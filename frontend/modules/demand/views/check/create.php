<?php

use common\models\demand\DemandCheck;
use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model DemandCheck */

$this->title = Yii::t('rcoa/demand', 'Create Demand Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-check-create">

    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">是否提交任务审核</h4>
        </div>
        <div class="modal-body">
            
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
        <div class="modal-footer">
            <?php if(empty($model->demandTask->budget_cost)): ?>
            <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">关闭</button>
            <?php else:?>
            <button id="submit-check-save" class="btn btn-primary">确认</button>
            <?php endif; ?>    
        </div>
   </div>
        
</div> 

<script type="text/javascript">
    /** 审核操作 提交表单 */
    $("#submit-check-save").click(function()
    {
        $('#demand-check-form').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>

