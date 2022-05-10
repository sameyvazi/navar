<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\FancyboxAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\log\LogSearch */

FancyboxAsset::register($this);

$this->title = Yii::t('app', 'Log Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-11">
        <?php if(Yii::$app->getUser()->can('LogDelete')): ?>
            <?= Html::button('<i class="fa fa-trash"></i> '.Yii::t('app', 'Delete'), [
                'class' => 'btn btn-danger',
                'onClick' => "return Navar.massGridAction('{$searchModel->formName()}', '".Url::to(['/log/bulk-delete'])."', '".Yii::t('app', 'Are you sure you want to delete selected items?')."', 'logList-pjax', 'message_contianer')",
            ]);?>
        <?php endif; ?>
    </div>
    <div class="col-md-1">
        <?= backend\widgets\PageSize::widget([
            'label' => '',
            'defaultPageSize' => 10,
        ]); ?>
    </div>
</div>
<div id="message_contianer"></div>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
            <?php
            Pjax::begin([
                'id' => 'logList-pjax',
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
                    ['class' => \yii\grid\CheckboxColumn::class],
                    ['class' => \yii\grid\SerialColumn::class],
                    'level',
                    'category',
                    [
                        'attribute' => 'prefix',
                        'format' => 'raw',
                        'value' => function ($data) {
                            preg_match_all("/\[([^\]]*)\]/", $data->prefix, $matches);
                            $text = Yii::t('app', 'IP').": {$matches[1][0]}<br>";
                            $text .= Yii::t('app', 'User').": {$matches[1][1]}<br>";
                            $text .= Yii::t('app', 'Session ID').": {$matches[1][2]}";
                            return $text;
                        }
                    ],
                    [
                        'attribute' => 'log_time',
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'dir' => 'ltr',
                            'placeholder' => Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d_Y-m-d')
                        ],
                        'value' => function ($data) {
                            return Yii::$app->formatter->asDatetime((int)$data->log_time, 'php:l j F Y H:i (Y/m/d)');
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Yii::$app->helper->createViewButton($url, '', true);
                            }
                        ],
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{delete}',
                        'visible' => Yii::$app->getUser()->can('LogDelete'),
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Yii::$app->helper->createDeleteButton($url, '', 'logList-pjax', 'message_contianer');
                            }
                        ],
                    ],
                ]
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>