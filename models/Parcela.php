<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "parcela".
 *
 * @property int $id
 * @property int $variedad_id
 * @property int $finca_id
 * @property string $nombre
 * @property int $cant_total
 * @property int $cant_disp
 *
 * @property Finca $finca
 * @property Variedad $variedad
 */
class Parcela extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parcela';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['variedad_id', 'finca_id', 'nombre', 'cant_total', 'cant_disp'], 'required'],
            [['variedad_id', 'finca_id', 'cant_total', 'cant_disp'], 'integer'],
            [['nombre'], 'string', 'max' => 30],
            [['variedad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variedad::className(), 'targetAttribute' => ['variedad_id' => 'id']],
            [['finca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Finca::className(), 'targetAttribute' => ['finca_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'variedad_id' => 'Variedad ID',
            'finca_id' => 'Finca ID',
            'nombre' => 'Nombre',
            'cant_total' => 'Cant Total',
            'cant_disp' => 'Cant Disp',
        ];
    }

    static $variedad = [
        "Blanca",
        "Roja",
        "Negra"
    ];

    /**
     * Gets query for [[Finca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinca()
    {
        return $this->hasOne(Finca::class, ['id' => 'finca_id']);
    }

    /**
     * Gets query for [[Variedad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariedad()
    {
        return $this->hasOne(Variedad::class, ['id' => 'variedad_id']);
    }

    public static function lookup($variedad=""){

        $where= "";

        if($variedad){
            //Añadir que salgan solo si tienen kilos disponibles
            $where = "variedad_id = $variedad";
        }

        return ArrayHelper::map(self::find()->andWhere($where)->asArray()->all(),'id','nombre','finca_id');
    }
}
