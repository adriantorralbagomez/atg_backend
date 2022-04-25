<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caja".
 *
 * @property int $id
 * @property int $orden_id
 * @property int|null $sector_id
 * @property int $tipocaja_id
 * @property int $etiqueta_id
 * @property string $estado
 *
 * @property Etiqueta $etiqueta
 * @property Orden $orden
 * @property Sector $sector
 * @property Tipocaja $tipocaja
 */
class Caja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'caja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id', 'tipocaja_id', 'etiqueta_id'], 'required'],
            [['orden_id', 'sector_id', 'tipocaja_id', 'proveedor_material_id', 'etiqueta_id'], 'integer'],
            [['estado'], 'string'],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sector::class, 'targetAttribute' => ['sector_id' => 'id']],
            [['tipocaja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tipocaja::class, 'targetAttribute' => ['tipocaja_id' => 'id']],
            [['etiqueta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Etiqueta::class, 'targetAttribute' => ['etiqueta_id' => 'id']],
            [['orden_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orden::class, 'targetAttribute' => ['orden_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orden_id' => 'Orden ID',
            'sector_id' => 'Sector ID',
            'tipocaja_id' => 'Tipocaja ID',
            'proveedor_material_id' => 'Proveedor - Material',
            'etiqueta_id' => 'Etiqueta ID',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Etiqueta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEtiqueta()
    {
        return $this->hasOne(Etiqueta::class, ['id' => 'etiqueta_id']);
    }

    /**
     * Gets query for [[Orden]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrden()
    {
        return $this->hasOne(Orden::class, ['id' => 'orden_id']);
    }

    /**
     * Gets query for [[Sector]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSector()
    {
        return $this->hasOne(Sector::class, ['id' => 'sector_id']);
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
        'T' => 'Terminada',
        'A' => 'Almacén',
        'L' => 'Línea de producción',
        'E' => 'Expedición',
        'CC' => 'Control de calidad',
        'ME' => 'Mal Estado',
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
}
