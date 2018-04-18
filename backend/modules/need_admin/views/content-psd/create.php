<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\need\NeedContentPsd */

$this->title = Yii::t('app', '{Create}-{Content}{Template}', [
    'Create' => Yii::t('app', 'Create'),
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Content}{Template}',[
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="need-content-psd-create">

    <?= $this->render('_form', [
        'model' => $model,
        'workitemType' => $workitemType,
        'workitem' => $workitem,
    ]) ?>

</div>
