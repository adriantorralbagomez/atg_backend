<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pedidoinfo".
 *
 * @property int $id
 * @property int $pedido_id
 * @property int $variedad_id
 * @property int $cantidad
 *
 * @property OrdenPedidoinfo[] $ordenPedidoinfos
 * @property Pedido $pedido
 * @property Variedad $variedad
 */
class Pedidoinfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedidoinfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pedido_id', 'variedad_id', 'cantidad'], 'required'],
            [['pedido_id', 'variedad_id', 'cantidad'], 'integer'],
            [['pedido_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido::className(), 'targetAttribute' => ['pedido_id' => 'id']],
            [['variedad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variedad::className(), 'targetAttribute' => ['variedad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pedido_id' => 'Pedido ID',
            'variedad_id' => 'Variedad ID',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * Gets query for [[OrdenPedidoinfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenPedidoinfos()
    {
        return $this->hasMany(OrdenPedidoinfo::className(), ['pedidoinfo_id' => 'id']);
    }

    /**
     * Gets query for [[Pedido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido::className(), ['id' => 'pedido_id']);
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

    public static function cantidad($id){
        $where = "id = $id";
        
        $cant = ArrayHelper::map(self::find()->andWhere($where)->asArray()->all(),'id','cantidad');
        return $cant[$id];
    }
}
