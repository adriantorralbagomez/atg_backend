<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "material".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int|null $tipocaja_id
 * @property int $stock_min
 *
 * @property Tipocaja $tipocaja
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['tipocaja_id', 'stock_min'], 'integer'],
            [['nombre'], 'string', 'max' => 20],
            [['tipocaja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tipocaja::class, 'targetAttribute' => ['tipocaja_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'stock_min' => 'Stock Mínimo',
            'tipocaja_id' => 'Tipo de caja',
        ];
    }

    /**
     * Gets query for [[Tipocaja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipocaja()
    {
        return $this->hasOne(Tipocaja::class, ['id' => 'tipocaja_id']);
    }

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }

    public static function calc_stock_act($material_id){
        $stock_act = 0;
        $provmats = ProveedorMaterial::find()->where(["material_id"=>$material_id])->all();
        foreach ($provmats as $pm) {
            $stock_act = $stock_act + $pm["stock_act"];
        }
        return $stock_act;
    }

    public static function comprobarStock($data, $stock_act){
        if($stock_act == 0){
            //No hay stock
            return 'LightCoral';
        }else if ($stock_act > $data->stock_min) {
            //Si el stock actual supera el mínimo
            //Calcular porcentaje del stock mínimo sobre el stock actual
            $dif = $stock_act - $data->stock_min;
            $porcentaje = ((float)$dif * 100) / $stock_act;
            $porcentaje = round($porcentaje, 0);  //Eliminar los decimales

            if ($porcentaje <= 30) {
                //Cerca del mínimo de stock
                return 'LightCoral';
            } else if ($porcentaje <= 60) {
                //Stock "normal"
                return 'Gold';
            } else if ($porcentaje > 60) {
                //Hay stock de sobra
                return 'LightGreen';
            }
        } else if($stock_act < $data->stock_min) {
            //Stock por debajo de mínimos
            return 'LightCoral';
        } else if ($stock_act == $data->stock_min) {
            //Hay stock de sobra
            return 'Gold';
        }
    }

    public static function filtrar_stock_min(){
        //Para el search en el index de proveedormaterial
        return array_unique(ArrayHelper::map(self::find()->asArray()->all(),'id','stock_min'));
    }

    public static function filtrar_stock_act(){
        //ARREGLAR !!!!!!!!!!!
        //!!!!!!!!
        //!!!!!!!!
        //!!!!!!!!
        //Para el search en el index de proveedormaterial
        $materiales = Material::find()->all();
        $stock_acts = [];
        foreach ($materiales as $mt) {
            array_push($stock_acts,Material::calc_stock_act($mt->id));
        }
        return array_unique($stock_acts);
    }
}
