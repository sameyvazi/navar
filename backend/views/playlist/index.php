<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\FancyboxAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\admin\AdminSearch */

FancyboxAsset::register($this);

$this->title = Yii::t('app', 'Playlists Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-11">
        <div class="row">

            <?php if (Yii::$app->getUser()->can('PlaylistAdd')): ?>
                <div class="col-md-2">
                    <a class="btn btn-primary fancybox fancybox.ajax" href="<?= Url::to(['/playlist/add']); ?>"><i class="fa fa-admin-plus"></i> <?= Yii::t('app', 'Add new playlist'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-1">
        <?= backend\widgets\PageSize::widget([
            'label' => '',
            'defaultPageSize' => 10,
        ]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="message_container"></div>
        <div class="table table-responsive">
            <?php
            Pjax::begin([
                'id' => 'playlistList-pjax',
                'enablePushState' => true,
                'timeout' => '20000',
                'scrollTo' => 0
            ]);
            ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => 'select[name="per-page"]',
                'options' => [
                    'id' => $searchModel->formName(),
                ],
                'columns' => [
                    ['class' => \yii\grid\SerialColumn::class],
                    [
                        'attribute' => 'id',
                        'filterOptions' => [
                            'dir' => 'ltr',
                        ],
                    ],
                    'name',
                    'name_fa',
                    'mood_id',
                    'no',
                    'limit',
                    [
                        'attribute' => 'created_at',
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'dir' => 'ltr',
                            'placeholder' => Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d_Y-m-d')
                        ],
                        'value' => function ($data) {
                            return Yii::$app->formatter->asDatetime($data->created_at, 'php:l j F Y H:i (Y/m/d)');
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status, Url::to(['/playlist/status', 'id' => $data->id]), 'PlaylistDisable', 'playlistList-pjax', 'message_container');
                        }
                    ],
                    [
                        'attribute' => 'status_fa',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_fa, Url::to(['/playlist/status-fa', 'id' => $data->id]), 'PlaylistDisable', 'playlistList-pjax', 'message_container');
                        }
                    ],
                    [
                        'attribute' => 'status_app',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_app, Url::to(['/playlist/status-app', 'id' => $data->id]), 'PlaylistDisable', 'playlistList-pjax', 'message_container');
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{update}',
                        'visible' => Yii::$app->getUser()->can('PlaylistUpdate'),
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, '', true);
                            }
                        ],
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{delete}',
                        'visible' => Yii::$app->getUser()->can('PlaylistDelete'),
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Yii::$app->helper->createDeleteButton($url, '', 'playlistList-pjax', 'message_container');
                            }
                        ],
                    ],
                    [
                        //'attribute' => 'view',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $url = Url::to(array('playlist-music/index','PlaylistMusicSearch[playlist_id]'=>$model->id));
                            return '<a href="'.$url.'">detail</a>';
                        },
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>