<?php

namespace common\components;

use Yii;
use yii\mail\MailerInterface;

/**
 * Message implements a message class based on Mailgun.
 */
class CustomMailMessage extends \boundstate\mailgun\Message
{
    
    protected $customMailer;
    
    public function send(MailerInterface $mailer = null)
    {
        
        return parent::send($mailer);
        
        if ($mailer === null && $this->mailer === null) {
            $this->customMailer = Yii::$app->getMailer();
        } elseif ($mailer === null) {
            $this->customMailer = $this->mailer;
        }
        else
        {
            $this->customMailer = $mailer;
        }
        
        return Yii::$app->rabbitmq->basicPublish('email-worker', [
            'message' => $this
        ]);
    }
    
    public function sendCustom()
    {
        return $this->customMailer->send($this);
    }
}
