<?php

use common\models\demand\DemandAppeal;
use frontend\modules\demand\assets\DemandAssets;
use yii\web\View;


/* @var $this View */
/* @var $model DemandAppeal */

$this->title = Yii::t('rcoa/demand', 'Create Demand Appeal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Appeals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
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
            <button type="button" class="btn btn-primary" id="create-appeal-save">确认</button>
        </div>
   </div>
</div>         

<script type="text/javascript">
    
    $('#create-appeal-save').click(function()
    {
        $('#demand-appeal-form').submit();
    });

</script>

<?php
    DemandAssets::register($this);
?>
