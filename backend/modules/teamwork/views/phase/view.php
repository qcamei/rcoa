<?php

use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;



/* @var $this View */
/* @var $model Phase */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/teamwork', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'template_type_id',
                'value' => $model->templateType->name,
            ],
            //'template_type_id',
            'name',
            'weights',
            //'progress',
            /*[
                'attribute' => 'create_by',
                'value' => $model->createBy->nickname,
            ],*/
        ],
    ]) ?>
    
    <h3>环节</h3>
    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Create Link'), ['/teamwork/link/create','phase_id' => $model->id],['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->links,
        ]),
        'columns' =>[
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'ID',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->id;
                }   
            ],
            [
                'label'=>'阶段ID',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->phase->name;
                }   
            ],
            [
                'label'=>'名称',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->name;
                }   
            ],
            [
                'label'=>'类型',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->types[$model->type];
                }   
            ],
            [
                'label'=>'总数',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->total;
                }   
            ],
            [
                'label'=>'已完成数',
                'value'=>function($model){
                    /* @var $model Link */
                    return $model->completed;
                }   
            ],
            [
                'label'=>'单位',
                'value'=>function($model){
                    /* @var $model Link */
                    return empty($model->unit) ? '无' : $model->unit;
                }   
            ],
            /*[
                'label'=>'进度',
                'value'=>function($model){
                    /* @var $model Link 
                    return $model->progress;
                }   
            ],*/
            /*[
                'label'=>'创建者',
                'value'=>function($model){
                    /* @var $model Link 
                    return $model->createBy->nickname;
                }   
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['/teamwork/link/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                                'method' => 'post'
                            ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['/teamwork/link/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ]
    ]) ?>

</div>
