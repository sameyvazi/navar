<?php

use backend\assets\FancyboxAsset;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\activity\ActivitySearch */
FancyboxAsset::register($this);
$this->title = Yii::t('app', 'User Activity Manager');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-11">

    </div>
    <div class="col-md-1">
        <?= backend\widgets\PageSize::widget([
            'label' => '',
        ]); ?>
    </div>
</div>
<div id="message_contianer"></div>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
            <?php
            Pjax::begin([
                'id' => 'activityList-pjax',
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
                    'user_id',
                    'ip',
                    'target_id',
                    [
                        'attribute' => 'created_at',
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'dir' => 'ltr',
                            'placeholder' => Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d_Y-m-d')
                        ],
                        'value' => function ($data) {
                            return Yii::$app->formatter->asDatetime(Yii::$app->dateTimeAction->getMongoDateInteger($data->created_at), 'php:l j F Y H:i (Y/m/d)');
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
                ]
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>