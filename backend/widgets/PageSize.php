<?php

namespace backend\widgets;

use yii\helpers\Html;
use Yii;

/**
 * PageSize widget is an addition to the \yii\grid\GridView that enables
 * changing the size of a page on GridView.
 *
 * To use this widget with a GridView, add this widget to the page:
 *
 * ~~~
 * <?php echo \backend\widgets\PageSize::widget(); ?>
 * ~~~
 *
 * and set the `filterSelector` property of GridView as shown in
 * following example.
 *
 * ~~~
 * <?= GridView::widget([
 *      'dataProvider' => $dataProvider,
 *      'filterModel' => $searchModel,
 * 		'filterSelector' => 'select[name="per-page"]',
 *      'columns' => [
 *          ...
 *      ],
 *  ]); ?>
 * ~~~
 *
 * Please note that `per-page` here is the string you use for `pageSizeParam` setting of the PageSize widget.
 *
 * @author S.Eyvazi <saman3yvazi@gmail.com>
 */
class PageSize extends \yii\base\Widget
{
    /**
     * @var string the label text.
     */
    public $label = 'Per Page';

    /**
     * @var integer the defualt page size. This page size will be used when the $_GET['per-page'] is empty.
     */
    public $defaultPageSize = 20;

    /**
     * @var string the name of the GET request parameter used to specify the size of the page.
     * This will be used as the input name of the dropdown list with page size options.
     */
    public $pageSizeParam = 'per-page';

    /**
     * @var array the list of page sizes
     */
    public $sizes = [2 => 2, 5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 50 => 50,
        100 => 100, 200 => 200];

    /**
     * @var string the template to be used for rendering the output.
     */
    public $template = '{label} {list}';

    /**
     * @var array the list of options for the drop down list.
     */
    public $options = ['class' => 'form-control'];

    /**
     * @var array the list of options for the label
     */
    public $labelOptions = ['class' => 'control-label'];

    /**
     * @var boolean whether to encode the label text.
     */
    public $encodeLabel = false;

    /**
     * Runs the widget and render the output
     */
    public function run()
    {
        $request = Yii::$app->request;

        if (empty($this->options['id'])) {
            $this->options['id'] = $this->id;
        }

        if ($this->encodeLabel) {
            $this->label = Html::encode($this->label);
        }

        $perPage = $request->get($this->pageSizeParam, false) ? $request->get($this->pageSizeParam)
                : $this->defaultPageSize;

        $listHtml = Html::dropDownList('per-page', $perPage, $this->sizes,
                $this->options);
        $labelHtml = '';
        if (!empty($this->label)) {
            $labelHtml = Html::label($this->label, $this->options['id'],
                    $this->labelOptions);
        }

        $output = str_replace(['{list}', '{label}'], [$listHtml, Yii::t('app', $labelHtml)],
            $this->template);
        
        
        $output = Html::beginTag('div', ['class' => 'form-horizontal form-inline form-group']) . $output . Html::endTag('div');

        return $output;
    }
}