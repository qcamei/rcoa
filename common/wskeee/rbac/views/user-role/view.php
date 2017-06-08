<?php

use common\models\User;
use wskeee\rbac\models\AuthItem;
use wskeee\rbac\RbacAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model User */
/* @var $roleItems AuthItem */

$this->title = '用户角色详情：'.$model->nickname;
$this->params['breadcrumbs'][] = ['label' => '用户角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-role-view rbac">

    <div class="rbac-frame" style="height: 800px">
        
        <div class="rbac-number">
            已分配角色（<?= count($roles)?>个）
        </div>
        
        <?php $form = ActiveForm::begin([
            'id' => 'user-role-delete-form',
            'action' => '/rbac/user-role/delete?user_id='.$model->id
        ]); ?>
        
        <div class="rbac-delete-form" style="height: 700px">
            
            <?php foreach($roleCategorys as $roleCategory): ?>
            <p><b><?= $roleCategory['name'] ?></b></p>
                <?php foreach($roles as $roleItems): ?>
                    <?php if($roleItems->system_id == $roleCategory['id']): ?>
                    <p style="padding-left: 20px;">
                        <?= Html::checkbox('item_name[]', '', ['value' => $roleItems->name]) ?><?= $roleItems->description ?>
                        <span class="prompt">（角色）</span>
                    </p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
                    
        </div>
        
        <?php ActiveForm::end(); ?>
        
        <div class="rbac-btn">
            <?= Html::a('全选', 'javascript:;', ['id' => 'user-role-selectAll', 'style' => 'float: left; margin-right: 15px;']); ?>
            <?= Html::a('全不选', 'javascript:;', ['id' => 'user-role-unSelect', 'style' => 'float: left;']); ?>
            <?= Html::a('余除已选择角色', 'javascript:;', ['id' => 'user-role-delete', 'class' => 'btn btn-danger', 'data' => ['method' => 'post']]); ?>
            <?= Html::a('添加角色', ['create', 'user_id' => $model->id], ['id' => 'user-role-create', 'class' => 'btn btn-success']); ?>
        </div>
        
    </div>
   
</div>

<div class="rbac-model">
    <?= $this->render('_form_model')?>    
</div>

<?php
$js = 
<<<JS
        
    /** 删除操作 提交表单 */
    $('#user-role-delete').click(function()
    {       
        $('#user-role-delete-form').submit();
    });   
    /** 添加操作 提交表单 */
    $('#user-role-create').click(function()
    {       
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //全选
    $("#user-role-selectAll").click(function(){
        $("input[name='item_name[]']:checkbox").each(function(){
            $(this).prop("checked",true);
        });
    });
    //全不选
    $("#user-role-unSelect").click(function(){
        $("input[name='item_name[]']:checkbox").each(function(){
            $(this).prop("checked",false);
        });
    });
        
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    RbacAsset::register($this);
?>
