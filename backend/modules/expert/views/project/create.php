<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\expert\ExpertProject */

?>
<div class="expert-project-create">

    <h1><?= $model->expert->user->nickname ."：创建合作项目"?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'projects' => $projects,
    ]) ?>

</div>
