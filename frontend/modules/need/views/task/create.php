<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\need\NeedTask */

$this->title = Yii::t('app', 'Create Need Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container need-task-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?= $this->render('_btngroup', [
    'model' => $model
]) ?>