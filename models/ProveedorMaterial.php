<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "proveedor_material".
 *
 * @property int $id
 * @property int $material_id
 * @property int $proveedor_id
 * @property int $precio
 *
 * @property Material $material
 * @property Pedidostock[] $pedidostocks
 * @property Proveedor $proveedor
 */
class ProveedorMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor_material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['material_id', 'proveedor_id', 'precio'], 'required'],
            [['material_id', 'proveedor_id', 'stock_min', 'stock_act'], 'integer'],
            [['precio'], 'number'],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Material::class, 'targetAttribute' => ['material_id' => 'id']],
            [['proveedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::class, 'targetAttribute' => ['proveedor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_id' => 'Material',
            'proveedor_id' => 'Proveedor',
            'stock_min' => 'Stock Min',
            'stock_act' => 'Stock Act',
            'precio' => 'Precio',
        ];
    }

    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::class, ['id' => 'material_id']);
    }

    /**
     * Gets query for [[Pedidostocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidostocks()
    {
        return $this->hasMany(Pedidostock::class, ['proveedor_material_id' => 'id']);
    }

    /**
     * Gets query for [[Proveedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::class, ['id' => 'proveedor_id']);
    }

    //Obtener Material a partir de material_id para el lookup
    public static function obtenerMaterial($mat_id){
        return Material::find()->where(['id'=>$mat_id])->one();
    }
    //Obtener Material a partir de proveedor_id para el lookup
    public static function obtenerProveedor($prov_id){
        return Proveedor::find()->where(['id'=>$prov_id])->one();
    }

    public static function comprobarStock($data){
        if($data->stock_act == 0){
            //No hay stock
            return 'LightCoral';
        }else if ($data->stock_act > $data->stock_min) {
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
            } else if ($porcentaje > 60) {
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

    public static function stockActual(){
        return [
            "LightCoral"=>"No hay suficiente stock",
            "Gold"=>"Queda poco stock",
            "LightGreen"=>"Suficiente stock"
        ];
    }

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id',function($data){
            $material = ProveedorMaterial::obtenerMaterial($data["material_id"]);
            $proveedor = ProveedorMaterial::obtenerProveedor($data["proveedor_id"]);
            //Se devuelve una cadena con el nombre del material y el proveedor para seleccionar
            //en las vistas de crear y atualizar de pedidostock (Pedido de Materiales)
            return $material->nombre. ' - ' .$proveedor->nombre;
        });
    }
}
