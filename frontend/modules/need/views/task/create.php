<?php

use common\models\need\NeedTask;
use yii\web\View;


/* @var $this View */
/* @var $model NeedTask */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container need-task-create has-title">

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
    'params' => ['index'],
]) ?>