<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "etiqueta".
 *
 * @property int $id
 * @property int $cliente_id
 * @property string $formato
 *
 * @property Caja[] $cajas
 * @property Cliente $cliente
 */
class Etiqueta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'etiqueta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'formato'], 'required'],
            [['cliente_id'], 'integer'],
            [['formato'], 'string'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['cliente_id' => 'id']],
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
            'formato' => 'Formato',
        ];
    }

    /**
     * Gets query for [[Cajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCajas()
    {
        return $this->hasMany(Caja::class, ['etiqueta_id' => 'id']);
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::class, ['id' => 'cliente_id']);
    }
}
