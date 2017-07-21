<?php

use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model common\modules\rbac\models\AuthItem */

$this->title = '添加角色';
$this->params['breadcrumbs'][] = ['label' => 'Auth Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-create rbac">

    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            
            <?= $this->render('_form', [
                'roleCategorys' => $roleCategorys,
                'roles' => $roles,
            ]) ?>

        </div>
        <div class="modal-footer">
            <?= Html::a('全选', 'javascript:;', ['id' => 'user-role-create-selectAll', 'style' => 'float: left; margin-right: 15px;']); ?>
            <?= Html::a('全不选', 'javascript:;', ['id' => 'user-role-create-unSelect', 'style' => 'float: left;']); ?>
            <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">关闭</button>
            <button id="submit-create-save" class="btn btn-primary">确认</button>
        </div>
   </div>
</div> 

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $("#submit-create-save").click(function()
    {
        $('#user-role-create-form').submit();
    });
</script>

</div>

<?php
   
$js = 
<<<JS
        
    //全选
    $("#user-role-create-selectAll").click(function(){
        $("input[name='item_name[]']:checkbox").each(function(){
            $(this).prop("checked",true);
        });
    });
    //全不选
    $("#user-role-create-unSelect").click(function(){
        $("input[name='item_name[]']:checkbox").each(function(){
            $(this).prop("checked",false);
        });
    });
        
JS;
    $this->registerJs($js, View::POS_READY);
?>