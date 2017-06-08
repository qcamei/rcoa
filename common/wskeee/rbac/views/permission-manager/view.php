<?php

use wskeee\rbac\models\AuthItem;
use wskeee\rbac\RbacAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model AuthItem */

$this->title = '权限详情：'.$model->name;
$this->params['breadcrumbs'][] = ['label' => '所有权限', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$roleCategoryItem = ArrayHelper::map($roleCategorys, 'id', 'name');

?>
<div class="permission-manager-view rbac">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('更新', ['update', 'name' => $model->name], ['class' => 'btn btn-primary']) ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'system_id',
                'value' => isset($roleCategoryItem[$model->system_id]) ? $roleCategoryItem[$model->system_id] : null,
            ],
            'name',
            'description:ntext',
            'ruleName',
            'data:ntext'
        ],
    ]) ?>
    
    <div class="rbac-frame" style="width: 45%; float: left;">
        
        <div class="rbac-number">
            拥有该权限的角色（<?= count($byRoles) ?>个）
        </div>

        <div class="rbac-delete-form">
            <?php foreach($byRoleCategorys as $roleCategory): ?>
            <p><b><?= $roleCategory['name']; ?></b></p>

                <?php foreach($byRoles as $role): ?>
                    <?php if($role['system_id'] == $roleCategory['id']): ?>
                    <p style="padding-left: 20px;">
                        <?= $role['description'] ?>
                        <span class="prompt">（角色）</span>
                    </p>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php endforeach;?>
            
        </div>
        
    </div>    
    
    <div class="rbac-frame" style="width: 45%; float: right;">
        
        <div class="rbac-number">
            拥有该权限的用户（<?= count($byUsers) ?>个）
        </div>

        <div class="rbac-delete-form">
            <?php foreach($byRoles as $role): ?>
            <p><b><?= $role['description']; ?></b><span class="prompt">（角色）</span></p>

                <?php foreach($byUsers as $user): ?>
                    <?php if($user['item_name'] == $role['name']): ?>
                    <p style="padding-left: 20px;">
                        <?= $user['nickname'] ?>
                    </p>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php endforeach;?>
            
        </div>
        
    </div>    `
    
</div>

<div class="rbac-model">
    <?= $this->render('/user-role/_form_model')?>    
</div>

<?php
$js = 
<<<JS
        
    /** 角色管理删除操作 提交表单 */
    $('#role-manager-delete').click(function()
    {       
        $('#role-manager-delete-form').submit();
    });   
    /** 用户角色删除操作 提交表单 */
    $('#user-role-delete').click(function()
    {       
        $('#user-role-delete-form').submit();
    });   
    /** 角色管理添加操作 提交表单 */
    $('#role-manager-create').click(function()
    {       
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    /** 用户角色添加操作 提交表单 */
    $('#user-role-create').click(function()
    {       
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //角色管理操作 全选
    $("#role-manager-selectAll").click(function(){
        $("input[name='child[]']:checkbox").each(function(){
            $(this).prop("checked",true);
        });
    });
    //角色管理操作 全不选
    $("#role-manager-unSelect").click(function(){
        $("input[name='child[]']:checkbox").each(function(){
            $(this).prop("checked",false);
        });
    });
    //用户角色操作 全选
    $("#user-role-selectAll").click(function(){
        $("input[name='user_id[]']:checkbox").each(function(){
            $(this).prop("checked",true);
        });
    });
    //用户角色操作 全不选
    $("#user-role-unSelect").click(function(){
        $("input[name='user_id[]']:checkbox").each(function(){
            $(this).prop("checked",false);
        });
    });
        
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    RbacAsset::register($this);
?>