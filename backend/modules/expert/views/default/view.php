<?php

use common\models\expert\Expert;
use common\models\expert\ExpertProject;
use common\models\User;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Expert */

$this->title = $model->u_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'personal_image',
                'format'=>'raw',
                'value'=> Html::a(Html::img(WEB_ROOT.$model->personal_image,['width'=>'128px']), WEB_ROOT.$model->personal_image),
            ],
            'u_id',
            'user.username',
            'user.nickname',
            [
                'attribute' => 'user.sex',
                'value'=> User::$sexName[$model->user->sex],
            ],
            'type',
            'birth',
            'user.phone',
            'job_title',
            'job_name',
            'level',
            'employer',
            'attainment:ntext',
        ],
    ]) ?>
    <h5>合作项目</h5>
    <p>
        <?= Html::a('添加项目', ['/expert/project/create','expert_id'=>$model->u_id],['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->expertProjects,
        ]),
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
            'project.name:text:项目名称',
            [
                'label'=>'合作时间',
                'value'=>function($model){
                    /* @var $model ExpertProject */
                    $start_time =$model->start_time;
                    $end_time = $model->end_time ? $model->end_time : "至今";
                    return "$start_time - $end_time";
                }   
            ],
            [
                'label' => '费用报酬',
                'value' => function($model){
                    /* @var $model ExpertProject */
                    return Yii::$app->formatter->asCurrency($model->cost);
                }
            ],
            [
                'label' => '合作融洽度',
                'value' => function($model){
                    /* @var $model ExpertProject */
                    return ExpertProject::$compatibilityMap[$model->compatibility];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['/expert/project/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'data' => [
                                        'method' => 'post'
                                        ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['/expert/project/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ]
    ]) ?>
</div>
