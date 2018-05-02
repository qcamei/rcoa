<?php

use common\models\need\NeedTask;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model NeedTask */

$this->title = Yii::t('app', 'Update') . '：' . $model->task_name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Tasks'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="container need-task-update has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'allBusiness' => $allBusiness,
        'allLayer' => $allLayer,
        'allProfession' => $allProfession,
        'allCourse' => $allCourse,
        'allAuditBy' => $allAuditBy,
        'attFiles' => $attFiles,
    ]) ?>

</div>

<?= $this->render('_btngroup', [
    'model' => $model,
    'params' => ['view', 'id' => $model->id]
]) ?>