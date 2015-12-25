<?php

use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\shoot\ShootBookdetail */

$this->title = Yii::t('rcoa', 'Update {modelClass}: ', [
    'modelClass' => 'Shoot Bookdetail',
]) . ' ' . $model->id;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
          <?php echo '预约操作：【'.$model->site->name.'】'.
                date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName() ?>
    </div>
</div>
<div class="container shoot-bookdetail-update has-title">
    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'colleges' => $colleges,
        'projects' => $projects,
        'courses' => $courses,
    ]) ?>
    <?= $this->render('_form_model',[
         'model' => $model,
        ])?>
</div>
<!-- 添加 controlbar 等同标题样式，位置 固定到页面底部 -->
<div class="controlbar">
    <div class="container">
        <?= Html::a(
                !$model->getIsValid() ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit','class' => (!$model->getIsValid()) ? 'btn btn-success' : 'btn btn-primary', 'data-toggle'=>'modal', 'data-target'=>'#myModal']) ?>
        <?= Html::a(Yii::t('rcoa', 'Back'),['exit-create',   'date' => date('Y-m-d', $model->book_time), 'b_id' => $model->id, 'site'=> $model->site_id], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php
    /**
     * 通过外部提交按钮控制 form 提交
     */
    $js = 
 <<<JS
    $('#submit').click(function()
    {
        $('#myModal').modal()
         /**close关闭模态款的*/
        $("#myModal .modal-header .close").click(function(){
            window.location.reload();
        });
        /**关闭模态框后重新加载页面*/
        $("#myModal .modal-footer #close").click(function(){
            window.location.reload();
        });
        $("#myModal .modal-footer #save").click(function(){
            var ed = $("#myModal .modal-body input").val();
            if(ed != ''){
                $('#bookdetail-create-form input[name="editreason"]').val(ed);
                $('#bookdetail-create-form').submit();
            }else{
                $('#myModal .modal-body').html('<b style="font-size:18px;">编辑原因不能为空</ b>');     //设置内容
                $("#myModal .modal-footer #save").remove();   //移除确定
            }
        });
        return false;
    });
    
JS;
    /**
     * 注册 $js 到 ready 函数，以页面加载完成才执行 js
     */
$this->registerJs($js,  View::POS_READY);
?>