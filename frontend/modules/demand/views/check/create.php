<?php

use common\models\demand\DemandCheck;
use frontend\modules\demand\assets\DemandAssets;
use yii\web\View;


/* @var $this View */
/* @var $model DemandCheck */

$this->title = Yii::t('rcoa/demand', 'Demand Checks').':'.$model->task->course->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Checks'), 'url' => ['index']];
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

<script type="text/javascript">
    
    $('#create-check-save').click(function()
    {
        $('#demand-check-form').submit();
    });

</script>

<?php
    DemandAssets::register($this);
?>
