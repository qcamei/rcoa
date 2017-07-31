<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\teamwork\searchs\TemplateTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Template Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Create Template Type'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Phases'), ['phase/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            //'create_by',
            //'created_at',
            //'updated_at',
            // 'des',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
