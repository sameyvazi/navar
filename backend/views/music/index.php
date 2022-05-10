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

$this->title = Yii::t('app', 'Musics Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-11">
        <div class="row">

            <?php if (Yii::$app->getUser()->can('MusicAdd')): ?>
                <div class="col-md-2">
                    <a class="btn btn-primary fancybox fancybox.ajax" href="<?= Url::to(['/music/add']); ?>"><i class="fa fa-admin-plus"></i> <?= Yii::t('app', 'Add new music'); ?></a>
                </div>
            <?php endif; ?>
<!---->
<!--            --><?php //if (Yii::$app->getUser()->can('AdminUserDisable')): ?>
<!--                <div class="col-md-2">-->
<!--                    --><?//= Html::dropDownList('status_bulk', '', ['' => ''] + $searchModel->getStatusList(), [
//                        'class' => "form-control",
//                        'onChange' => "if(this.value === '') return false; Navar.massGridAction('{$searchModel->formName()}', '".Url::to(['/admin/bulk-status'])."', '".Yii::t('app', 'Are you sure you want to change selected item status?')."', 'adminList-pjax', 'message_container', this.value);this.selectedIndex = 0"
//                    ]); ?>
<!--                </div>-->
<!--            --><?php //endif; ?>

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
                'id' => 'musicList-pjax',
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
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{alert}',
                        'visible' => Yii::$app->getUser()->can('MusicUpdate'),
                        'buttons' => [
                            'alert' => function ($url, $model, $key) {
                                return Yii::$app->helper->createAlertButton($url, '', true);
                            }
                        ],
                    ],
                    [
                        'attribute' => 'id',
                        'filterOptions' => [
                            'dir' => 'ltr',
                        ],
                    ],
                    'name',
                    'name_fa',
                    [
                        'attribute' => 'genre_id',
                        'filter' => $searchModel->getGenreList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->genre_id;
                        }
                    ],
                    [
                        'attribute' => 'type',
                        'filter' => $searchModel->getTypeList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getTypeText($data->type);
                        }
                    ],
                    [
                        'attribute' => 'artist_id',
                        'value'     => 'artist.name',
                    ],
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
                            return $data->getStatusButton($data->status, Url::to(['/music/status', 'id' => $data->id]), 'MusicDisable', 'musicList-pjax', 'message_container');
                        }
                    ],
                    [
                        'attribute' => 'status_fa',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_fa, Url::to(['/music/status-fa', 'id' => $data->id]), 'MusicDisable', 'musicList-pjax', 'message_container');
                        }
                    ],
                    [
                        'attribute' => 'status_app',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_app, Url::to(['/music/status-app', 'id' => $data->id]), 'MusicDisable', 'musicList-pjax', 'message_container');
                        }
                    ],
                    [
                        'attribute' => 'status_site',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_site, Url::to(['/music/status-site', 'id' => $data->id]), 'MusicDisable', 'musicList-pjax', 'message_container');
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{update}',
                        'visible' => Yii::$app->getUser()->can('MusicUpdate'),
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, '', true);
                            }
                        ],
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view}',
                        'visible' => Yii::$app->getUser()->can('MusicUpdate'),
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Yii::$app->helper->createViewButton($url, '', true);
                            }
                        ],
                    ],
                    [
                        'attribute' => 'status_zizz',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getStatusButton($data->status_zizz, Url::to(['/music/status-zizz', 'id' => $data->id]), 'MusicDisable', 'musicList-pjax', 'message_container');
                        }
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>