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
use app\models\OrdenProvmat;
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

    public function calc_stock_act($provmats){
        $stock_act = 0;
        foreach ($provmats as $pm) {
            $stock_act = $stock_act + $pm["stock_act"];
        }
        return $stock_act;
    }

    public function comprobar_stock($provmats, $total_stock_act, $stock_min)
    {//Comprobar stock
        if($total_stock_act < $stock_min){
            foreach ($provmats as $prmt) {
                if (($prmt["stock_act"] > $stock_min) || ($prmt["stock_act"] > $stock_min)) {
                    //Si el stock actual supera el mínimo
                    //Calcular porcentaje del stock mínimo sobre el stock actual
                    $dif = $prmt["stock_act"] - $stock_min;
                    $porcentaje = ((float)$dif * 100) / $prmt["stock_act"];
                    $porcentaje = round($porcentaje, 0);  //Eliminar los decimales
    
                    if ($porcentaje <= 30) {
                        //Cerca del mínimo de stock
                        $this->pedirpedido($prmt, $dif*2);
                    }
                } else if (($prmt["stock_act"] < $stock_min) || ($prmt["stock_act"] < $stock_min)) {
                    //Stock por debajo de mínimos
                    //Cantidad de cajas a pedir
                    $restante = $stock_min - $total_stock_act;
                    $extra = $stock_min * 40 / 100;
                    //$cantidad = $extra + $restante;
                    //REVISAR
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    //!!!!!!!!!!!!!!
                    $this->pedirpedido($prmt, $extra);
                }
            }
        }
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
            $stock_min = 0;
            $total_stock_act = 0;
            //comprobar cajas
            switch ($model->estado) {
                case 'T':
                    //Materiales: cajas y palets campo
                    $mats = Material::find()->where(["id" => Material::CAMPO])->asArray()->all();
                    foreach ($mats as $mat) {
                        //Lista de proveedores de material 
                        $provmats = ProveedorMaterial::find()->where(["material_id" => $mat["id"]])->asArray()->all();
                        //Suma de todos los stock actual (de todos sus proveedores) del material
                        $total_stock_act = $this->calc_stock_act($provmats);
                        $stock_min = $mat["stock_min"];
                        $this->comprobar_stock($provmats, $total_stock_act, $stock_min);
                    }
                    break;
                case 'L':
                    //Materiales: caja final, cajas y palets expedición
                    $mats = Material::find()->where(["id" => Material::EXPEDICION])->asArray()->all();
                    foreach ($mats as $mat) {
                        //Lista de proveedores de material 
                        $provmats = ProveedorMaterial::find()->where(["material_id" => $mat["id"]])->asArray()->all();
                        //Suma de todos los stock actual (de todos sus proveedores) del material
                        $total_stock_act = $this->calc_stock_act($provmats);
                        $stock_min = $mat["stock_min"];
                        $this->comprobar_stock($provmats, $total_stock_act, $stock_min);
                    }
                    break;
                case 'E':
                    //CALCULAR COSTE
                    //-----------------
                    $costetotal = 0;
                    $coste_cajas = 0;
                    $cajas = Caja::find()->where(["orden_id"=>$model->id])->all();
                    //Calcular coste cajas
                    foreach ($cajas as $caja) {
                        $coste_cajas .= $caja["proveedorMaterial"]["precio"];
                    }
                    //FALTA CALCULAR EL COSTE DE LA CAJA DE 15 KG (CAJA DE EXPEDICIÓN)
                    $ncajexp = count($cajas) / 30;
                    //!!!
                    //CALCULAR COSTE PALETS

                    //!!Sumar el resto de costes
                    $costetotal = $coste_cajas;
                    $model->coste = $costetotal;
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

    function pedirpedido($material, $dif)
    {
        //Pedir material
        $pedstock = new Pedidostock();
        //Se obtiene el primer proveedor - material con precio más bajo
        $provmat = ProveedorMaterial::find()->where(["material_id" => $material["id"]])->orderBy(['precio' => SORT_ASC])->one();
        $pedstock->proveedor_material_id = $provmat->id;
        $pedstock->cantidad = $dif;
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
