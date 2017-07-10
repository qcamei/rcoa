<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\web\View;

/* @var $this View */
// @var $model WorksystemContentinfo */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Contentinfo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>

<div class="worksystem-contentinfo-update">

    <?= $this->render('_form', [
        'model' => $model,
        'infos' => $infos,
    ]) ?>

</div>

<?php
$js =   
<<<JS
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>