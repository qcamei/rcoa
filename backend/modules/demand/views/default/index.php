
<?php

use common\models\demand\DemandTaskAuditor;
use common\models\demand\searchs\DemandTaskAuditorSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandTaskAuditorSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Task Auditors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-task-auditor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/demand', 'Create Demand Task Auditor'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'team_id',
                'value' => function($model){
                    /* @var $model DemandTaskAuditor*/
                    return $model->team->name;
                }
            ],
            [
                'attribute' => 'u_id',
                'value' => function($model){
                    /* @var $model DemandTaskAuditor*/
                    return $model->taskUser->nickname;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
