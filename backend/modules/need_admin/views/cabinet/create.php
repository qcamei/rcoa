<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCabinet */

$this->title = Yii::t('rcoa/workitem', 'Create Workitem Cabinet');
$this->params['breadcrumbs'][] = ['label' => $model->workitem->name, 'url' => ["/workitem_admin/default/view?id=$model->workitem_id"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cabinet-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
