<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\FancyboxAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\like\LikeSearch */

FancyboxAsset::register($this);

$this->title = Yii::t('app', 'Likes Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-11">
        <div class="row">

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
                'id' => 'commentList-pjax',
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
                    [
                        'attribute' => 'id',
                        'filterOptions' => [
                            'dir' => 'ltr',
                        ],
                    ],
                    'post_id',
                    'type',
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
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>