<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_pedidoinfo".
 *
 * @property int $id
 * @property int $orden_id
 * @property int $pedidoinfo_id
 * @property int $variedad_id
 * @property int $cantidad
 *
 * @property Orden $orden
 * @property Pedidoinfo $pedidoinfo
 * @property Variedad $variedad
 */
class OrdenPedidoinfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_pedidoinfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id', 'pedidoinfo_id', 'variedad_id', 'cantidad'], 'required'],
            [['orden_id', 'pedidoinfo_id', 'variedad_id', 'cantidad'], 'integer'],
            [['orden_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orden::className(), 'targetAttribute' => ['orden_id' => 'id']],
            [['variedad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variedad::className(), 'targetAttribute' => ['variedad_id' => 'id']],
            [['pedidoinfo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidoinfo::className(), 'targetAttribute' => ['pedidoinfo_id' => 'id']],
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
            'pedidoinfo_id' => 'Pedidoinfo ID',
            'variedad_id' => 'Variedad ID',
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
     * Gets query for [[Pedidoinfo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoinfo()
    {
        return $this->hasOne(Pedidoinfo::className(), ['id' => 'pedidoinfo_id']);
    }

    /**
     * Gets query for [[Variedad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariedad()
    {
        return $this->hasOne(Variedad::className(), ['id' => 'variedad_id']);
    }
}
