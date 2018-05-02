<?php

use wskeee\framework\models\Project;
use yii\web\View;

/* @var $this View */
/* @var $model Project */

$this->title = Yii::t('app', 'Update'). '：' . $model->name;
$this->params['breadcrumbs'][] = Yii::t('app', 'Update').'：'.$model->name;

?>
<div class="main project-update">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges'=>$colleges,
    ]) ?>

</div>
