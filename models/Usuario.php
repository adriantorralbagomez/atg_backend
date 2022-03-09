<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property int|null $finca_id
 * @property string $usuario
 * @property string $password
 * @property string $nombre
 * @property string $mail_corp
 * @property string $mail_per
 * @property int $telefono
 * @property string $rol
 * @property string $token
 *
 * @property Finca $finca
 */
class Usuario extends ActiveRecord implements IdentityInterface
{

    static $roles = [
        'A' => 'Administrador',
        'G' => 'Gestión',
        'CC' => 'Control Campo',
        'CP' => 'Control Planta',
        'PP' => 'Producción',
        'CE' => 'Control Expedición',
    ];
    //('A','CC','CP','PP','CE')	
    // public $ide;
    // public $usu;
    // public $password;
    // public $authKey;
    // public $accessToken;


    public function beforeSave($insert)
    {
        //Transformar contraseña a md5
        //antes de guardar
        if (($this->password != null) || ($this->password != '') || ($this->token != '')) {
            $pass = md5($this->password);
            $this->password = $pass;
            $this->token = $this->getUniqueAccessToken();
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }

    private function getUniqueAccessToken()
    {
        $resultado = md5(Yii::$app->security->generateRandomString() . '_' . time());
        $identity = $this->findIdentityByAccessToken($resultado);
        if ($identity) {
            $resultado = $this->getUniqueAccessToken();
        }
        return $resultado;
    }

    public function getRolText()
    {
        return self::$roles[$this->rol];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * Finds user by usuario
     *
     * @param string $usu
     * @return static|null
     */
    public static function findByusername($username)
    {
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['usu'], $usu) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;
        return static::findOne(['usuario' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    // public static function findIdentityByAccessToken($token, $type = null)
    // {
    //     foreach (self::$users as $user) {
    //         if ($user['accessToken'] === $token) {
    //             return new static($user);
    //         }
    //     }

    //     return null;
    // }
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['finca_id', 'telefono'], 'integer'],
            [['usuario', 'password', 'nombre', 'mail_corp', 'mail_per', 'telefono', 'rol'], 'required'],
            [['rol'], 'string'],
            [['usuario'], 'string', 'max' => 16],
            [['password'], 'string', 'max' => 32],
            [['nombre'], 'string', 'max' => 30],
            [['mail_corp', 'mail_per'], 'string', 'max' => 60],
            [['finca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Finca::class, 'targetAttribute' => ['finca_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'finca_id' => 'Finca ID',
            'usuario' => 'Usuario',
            'password' => 'Password',
            'nombre' => 'Nombre',
            'mail_corp' => 'Mail Corp',
            'mail_per' => 'Mail Per',
            'telefono' => 'Telefono',
            'rol' => 'Rol',
        ];
    }

    /**
     * Gets query for [[Finca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinca()
    {
        return $this->hasOne(Finca::class, ['id' => 'finca_id']);
    }
}
