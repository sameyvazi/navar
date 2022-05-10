<?php

namespace console\controllers\music;

use yii\base\Action;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class Alert extends Action {

    public function run($id) {


        $id = str_replace('+', ' ',$id);

        //navar
        $server_key = 'AAAAHSgskO0:APA91bE2e8ghm_Jig0KkzcVZTvrYxoiCHmTldIIJxm2Hx5Y-FiIsaBEFVreycXUhlaESnhPBKzWptm_cpLN5ViEQWsbT6wkvHmNVbadeWm8i9vP6o8wInjGyzZfwSoiiKmCYDZNLd14e';

        //tarfand
//        $server_key = 'AAAAvLnsS_Q:APA91bGoxr7nasAM-yWOR9Wx93OPXC_qT19SaSa9zFUOhm0AXqNqNUs8t1AR8G6CIBxKpzgTKyNwMKQLtQc8kRRq6XB2sKQkA4NujBiwxq5zbJC84ZssA4R0c347RlkvpsU4mb0VRf3C';

        $client = new Client();
        $client->setApiKey($server_key);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $message = new Message();
        $message->setPriority('high');
        $message->addRecipient(new Topic('Navar'));

        $not = new Notification('موزیک جدید', $id);
        $not->setSound('default');

        $message
            ->setNotification($not)
//            ->setData(['sound' => true])
        ;

        $response = $client->send($message);
        var_dump($response->getStatusCode());
        var_dump($response->getBody()->getContents());


    }

}
