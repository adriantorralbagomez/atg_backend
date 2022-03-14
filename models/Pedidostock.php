<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pedidostock".
 *
 * @property int $id
 * @property int $proveedor_material_id
 * @property int $cantidad
 * @property string $fecha
 * @property string $estado
 *
 * @property ProveedorMaterial $proveedorMaterial
 */
class Pedidostock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedidostock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['proveedor_material_id', 'cantidad'], 'required'],
            [['proveedor_material_id', 'cantidad'], 'integer'],
            [['fecha'], 'safe'],
            [['estado'], 'string'],
            [['proveedor_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProveedorMaterial::class, 'targetAttribute' => ['proveedor_material_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'proveedor_material_id' => 'Proveedor Material ID',
            'cantidad' => 'Cantidad',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[ProveedorMaterial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedorMaterial()
    {
        return $this->hasOne(ProveedorMaterial::class, ['id' => 'proveedor_material_id']);
    }

    static $estados = [
        'P' => 'Pendiente',
        'E' => 'Enviado',
        'R' => 'Recogido',
    ];
    /**
     * Gets query for [[Estado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return self::$estados[$this->estado];
    }

    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }
}
