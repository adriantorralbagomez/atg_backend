<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "variedad".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property OrdenPedidoinfo[] $ordenPedidoinfos
 * @property Orden[] $ordens
 * @property Parcela[] $parcelas
 * @property Pedidoinfo[] $pedidoinfos
 */
class Variedad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'variedad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 20],
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
        ];
    }

    /**
     * Gets query for [[OrdenPedidoinfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenPedidoinfos()
    {
        return $this->hasMany(OrdenPedidoinfo::class, ['variedad_id' => 'id']);
    }

    /**
     * Gets query for [[Ordens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdens()
    {
        return $this->hasMany(Orden::class, ['variedad_id' => 'id']);
    }

    /**
     * Gets query for [[Parcelas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParcelas()
    {
        return $this->hasMany(Parcela::class, ['variedad_id' => 'id']);
    }

    /**
     * Gets query for [[Pedidoinfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoinfos()
    {
        return $this->hasMany(Pedidoinfo::class, ['variedad_id' => 'id']);
    }

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }
}
