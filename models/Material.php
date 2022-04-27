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
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['tipocaja_id', 'stock_min'], 'integer'],
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
            'stock_min' => 'Stock Mínimo',
            'tipocaja_id' => 'Tipo de caja',
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

    //IDS MATERIALES
    const CAMPO = [0,1];
    const EXPEDICION = [2,3,4];

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }
    public static function filtrar_stock_min(){
        //Para el search en el index de proveedormaterial
        return array_unique(ArrayHelper::map(self::find()->asArray()->all(),'id','stock_min'));
    }
}
