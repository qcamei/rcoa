<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCabinet */

$this->title = Yii::t('app', 'Update'). '：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->workitem->name, 'url' => ["/workitem_admin/default/view?id=$model->workitem_id"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cabinet-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
