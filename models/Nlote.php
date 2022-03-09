<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nlote".
 *
 * @property int $id
 * @property int $numero
 */
class Nlote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nlote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero'], 'required'],
            [['numero'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero' => 'Numero',
        ];
    }

    //Obtiene
    public static function numLote(){
        $n = ArrayHelper::map(self::find()->asArray()->all(),'id','numero');
        return $n[1];
    }
}
