<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\FancyboxAsset;
use common\models\music\Music;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\admin\AdminSearch */

FancyboxAsset::register($this);

$this->title = Yii::t('app', 'Musics Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
</div>

<div class="row">
    <div class="col-md-12">
        <div id="message_container"></div>
        <div class="table table-responsive">
            <?php
            Pjax::begin([
                'id' => 'musicList-pjax',
                'enablePushState' => true,
                'timeout' => '20000',
                'scrollTo' => 0
            ]);
            ?>
            <?= \yii\widgets\DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'artist_name',
                    'name',
                    'artist_name_fa',
                    'name_fa',
                    [
                        'attribute' => 'dl_link',
                        'format'=>'raw',
                        'value'=> function ($model) {

                            $storage = Yii::$app->params['storageServerUrl2'];
                            if ($model->type == Music::TYPE_MP3){

                                $albumKey = '';
                                if ($model->music_id != null && $music = Music::find()->where(['id' => $model->music_id])->one()){

                                    if($music->type == Music::TYPE_ALBUM && $music->id > 6700){
                                        $albumKey = $music->key_pure.'/';
                                    }
                                }
                                return $storage.$model->artist->key.'/mp3/'.$albumKey.$model->dl_link.' [320].mp3';
                            }elseif ($model->type == Music::TYPE_VIDEO){

                                return $storage.$model->artist->key.'/video/'.$model->dl_link. ' 1080p [iNavar.com].mp4';
                            }elseif ($model->type = Music::TYPE_ALBUM){

                                $albumKey = '';

                                if($model->type == Music::TYPE_ALBUM && $model->id > 6700){
                                    $albumKey = $model->key_pure.'/';
                                }

                                return $storage.$model->artist->key.'/mp3/'.$albumKey.$model->dl_link.' [320].zip';
                            }
                        },
                    ],
                    [
                        'attribute' => 'image',
                        'format'=>'raw',
                        'value'=> Yii::$app->params['storageServerUrl2'].$model['directory'].'/cover/cover-'.$model['image']
                    ]
                ],
            ]) ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>