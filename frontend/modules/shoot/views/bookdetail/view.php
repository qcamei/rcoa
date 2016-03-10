<?php

use common\models\shoot\ShootBookdetail;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $model ShootBookdetail */

$this->title = Yii::t('rcoa', 'Shoot Bookdetail Details') . ' : ' . $model->id;
?>

<div class="title">
    <div class="container">
        <?php echo '预约操作：【'.$model->site->name.'】'.
                date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName() ?>
    </div>
</div>

<div class="container has-title shoot-bookdetail-view">
    <?= $this->render('_form_detail2', [
        'model' => $model,
        'shootmans' => $shootmans,
        'reloadShootMans' => $reloadShootMans,
        'assignedShootMans' => $assignedShootMans,
        'shootMansKey' => $shootMansKey,
        'reloadContacts' => $reloadContacts,
    ]) ?>
    <?= $this->render('_form_model',[
         'model' => $model,
        ])?>
</div>
<div class="controlbar">
    <div class="container">
        <?php
            /**
             * 提交 按钮显示必须满足以下条件：
             * 1、拥有【指派】权限
             * 2、在拍摄任务完成前，即评价发生前
             */
            if(Yii::$app->user->can(RbacName::PERMSSIONT_SHOOT_ASSIGN) && $model->canAssign())
                echo Html::a('提交', 'javascript:;', ['id'=>'submit', 'class' => 'btn btn-danger']).' ';
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、拥有【编辑】权限(管理员或者任务的发起者)
             * 2、在摄影师指派前
             */
            if($model->canEdit() && Yii::$app->user->can(RbacName::PERMSSIONT_SHOOT_UPDATE,['job'=>$model]) && $model->u_booker)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            /**
             * 评价 按钮显示必须满足以下条件：
             * 1、拥有【评价】权限(编导和摄影师都有权限)
             * 2、在摄影师指派后，即摄影结束后
             * 3、查看【评价】权限为所有人
             * 4、不是为【已失约】状态
             * 5、预约时间要大于当前时间
             */
            if($model->canAppraise() && !$model->getIsStatusBreakPromise() && $model->book_time < time())
                echo Html::a('评价', ['/shoot/appraise/create', 'b_id' => $model->id], ['class' => 'btn btn-danger']).' ';
            /**
             * 取消 按钮显示必须满足以下条件：
             * 1、拥有【取消】权限（编导自己和管理员）
             * 2、必须是在24小时之前
             * 3、不是为【已取消】【已完成】状态
             */
            if(Yii::$app->user->can(RbacName::PERMSSIONT_SHOOT_CANCEL, ['job'=>$model]) && !$model->getIsStatusCancel() && !$model->getIsStatusCompleted())
                echo Html::a('取消', 'javascript::', ['id'=>'cancel', 'class' => 'btn btn-warning']).' ';
        ?>
        <?= Html::a('返回', ['index','date'=>  date('Y-m-d',$model->book_time), 'site'=>$model->site_id], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php
    $js = 
 <<<JS
    /**close关闭模态款的*/
    $("#myModal .modal-header .close").click(function(){
        window.location.reload();
    });
    /**关闭模态框后重新加载页面*/
    $("#myModal .modal-footer #close").click(function(){
        window.location.reload();
    });
    /** 指派操作*/
    var uShootMan = "$model->u_shoot_man";
    $('#submit').click(function()
        {
            if(uShootMan > 0){
                $('#myModal').modal()
                $("#myModal .modal-footer #save").click(function(){
                    var ed = $("#myModal .modal-body input").val();
                    var se = $(".table #shootbookdetail-u_shoot_man option:selected").val();
                    if(uShootMan == se)
                    {
                        $('#myModal .modal-body').html('<b style="font-size:18px;">请重新选择摄影师</ b>'); //设置内容
                        $("#myModal .modal-footer #save").remove();   //移出确定
                    }
                    else if(ed != '')
                    {
                        $('#form-assign-shoot_man input[name="editreason"]').val(ed);
                        $('#form-assign-shoot_man').submit();
                    }
                    else
                    {
                        $('#myModal .modal-body').html('<b style="font-size:18px;">编辑原因不能为空</ b>');     //设置内容
                        $("#myModal .modal-footer #save").remove();   //移除确定
                    }
                });
                return false;
            }
            $('#form-assign-shoot_man').submit();
        });
    /** 取消操作 */        
    $('#cancel').click(function(){
        $('#myModal').modal()
        $('#myModal .modal-header h4').text("是否取消");    //更改模态标题
        $("#myModal .modal-body input").attr("placeholder","请输入取消原因...");
        $("#myModal .modal-footer #save").click(function(){
            var ed = $("#myModal .modal-body input").val();
            if(ed != ''){
                $('#form-assign-shoot_man input[name="editreason"]').val(ed);
                $('#form-assign-shoot_man').attr("action","cancel?id="+"$model->id").submit();
              
            }else{
                $('#myModal .modal-body').html('<b style="font-size:18px;">取消原因不能为空</ b>');     //设置内容
                $("#myModal .modal-footer #save").remove();   //移除确定
            }
        });
        return false;
    });
            
    $(".select2-selection__rendered li").prev(".select2-selection__choice").eq(0).css({border:"1px solid blue"});
JS;
$this->registerJs($js,  View::POS_READY);
?>
<script type="text/javascript">
    function select2Log(){
        $(".select2-selection__rendered li").prev(".select2-selection__choice").eq(0).css({border:"1px solid blue"});
    } 
</script>
