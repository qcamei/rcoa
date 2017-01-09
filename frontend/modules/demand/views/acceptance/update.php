<?php

use common\models\demand\DemandAcceptance;
use frontend\modules\demand\assets\DemandAssets;
use yii\web\View;

/* @var $this View */
/* @var $model DemandAcceptance */

$this->title = Yii::t('rcoa/demand', 'Update Demand Acceptance').':' .$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Acceptances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/demand', 'Update');
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
            <button type="button" class="btn btn-primary" id="update-acceptance-save">确认</button>
        </div>
   </div>
</div>  
        
<script type="text/javascript">
    /** 编辑审核操作 */
    $('#update-acceptance-save').click(function()
    {
        $('#demand-acceptance-form').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>