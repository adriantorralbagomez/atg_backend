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
 * @property int $stock_act
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
            [['nombre', 'stock_min', 'stock_act'], 'required'],
            [['descripcion'], 'string'],
            [['tipocaja_id', 'stock_min', 'stock_act'], 'integer'],
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
            'tipocaja_id' => 'Tipo de caja',
            'stock_min' => 'Stock Min',
            'stock_act' => 'Stock Act',
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

    public static function comprobarStock($data){
        if ($data->stock_act > $data->stock_min) {
            //Si el stock actual supera el mínimo
            //Calcular porcentaje del stock mínimo sobre el stock actual
            $dif = $data->stock_act - $data->stock_min;
            $porcentaje = ((float)$dif * 100) / $data->stock_act;
            $porcentaje = round($porcentaje, 0);  //Eliminar los decimales

            if ($porcentaje <= 30) {
                //Cerca del mínimo de stock
                return 'LightCoral';
            } else if ($porcentaje <= 60) {
                //Stock "normal"
                return 'Gold';
            } else if ($porcentaje > 80) {
                //Hay stock de sobra
                return 'LightGreen';
            }
        } else if($data->stock_act < $data->stock_min) {
            //Stock por debajo de mínimos
            return 'LightCoral';
        } else if ($data->stock_act == $data->stock_min) {
            //Hay stock de sobra
            return 'Gold';
        }
    }

    //IDS MATERIALES
    const CAMPO = [0,1];
    const EXPEDICION = [2,3,4];

    public static function stockActual(){
        return [
            "LightCoral"=>"No hay suficiente stock",
            "Gold"=>"Queda poco stock",
            "LightGreen"=>"Suficiente stock"
        ];
    }

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }
}
