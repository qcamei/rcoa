<?php

use common\models\need\NeedContentPsd;
use yii\web\View;

/* @var $this View */
/* @var $model NeedContentPsd */

$this->title = Yii::t('app', '{Update}-{Content}{Template}: {nameAttribute}', [
    'Update' => Yii::t('app', 'Update'),
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
    'nameAttribute' => $model->workitem->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Content}{Template}',[
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workitem->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="need-content-psd-update">

    <?= $this->render('_form', [
        'model' => $model,
        'workitemType' => $workitemType,
        'workitem' => $workitem,
    ]) ?>

</div>
