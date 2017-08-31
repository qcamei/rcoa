<?php

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Team */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => $model->teamType->name,
            ],
            'des',
            'index'
        ],
    ]) ?>
    
    <h3>团队成员</h3>
    <p>
        <?= Html::a(Yii::t('rcoa/team', 'Create Team Member'), ['/teammanage_admin/member/create','team_id'=>$model->id],['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->teamMembers,
        ]),
        'columns' =>[
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'团员名称',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return !empty($model->u_id) && !empty($model->user)? $model->user->nickname : null;
                }   
            ],
            [
                'label'=>'团员职称',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return !empty($model->position_id) ? $model->position->name : null;
                }   
            ],
            [
                'label'=>'队长 / 队员',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return TeamMember::$is_leaders[$model->is_leader];
                }   
            ],
            [
                'label'=>'职位等级',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return !empty($model->position_id) ? $model->position->level : null;
                }   
            ],
            [
                'label'=>'顺序',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return $model->index;
                }   
            ],
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
                            ['/teammanage_admin/member/update', 'id' => $model->id], $options);
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
                            ['/teammanage_admin/member/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ]
    ]) ?>
    
</div>
