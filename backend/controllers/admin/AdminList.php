<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use common\models\admin\Admin;
use yii\db\Query;
use yii\web\Response;

class AdminList extends Action
{

    public function run($search = null, $id = null)
    {
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new Query();
            $query->select('[[id]], [[email]] AS [[text]]')
                ->from('{{admins}}')
                ->filterWhere(['like', '[[email]]', $search])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Admin::findOne($id)->email];
        } else {
            $out['results'] = ['id' => 0, 'text' => Yii::t('app', 'No matching records found')];
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }
}