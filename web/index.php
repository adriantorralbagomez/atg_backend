<?php

use yii\helpers\Url;

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

//Devuelve si el usuario tiene un rol.
function hasrole($rol){
    if(Yii::$app->user->isGuest) 
        $roluser='*'; //No logueado
    else
        $roluser=Yii::$app->user->identity->rol;
    return $roluser==$rol || $roluser=='A';
}

//Comprueba si el usuario es administrador
function isAdmin(){
    if(!Yii::$app->user->isGuest && Yii::$app->user->identity->rol == "A"){
        return true;
    }else{
        return false;
    }
}

//Si el usuario no es administrador, restringe el
//acceso y devuelve al login
function checkLogged(){
    if(!isAdmin()){
        $url = Url::home();
        header("Location: $url?r=site%2Flogin");
        exit;
    }
}

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
