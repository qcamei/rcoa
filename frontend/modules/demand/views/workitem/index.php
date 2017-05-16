<?php

use common\models\demand\DemandTask;
use common\models\demand\searchs\DemandWorkitemSearch;
use frontend\modules\demand\assets\DemandAssets;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $dt_model DemandTask */
/* @var $searchModel DemandWorkitemSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('rcoa/demand', 'Demand Workitems');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="demand-workitem-index">
    
    <?= $this->render('_form', [
        'dt_model' => $dt_model,
        'workitmType' => $workitmType,
        'workitem' => $workitem,
    ]) ?>
    
</div>

<?php
    DemandAssets::register($this);
?>