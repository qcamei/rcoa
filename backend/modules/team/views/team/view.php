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
                'confirm' => Yii::t('rcoa/team', 'Are you sure you want to delete this item?'),
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
        ],
    ]) ?>
    
    <h3>团队成员</h3>
    <p>
        <?= Html::a(Yii::t('rcoa/team', 'Create Team Member'), ['/teammanage/member/create','team_id'=>$model->id],['class' => 'btn btn-success']) ?>
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
                    return $model->u->nickname;
                }   
            ],
            [
                'label'=>'团员职称',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return $model->position;
                }   
            ],
            [
                'label'=>'队长 / 队员',
                'value'=>function($model){
                    /* @var $model TeamMember */
                    return $model->is_leaders[$model->is_leader];
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
                            ['/teammanage/member/update', 'team_id' => $model->team_id, 'u_id' => $model->u_id], $options);
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
                            ['/teammanage/member/delete', 'team_id' => $model->team_id, 'u_id' => $model->u_id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ]
    ]) ?>
    
</div>
