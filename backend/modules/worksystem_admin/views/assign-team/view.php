<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAssignTeam */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Assign Teams').'：'.$model->user->nickname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Assign Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-assign-team-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/worksystem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'team_id',
                'value' => !empty($model->team_id) ? $model->team->name : null,
            ],
            [
                'attribute' => 'user_id',
                'value' => !empty($model->user_id) ? $model->user->nickname : null,
            ],
            'des:ntext',
            'index',
            [
                'attribute' => 'is_delete',
                'value' =>  $model->is_delete == 0 ? '否' : '是',
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
