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
        //variedad elegida, filtra también por variedad
        $where = "id NOT in (SELECT pedidoinfo_id FROM orden_pedidoinfo)";
        if ($variedad) {
            $where .= " AND variedad_id = $variedad";
        }

        //Información para el modelo create (gestionar el cambio de variedad, la fecha, etc...)
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
                    //Inicio de transacción
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
                    //Añadiendo datos de la orden
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
    {//COMPROBAR STOCK Y PEDIR SI NO HAY SUFICIENTE
        foreach ($mats as $mat) {
            $stock_act = Material::calc_stock_act($mat["id"]);
            if($stock_act > $mat["stock_min"]){
                //Comprobar si hay suficiente stock
                $dif = $stock_act - $mat["stock_min"];
                $porcentaje = ((float)$dif * 100) / $stock_act;
                $porcentaje = round($porcentaje, 0);  //Eliminar los decimales
                if ($porcentaje <= 30) {
                    $restante = $stock_act - $mat["stock_min"];
                    $extra = $mat["stock_min"] * 40 / 100;
                    $cantidad = $extra + $restante;
                    $prmt = ProveedorMaterial::find()->where(["material_id"=>$mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                    //Cerca del mínimo de stock
                    $this->pedirpedido($prmt, $dif * 2);
                }
            }else if($stock_act < $mat["stock_min"]){
                //Stock actual por debajo del mínimo
                $restante = $mat["stock_min"] - $stock_act;
                $extra = $mat["stock_min"] * 40 / 100;
                $cantidad = $extra + $restante;
                $prmt = ProveedorMaterial::find()->where(["material_id"=>$mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                $this->pedirpedido($prmt, $cantidad);
            }else if($stock_act == $mat["stock_min"] || $stock_act == 0){
                //Stock actual es el mínimo o 0
                $restante = $mat["stock_min"];
                $extra = $mat["stock_min"] * 40 / 100;
                $cantidad = $extra + $restante;
                $prmt = ProveedorMaterial::find()->where(["material_id"=>$mat["id"]])->orderBy(['precio' => SORT_ASC])->one();
                $this->pedirpedido($prmt, $cantidad);
            }
        }
    }

    public function comprobar_costes($model)
    {//Comprobar que se rellenen costes, si no están rellenados se ponen a 0
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
            //Inicio de transacción
            $trans = Yii::$app->db->beginTransaction();
            $mats = [];

            $model = $this->comprobar_costes($model);

            //comprobar cajas
            switch ($model->estado) {
                case 'T':
                    //Materiales: cajas y palets campo
                    $mats = Material::find()->where(["id" => [1, 2]])->asArray()->all();
                    //COMPROBAR SI HAY STOCK Y SI NO PEDIR
                    $this->compr_stock($mats);
                    break;
                case 'L': case 'ME': case 'CC':
                    //Materiales: caja final, cajas y palets expedición
                    //SE COMPRUEBAN EN LÍNEA DE PRODUCCIÓN PARA ASÍ PEDIRLOS ANTES DE QUE LLEGUEN
                    $mats = [];
                    if($model->estado == 'L'){
                        $mats = Material::find()->where(["id" => [3, 4, 5]])->asArray()->all();
                    }else{//Mal estado y control de calidad
                        $mats = Material::find()->where(["id" => [1, 2]])->asArray()->all();
                    }
                    //COMPROBAR SI HAY STOCK Y SI NO PEDIR
                    $this->compr_stock($mats);
                    //CALCULAR COSTE
                    //-----------------
                    $coste_prod_total = 0;
                    $coste_cajas_prod = 0;
                    $npaletprod = 0;
                    //Obtener todas las cajas menos las de Expedición
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
                    //Obtener proveedor de palets de campo (hasta producción) más barato
                    $prov_paletprod = ProveedorMaterial::find()->where(["material_id" => 2])->orderBy(['precio' => SORT_ASC])->one();
                    $coste_palets_prod = $prov_paletprod["precio"] * $npaletprod;

                    $coste_prod_total = $coste_cajas_prod + $coste_palets_prod;
                    $model->coste = $coste_prod_total;
                    $model->coste_cajas_prod = $coste_cajas_prod;
                    $model->coste_palets_prod = $coste_palets_prod;
                    $model->coste_prod_total = $coste_prod_total;
                    break;
                case 'E':
                    //CALCULAR COSTE
                    //-----------------
                    $coste_exp_total = 0;
                    $coste_cajas = 0;
                    $coste_cajas_exp = 0;
                    $ncajexp = 0;
                    $npaletsexp = 0;
                    //Obtener cajas de esa orden que han pasado a expedición
                    $cajas = Caja::find()->where(["orden_id" => $model->id])->andWhere(["estado" => "E"])->all();
                    //Calcular coste cajas
                    foreach ($cajas as $caja) {
                        $coste_cajas =  $coste_cajas + $caja["proveedorMaterial"]["precio"];
                    }
                    //Calcular el coste de las cajas de 15 KG (CAJA DE EXPEDICIÓN)
                    //En una caja de expedición caben 30 cajas finales
                    if (count($cajas) > 30) { //Comprobar si hay más de 30 cajas finales
                        $ncajexp = ceil(count($cajas) / 30); //calcular nº de cajas de expedicón
                    } else { //si no llegan a las 30 cajas finales se utiliza solo 1 caja de expedición de 15 kg
                        $ncajexp = 1;
                    }
                    //Obtener proveedor de cajas de expedición más barato
                    $prov_cajexp = ProveedorMaterial::find()->where(["material_id" => 3])->orderBy(['precio' => SORT_ASC])->one();
                    $coste_cajas_exp = $prov_cajexp["precio"] * $ncajexp;

                    //Calcular el coste de los palets de expedición
                    //En un palet caben aproximadamente 100 cajas (los palets soportan de media 1500kg)
                    //1500 entre 15kg = 100 cajas
                    if ($ncajexp > 100) {
                        $npaletsexp = ceil($ncajexp / 100);
                    } else {
                        $npaletsexp = 1;
                    }
                    //Obtener proveedor de palets de expedición más barato
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

            //Transacción correcta
            $trans->commit();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    function pedirpedido($prmt, $cant)
    {//Pedir material
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
