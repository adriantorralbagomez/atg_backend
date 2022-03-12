<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 *
 * @property ProveedorMaterial[] $proveedorMaterials
 */
class Proveedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['nombre'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * Gets query for [[ProveedorMaterials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedorMaterials()
    {
        return $this->hasMany(ProveedorMaterial::className(), ['proveedor_id' => 'id']);
    }
}
