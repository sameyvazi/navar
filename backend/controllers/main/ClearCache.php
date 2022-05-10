<?php

namespace backend\controllers\main;

use Yii;
use yii\base\Action;
use yii\web\Response;

class ClearCache extends Action {

    public function run() {

        apc_clear_cache();
        apc_clear_cache('user');
        apc_clear_cache('opcode');

        pclose(popen('rm -R /var/www/inavar_v2/public_html/runtime/cache/', 'r'));
        pclose(popen('rm -R /var/www/musicplus_v2/public_html3/runtime/cache/', 'r'));
        pclose(popen('rm -R /var/www/navar_en/public_html/runtime/cache/', 'r'));
        pclose(popen('rm -R /var/www/navar_fa/public_html/runtime/cache/', 'r'));

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'result' => Yii::$app->getCache()->flush()
        ];
    }

}
