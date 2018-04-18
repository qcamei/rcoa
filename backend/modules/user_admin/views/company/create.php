<?php

use common\models\Company;
use yii\web\View;

/* @var $this View */
/* @var $model Company */

$this->title = Yii::t('app', '{Create}{Company}', [
            'Create' => Yii::t('app', 'Create'),
            'Company' => Yii::t('app', 'Company'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Company}{List}',[
    'Company' => Yii::t('app', 'Company'),
    'List' => Yii::t('app', 'List'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
