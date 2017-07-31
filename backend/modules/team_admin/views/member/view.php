<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\team\TeamMember */

$this->title = $model->team_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Team Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-member-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'team_id' => $model->team_id, 'u_id' => $model->u_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'team_id' => $model->team_id, 'u_id' => $model->u_id], [
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
            'team_id',
            'u_id',
        ],
    ]) ?>

</div>
