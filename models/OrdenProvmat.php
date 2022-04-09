<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_provmat".
 *
 * @property int $id
 * @property int $orden_id
 * @property int $proveedor_material_id
 * @property int|null $pedidostock_id
 * @property int $cantidad
 *
 * @property Orden $orden
 * @property Pedidostock $pedidostock
 * @property ProveedorMaterial $proveedorMaterial
 */
class OrdenProvmat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_provmat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id', 'proveedor_material_id', 'cantidad'], 'required'],
            [['orden_id', 'proveedor_material_id', 'pedidostock_id', 'cantidad'], 'integer'],
            [['orden_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orden::className(), 'targetAttribute' => ['orden_id' => 'id']],
            [['proveedor_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProveedorMaterial::className(), 'targetAttribute' => ['proveedor_material_id' => 'id']],
            [['pedidostock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidostock::className(), 'targetAttribute' => ['pedidostock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orden_id' => 'Orden ID',
            'proveedor_material_id' => 'Proveedor Material ID',
            'pedidostock_id' => 'Pedidostock ID',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * Gets query for [[Orden]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrden()
    {
        return $this->hasOne(Orden::className(), ['id' => 'orden_id']);
    }

    /**
     * Gets query for [[Pedidostock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidostock()
    {
        return $this->hasOne(Pedidostock::className(), ['id' => 'pedidostock_id']);
    }

    /**
     * Gets query for [[ProveedorMaterial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedorMaterial()
    {
        return $this->hasOne(ProveedorMaterial::className(), ['id' => 'proveedor_material_id']);
    }
}
