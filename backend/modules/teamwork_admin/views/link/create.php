<?php

use common\models\teamwork\Link;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model Link */

$this->title = Yii::t('rcoa/teamwork', 'Create Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phases'), 'url' => ['/teamwork/phase/view', 'id' => $model->phase_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
