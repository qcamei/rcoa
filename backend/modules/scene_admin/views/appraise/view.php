<?php

use common\models\scene\SceneAppraise;
use common\models\scene\SceneBookUser;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model SceneAppraise */

$this->title = SceneBookUser::$roleName[$model->role];
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Shoot Appraises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shoot-appraise-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'role_name' => $model->role_name, 'q_id' => $model->q_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'role_name' => $model->role_name, 'q_id' => $model->q_id], [
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
            'role_name',
            'q_id',
            'index',
        ],
    ]) ?>

</div>
