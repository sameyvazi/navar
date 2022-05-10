<?php
namespace common\components;

use Yii;
use yii\base\Component;

class Email extends Component
{

    public function send($view, $data, $to, $subject, $from = '')
    {
        return $this->realTimeSend($view, $data, $to, $subject, $from);

    }

    public function realTimeSend($view, $data, $to, $subject, $from = '')
    {
        if (empty($from))
        {
            $from = Yii::$app->params['systemEmail'];
        }

        $mailer = Yii::$app->mailer->compose($view, [
            'content' =>$data
        ]);

        $mailer->setTo($to);
        $mailer->setFrom($from);
        $mailer->setSubject($subject);
        return $mailer->send();
    }
}

