<?php

use common\models\demand\DemandCheckReply;
use frontend\modules\demand\assets\DemandAssets;
use yii\web\View;


/* @var $this View */
/* @var $model DemandCheckReply */

$this->title = Yii::t('rcoa/demand', 'Create Demand Check Reply');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Check Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-check-reply-create">

    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">是否通过审核</h4>
        </div>
        <div class="modal-body">
            
            <?= $this->render('_form', [
                'model' => $model,
                'pass' => $pass,
            ]) ?>
            
        </div>
        <div class="modal-footer">
            <button id="submit-check-reply-save" class="btn btn-primary">确认</button>
        </div>
   </div>

</div>

 <script type="text/javascript">
    /** 审核回复操作 提交表单 */
    $("#submit-check-reply-save").click(function()
    {
        $('#demand-check-reply-form').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>