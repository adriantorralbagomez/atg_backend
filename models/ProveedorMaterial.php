<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor_material".
 *
 * @property int $id
 * @property int $material_id
 * @property int $proveedor_id
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
            [['material_id', 'proveedor_id'], 'required'],
            [['material_id', 'proveedor_id'], 'integer'],
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
            'material_id' => 'Material ID',
            'proveedor_id' => 'Proveedor ID',
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
}
