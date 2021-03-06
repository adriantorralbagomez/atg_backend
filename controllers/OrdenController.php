<?php

namespace app\controllers;

use app\models\Caja;
use Yii;
use app\models\Finca;
use app\models\Material;
use app\models\Nlote;
use app\models\Orden;
use yii\db\Exception;
use app\models\Parcela;
use yii\web\Controller;
use app\models\Variedad;
use app\models\Pedidoinfo;
use app\models\OrdenSearch;
use yii\filters\VerbFilter;
use app\models\OrdenPedidoinfo;
use app\models\PedidoinfoSearch;
use app\models\Pedidostock;
use app\models\ProveedorMaterial;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * OrdenController implements the CRUD actions for Orden model.
 */
class OrdenController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Orden models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrdenSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function getFincas()
    {
        return Finca::lookup();
    }

    public function getVariedades()
    {
        return Variedad::lookup();
    }

    public function getParcelas()
    {
        return Parcela::lookup();
    }

    /**
     * Displays a single Orden model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function updNlote($num)
    {
        $num = $num + 1;
        Yii::$app->db->createCommand()
            ->update('nlote', ['numero' => $num])
            ->execute();
    }

    /**
     * Creates a new Orden model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($variedad = "")
    {
        //Almacena la sentencia para filtrar los pedidoinfos, en caso de haber
        //variedad elegida, filtra tambi??n por variedad
        $where = "id NOT in (SELECT pedidoinfo_id FROM orden_pedidoinfo)";
        if ($variedad) {
            $where .= " AND variedad_id = $variedad";
        }

        //Informaci??n para el modelo create (gestionar el cambio de variedad, la fecha, etc...)
        $searchModel = new PedidoinfoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $where);
        $model = new Orden();
        $model->fecha = date('Y-m-d');
        $model->variedad_id = $variedad;

        if ($this->request->isPost) {
            $post = $this->request->post();
            //Si se ha seleccionado como minimo un pedidoinfo
            if (isset($post['ids'])) {
                $ids = $post['ids'];
                try {
                    //Inicio de transacci??n
                    $trans = Yii::$app->db->beginTransaction();
                    //Genera un string para igualar todos los numeros de lote a la misma longitud
                    $strLote = "";
                    for ($i = strlen(Nlote::numLote()); $i < 6; $i++) {
                        $strLote .= "0";
                    }
                    $strLote .= Nlote::numLote();
                    //Obtiene la cantidad total de 
                    $cant_tot = 0;
                    foreach ($ids as $id) {
                        $cant_tot = $cant_tot + intval(Pedidoinfo::cantidad($id));
                    }
                    //A??adiendo datos de la orden
                    $orden = new Orden();
                    $orden->lote = date('Y') . "-" . $strLote;
                    $orden->variedad_id = $variedad;
                    $orden->finca_id = key(Finca::fromFinca($post['Orden']['parcela_id']));
                    $orden->parcela_id = $post['Orden']['parcela_id'];
                    $orden->fecha = $post['Orden']['fecha'];
                    $orden->cantidad = $cant_tot;
                    $orden->estado = "P";
                    //Poner costes a 0
                    $orden->coste = 0;
                    $orden->coste_prod_total = 0;
                    $orden->coste_palets_prod = 0;
                    $orden->coste_cajas_prod = 0;
                    $orden->coste_exp_total = 0;
                    $orden->coste_cajas_exp = 0;
                    $orden->coste_palets_exp = 0;
                    //Se crea por defecto en estado P, se puede cambiar luego
                    if ($orden->save()) {
                        foreach ($ids as $id) {
                            $linea = new OrdenPedidoinfo();
                            $linea->orden_id = $orden->id;
                            $linea->pedidoinfo_id = $id;
                            $linea->variedad_id = $post['Orden']['variedad_id'];
                            $linea->cantidad = Pedidoinfo::cantidad($id);
                            $linea->save();
                        }
                        $trans->commit();
                        $this->updNlote(intval(Nlote::numLote()));
                    }
                } catch (Exception $e) {
                    var_dump($e);
                }
                return $this->redirect(['view', 'id' => $orden->id]);
            } else {
                //Si no se ha elegido se devuelve a la pagina de crear, con un error
                //MENSAJE DE ERROR POR HACER (o no decir nada sobre ello)
                Yii::$app->controller->redirect('index.php?r=orden%2Fcreate&error=e');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    public function calc_stock_act($provmats)
    {
        $stock_act = 0;
        foreach ($provmats as $pm) {
            $stock_act = $stock_act + $pm["stock_act"];
        }
        return $stock_act;
    }

    public function compr_stock($mats)
    { //COMPROBAR STOCK Y PEDIR SI NO HAY SUFICIENTE
        foreach ($mats as $mat) {
            $stock_act = Material::calc_stock_act($mat["id"]);
            if ($stock_act > $mat["stock_min"]) {
                //Comprobar si hay suficiente stock
                $dif = $stock_act - $mat["stock_min"];
                $porcentaje = ((float)$dif * 100) / $stock_act;
                $porcentaje = round($porcentaje, 0);  //Eliminar los decimales
                if ($porcentaje <= 30) {
                    $restante = $stock_act - $mat["stock_min"];
                    $extra = $mat["stock_min"] * 40 / 100;
                    $cantidad = $extra + $restante;
                    $prmt = ProveedorMaterial::find()->where(["material_id" => $mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                    //Cerca del m??nimo de stock
                    $this->pedirpedido($prmt, $dif * 2);
                }
            } else if ($stock_act < $mat["stock_min"]) {
                //Stock actual por debajo del m??nimo
                $restante = $mat["stock_min"] - $stock_act;
                $extra = $mat["stock_min"] * 40 / 100;
                $cantidad = $extra + $restante;
                $prmt = ProveedorMaterial::find()->where(["material_id" => $mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                $this->pedirpedido($prmt, $cantidad);
            } else if ($stock_act == $mat["stock_min"] || $stock_act == 0) {
                //Stock actual es el m??nimo o 0
                $restante = $mat["stock_min"];
                $extra = $mat["stock_min"] * 40 / 100;
                $cantidad = $extra + $restante;
                $prmt = ProveedorMaterial::find()->where(["material_id" => $mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                $this->pedirpedido($prmt, $cantidad);
            }
        }
    }

    public function comprobar_costes($model)
    { //Comprobar que se rellenen costes, si no est??n rellenados se ponen a 0
        if ($model->coste == null) {
            $model->coste = 0;
        }
        if ($model->coste_cajas_prod == null) {
            $model->coste_cajas_prod = 0;
        }
        if ($model->coste_palets_prod == null) {
            $model->coste_palets_prod = 0;
        }
        if ($model->coste_prod_total == null) {
            $model->coste_prod_total = 0;
        }
        if ($model->coste_cajas_exp == null) {
            $model->coste_cajas_exp = 0;
        }
        if ($model->coste_palets_exp == null) {
            $model->coste_palets_exp = 0;
        }
        if ($model->coste_exp_total == null) {
            $model->coste_exp_total = 0;
        }
        return $model;
    }

    public function lineaProduccion($model)
    {
        //Materiales: caja final, cajas y palets expedici??n
        //SE COMPRUEBAN EN L??NEA DE PRODUCCI??N PARA AS?? PEDIRLOS ANTES DE QUE LLEGUEN
        $mats = [];
        if ($model->estado == 'L') {
            $mats = Material::find()->where(["id" => [3, 4, 5]])->asArray()->all();
        } else { //Mal estado y control de calidad
            $mats = Material::find()->where(["id" => [1, 2]])->asArray()->all();
        }
        //COMPROBAR SI HAY STOCK Y SI NO PEDIR
        $this->compr_stock($mats);
        //CALCULAR COSTE
        //-----------------
        $coste_prod_total = 0;
        $coste_cajas_prod = 0;
        $npaletprod = 0;
        //Obtener todas las cajas menos las de Expedici??n
        $cajas_prod = Caja::find()->where(["orden_id" => $model->id])->andWhere(["<>", "estado", "E"])->all();
        foreach ($cajas_prod as $cajaprod) { //calcular coste cajas
            $coste_cajas_prod = $coste_cajas_prod + $cajaprod["proveedorMaterial"]["precio"];
        }

        //Calcular el coste de los palets de campo
        //En un palet caben aproximadamente 100 cajas (los palets soportan de media 1500kg)
        //1500 entre 15kg = 100 cajas
        if (count($cajas_prod) > 100) {
            $npaletprod = ceil(count($cajas_prod) / 100);
        } else {
            $npaletprod = 1;
        }
        //Obtener proveedor de palets de campo (hasta producci??n) m??s barato
        $prov_paletprod = ProveedorMaterial::find()->where(["material_id" => 2])->orderBy(['precio' => SORT_ASC])->one();
        $coste_palets_prod = $prov_paletprod["precio"] * $npaletprod;

        $coste_prod_total = $coste_cajas_prod + $coste_palets_prod;
        $model->coste = $coste_prod_total;
        $model->coste_cajas_prod = $coste_cajas_prod;
        $model->coste_palets_prod = $coste_palets_prod;
        $model->coste_prod_total = $coste_prod_total;
        return $model;
    }

    public function rellenarDatosCajas($num_caj_campo, $model_id, $tipocaja_id, $provmat_id, $estado)
    {
        $datos_cajas = [];
        for ($c = 0; $c < $num_caj_campo; $c++) {
            $datos_cajas[$c] = [
                "orden_id" => $model_id,
                "tipocaja_id" => $tipocaja_id,
                "proveedor_material_id" => $provmat_id,
                "estado" => $estado,
            ];
        }
        return $datos_cajas;
    }

    /**
     * Updates an existing Orden model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post())) {
            //Inicio de transacci??n
            $trans = Yii::$app->db->beginTransaction();
            $mats = [];

            $model = $this->comprobar_costes($model);

            //Cambiar estado de todas las cajas
            try {
                switch ($model->estado) {
                    case 'L': //Si se pasa a producci??n
                        $this->cambiarEstado($model->id, "L");
                        break;
                    case 'E': //Si se pasan a expedici??n -> crear nuevas cajas (0,5 kg)
                        //Obtener cajas con estado L (son las que se pasar??n a cajas nuevas de E)
                        $cajs_linea = Caja::find()->where(["orden_id" => $model->id])->andWhere(["estado" => "L"])->all();
                        //n??mero de cajas de expedici??n = 10kg (kg caja) * n?? cajas en estado l??nea / 0,5 (kg de las cajas finales)
                        $num_caj_exp = ceil(10 * count($cajs_linea) / 0.5);
                        $provmat_cajexp = ProveedorMaterial::find()->where(["material_id" => 1])->orderBy(['precio' => SORT_ASC])->one();
                        //crear cajas expedici??n
                        try {
                            $datos_cajas = $datos_cajas = $this->rellenarDatosCajas($num_caj_exp, $model->id, 3, $provmat_cajexp["id"], "E");
                            Yii::$app->db->createCommand()->batchInsert('caja', ['orden_id', 'tipocaja_id', 'proveedor_material_id', 'estado'], $datos_cajas)->execute();
                        } catch (Exception $e) {
                            var_dump($e);
                        }
                        break;
                    default: //Caso de ME, CC, P y T
                        Caja::updateAll(['estado' => $model->estado], ['=', 'orden_id', $model->id]);
                        break;
                }
            } catch (Exception $e) {
                var_dump($e);
            }

            //comprobar cajas
            switch ($model->estado) {
                case 'T':
                    //Materiales: cajas y palets campo
                    $mats = Material::find()->where(["id" => [1, 2]])->asArray()->all();
                    //COMPROBAR SI HAY STOCK Y SI NO PEDIR
                    $this->compr_stock($mats);
                    //Generar cajas de campo (cajas de 10kg)
                    $num_caj_campo = ceil($model->cantidad / 10);
                    //obtener provedor de material con precio m??s barato
                    $provmat_cajcampo = ProveedorMaterial::find()->where(["material_id" => 1])->orderBy(['precio' => SORT_ASC])->one();
                    try {
                        $datos_cajas = $datos_cajas = $this->rellenarDatosCajas($num_caj_campo, $model->id, 1, $provmat_cajcampo["id"], "T");
                        Yii::$app->db->createCommand()->batchInsert('caja', ['orden_id', 'tipocaja_id', 'proveedor_material_id', 'estado'], $datos_cajas)->execute();
                    } catch (Exception $e) {
                        var_dump($e);
                    }
                    break;
                case 'L':
                    $model = $this->lineaProduccion($model);
                    break;
                case 'ME':
                    $model = $this->lineaProduccion($model);
                    break;
                case 'CC':
                    $model = $this->lineaProduccion($model);
                    break;
                case 'E':
                    //CALCULAR COSTE
                    //-----------------
                    $coste_exp_total = 0;
                    $coste_cajas = 0;
                    $coste_cajas_exp = 0;
                    $ncajexp = 0;
                    $npaletsexp = 0;
                    //Obtener cajas de esa orden que han pasado a expedici??n
                    $cajas = Caja::find()->where(["orden_id" => $model->id])->andWhere(["estado" => "E"])->all();
                    //Calcular coste cajas
                    foreach ($cajas as $caja) {
                        $coste_cajas =  $coste_cajas + $caja["proveedorMaterial"]["precio"];
                    }
                    //Calcular el coste de las cajas de 15 KG (CAJA DE EXPEDICI??N)
                    //En una caja de expedici??n caben 30 cajas finales
                    if (count($cajas) > 30) { //Comprobar si hay m??s de 30 cajas finales
                        $ncajexp = ceil(count($cajas) / 30); //calcular n?? de cajas de expedic??n
                    } else { //si no llegan a las 30 cajas finales se utiliza solo 1 caja de expedici??n de 15 kg
                        $ncajexp = 1;
                    }
                    //Obtener proveedor de cajas de expedici??n m??s barato
                    $prov_cajexp = ProveedorMaterial::find()->where(["material_id" => 3])->orderBy(['precio' => SORT_ASC])->one();
                    $coste_cajas_exp = $prov_cajexp["precio"] * $ncajexp;

                    //Calcular el coste de los palets de expedici??n
                    //En un palet caben aproximadamente 100 cajas (los palets soportan de media 1500kg)
                    //1500 entre 15kg = 100 cajas
                    if ($ncajexp > 100) {
                        $npaletsexp = ceil($ncajexp / 100);
                    } else {
                        $npaletsexp = 1;
                    }
                    //Obtener proveedor de palets de expedici??n m??s barato
                    $prov_paletexp = ProveedorMaterial::find()->where(["material_id" => 5])->orderBy(['precio' => SORT_ASC])->one();
                    $coste_palets_exp = $prov_paletexp["precio"] * $npaletsexp;

                    //Sumar el resto de costes
                    $coste_exp_total = $coste_cajas + $coste_cajas_exp + $coste_palets_exp;
                    $model->coste = $model->coste + $coste_exp_total;
                    $model->coste_cajas_exp = $coste_cajas_exp;
                    $model->coste_palets_exp = $coste_palets_exp;
                    $model->coste_exp_total = $coste_exp_total;
                    break;
            }

            //Transacci??n correcta
            if ($model->save()) {
                $trans->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function cambiarEstado($model_id, $estado)
    {
        $condiciones = [
            'and',
            ['=', 'orden_id', $model_id],
            ['<>', 'estado', "ME"],
            ['<>', 'estado', "CC"],
        ];
        if ($estado == "L") {
            Caja::updateAll(['estado' => $estado], $condiciones);
        } else { //expedici??n -> cambiar tipo de caja
            $prov_cajexp = ProveedorMaterial::find()->where(["material_id" => 4])->orderBy(['precio' => SORT_ASC])->one();
            Caja::updateAll(['estado' => $estado, 'tipocaja_id' => 3, 'proveedor_material_id' => $prov_cajexp["id"]], $condiciones);
        }
    }

    function pedirpedido($prmt, $cant)
    { //Pedir material
        $pedstock = new Pedidostock();
        $pedstock->proveedor_material_id = $prmt->id;
        $pedstock->cantidad = $cant;
        $pedstock->estado = "P";
        $pedstock->fecha = date('Y-m-d H:i:s');
        $pedstock->save();
    }

    /**
     * Deletes an existing Orden model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orden model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Orden the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orden::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
