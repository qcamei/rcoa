<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\team\TeamCategoryMap */

$this->title = $model->category_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Team Category Maps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-map-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/team', 'Update'), ['update', 'category_id' => $model->category_id, 'team_id' => $model->team_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/team', 'Delete'), ['delete', 'category_id' => $model->category_id, 'team_id' => $model->team_id], [
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
            'category_id',
            'team_id',
            'index',
            'is_delete',
        ],
    ]) ?>

</div>
