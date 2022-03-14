<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "proveedor_material".
 *
 * @property int $id
 * @property int $material_id
 * @property int $proveedor_id
 * @property int $precio
 *
 * @property Material $material
 * @property Pedidostock[] $pedidostocks
 * @property Proveedor $proveedor
 */
class ProveedorMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor_material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['material_id', 'proveedor_id', 'precio'], 'required'],
            [['material_id', 'proveedor_id', 'precio'], 'integer'],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Material::class, 'targetAttribute' => ['material_id' => 'id']],
            [['proveedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::class, 'targetAttribute' => ['proveedor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_id' => 'Material',
            'proveedor_id' => 'Proveedor',
            'precio' => 'Precio',
        ];
    }

    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::class, ['id' => 'material_id']);
    }

    /**
     * Gets query for [[Pedidostocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidostocks()
    {
        return $this->hasMany(Pedidostock::class, ['proveedor_material_id' => 'id']);
    }

    /**
     * Gets query for [[Proveedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::class, ['id' => 'proveedor_id']);
    }
    public static function lookup(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','nombre');
    }
}
