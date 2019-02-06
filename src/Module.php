<?php

namespace hessian\yii\token;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'hessian\yii\token\controllers';

    public function init()
    {
        parent::init();
        // todo 待完成
        if (!isset(Yii::$app->i18n->translations['token'])) {
            Yii::$app->i18n->translations['token'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@hessian/yii/token/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'token' => 'token.php',
                ]
            ];
        }
    }
}
