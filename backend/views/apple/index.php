<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use \common\models\Apple;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List</h3>
                <div class="box-tools">
                    <?php
                    Modal::begin([
                        'header' => '<h2>Apple generator</h2>',
                        'toggleButton' => ['label' => 'Show apple generator', 'class' => 'btn btn-primary btn-sm'],
                        'footer' => Html::submitButton('Generate', ['onclick' => '$("#apple-generator-form").submit()', 'class' => 'btn btn-primary']),
                    ]);

                    $form = ActiveForm::begin(['action' => ['apple/generate'], 'id' => 'apple-generator-form']);
                    echo $form->field($appleGeneratorForm, 'quantity')->textInput();
                    ActiveForm::end();

                    Modal::end();
                    ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline" role="grid">
                    <div class="row">
                        <div class="col-xs-6"></div>
                        <div class="col-xs-6"></div>
                    </div>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                    'class' => 'yii\grid\SerialColumn'
                            ],

                            [
                                    'attribute' => 'color',
                                    'value' => function ($data) {
                                        return '<div class="box_color" style="background-color: ' . Html::encode($data->color) . '"></div> ' . Html::encode($data->color);
                                    },
                                    'format' => 'raw'
                            ],

                            [
                                    'attribute' => 'created_at',
                                    'value' => function ($data) {
                                        return date('Y-m-d H:i:s', $data->created_at);
                                    },
                            ],

                            [
                                'attribute' => 'falled_at',
                                'value' => function ($data) {
                                    return $data->falled_at ? date('Y-m-d H:i:s', $data->falled_at) : null;
                                }
                            ],

                            [
                                    'attribute' => 'status',
                                    'value' => function ($data) {return Apple::$statuses[$data->status];},
                                    'filter' => Apple::$statuses
                            ],

                            [
                                    'attribute' => 'size',
                            ],

                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{fall-to-ground}  {delete} {eat}',
                                'buttons' => [
                                    'fall-to-ground' => function ($url, $model) {
                                        if ($model->status == Apple::STATUS_ON_TREE) {
                                            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url,
                                                ['title' => 'Fall to ground', 'data-pjax' => '0', 'class' => 'btn btn-default btn-sm']);
                                        }
                                    },
                                    'delete' => function ($url, $model) {
                                        if ($model->size == 0) {
                                            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                                                ['title' => 'Delete', 'data-pjax' => '0', 'class' => 'btn btn-default btn-sm']);
                                        }
                                    },
                                    'eat' => function ($url, $model, $key) {
                                        if ($model->status == Apple::STATUS_FALLED_TO_GROUND && $model->size > 0) {
                                            $iconName = "cutlery";
                                            $id = 'eat-' . $key;
                                            $options = [
                                                'title' => 'Eat',
                                                'aria-label' => 'Eat',
                                                'data-pjax' => '0',
                                                'id' => $id
                                            ];
                                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                                            $js = <<<JS
                                        $("#{$id}").on("click",function(event){  
                                                $("#{$id}").attr('href', $("#{$id}").attr('href') + "&percent=" + parseInt(window.prompt("Please enter a number from 1 to 100", ""), 10));
                                            }
                                        );
JS;
                                            $this->registerJs($js, \yii\web\View::POS_READY, $id);
                                            return Html::a($icon, $url, $options);
                                        }

                                    }
                                ],
                            ],

                        ],
                        'layout' => "{items}
                    <div class='dt-row dt-bottom-row'>
                        <div class='row'>
                            <div class='col-sm-6'>
                                {summary}
                            </div>
                            <div class='col-sm-6 text-right'>
                                {pager}
                            </div>
                        </div></div>"
                    ]);
                    ?>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- /.col -->
    </div>

</div>