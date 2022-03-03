<?php

namespace app\models;

use Yii;

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
            'tipocaja_id' => 'Tipocaja ID',
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
}
