<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Sms extends Component {
    
    public function send($number, $content) {
        
        $this->realTimeSend($number, $content);
        
    }

    public function realTimeSend($number, $content)
    {
        try {
            
            $client = new \SoapClient(Yii::$app->params['sms']['endpoint']);
            
            $parameters = [
                'username' => Yii::$app->params['sms']['username'],
                'password' => Yii::$app->params['sms']['password'],
                'from' => Yii::$app->params['sms']['number'],
                'to' => [$number],
                'text' => $content,
                'flash' => false,
            ]; 
            $res = $client->SendSms($parameters);
            return $res->SendSmsResult == 0 ? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
