<?php

namespace api\modules\api\frontend\v1;

use Yii;
use yii\helpers\Inflector;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'api\modules\api\frontend\v1\controllers';

    public function init() {
        parent::init();

        Yii::$app->set("user", [
            'class' => \yii\web\User::class,
            'identityClass' => \common\models\user\User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ]);
    }

    public function getUrlRules() {
        $path = explode('/', Yii::$app->getRequest()->getPathInfo());
        $controller = isset($path[1]) ? Inflector::singularize($path[1]) : '';
        $controller = Inflector::camelize($controller) . 'Controller';
        $object = "{$this->controllerNamespace}\\{$controller}";
        
        if (!class_exists($object))
        {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        
        return $object::getRoutes();
    }

}
