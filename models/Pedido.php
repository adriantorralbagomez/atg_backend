<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido".
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $cantidad
 * @property string $estado
 * @property string $fecha
 *
 * @property Cliente $cliente
 * @property Pedidoinfo[] $pedidoinfos
 */
class Pedido extends \yii\db\ActiveRecord
{

    static $estados = [
        'P' => 'Pendiente',
        'R' => 'Recogido',
        'C' => 'Completado',
    ];

    public function getEstadoText(){
        return self::$estados[$this->estado];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'cantidad', 'fecha'], 'required'],
            [['cliente_id', 'cantidad'], 'integer'],
            [['estado'], 'string'],
            [['fecha'], 'safe'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'cantidad' => 'Cantidad',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    /**
     * Gets query for [[Pedidoinfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoinfos()
    {
        return $this->hasMany(Pedidoinfo::className(), ['pedido_id' => 'id']);
    }
}
