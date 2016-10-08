<?php

use common\models\multimedia\MultimediaCheck;
use frontend\modules\multimedia\MultimediaAsset;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model MultimediaCheck */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?= $this->title ?></h4>
</div>
<div class="modal-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="create-check-save">确认</button>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#multimedia-check-form').submit();
    });
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>