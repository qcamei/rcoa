<?php

use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\shoot\ShootBookdetail */

$this->title = Yii::t('rcoa', 'Update {modelClass}: ', [
    'modelClass' => 'Shoot Bookdetail',
]) . ' ' . $model->id;
?>
<div class="shoot-bookdetail-update">
    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'colleges' => $colleges,
        'projects' => $projects,
        'courses' => $courses,
    ]) ?>
</div>
<!-- 添加 controlbar 等同标题样式，位置 固定到页面底部 -->
<div class="controlbar">
    <div class="container">
        <?= Html::a(
                !$model->getIsValid() ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit','class' => (!$model->getIsValid()) ? 'btn btn-success' : 'btn btn-primary', 'data-toggle'=>'modal', 'data-target'=>'#myModal']) ?>
        <?= Html::a(Yii::t('rcoa', 'Back'),['exit-create',   'date' => date('Y-m-d', $model->book_time), 'b_id' => $model->id], ['class' => 'btn btn-default']) ?>
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
        $("#myModal .modal-footer #save").click(function(){
            var ed = $("#myModal .modal-body input").val();
            $('#bookdetail-create-form input[name="editreason"]').val(ed);
            $('#bookdetail-create-form').submit();
        });
        return false;
    });
    
JS;
    /**
     * 注册 $js 到 ready 函数，以页面加载完成才执行 js
     */
$this->registerJs($js,  View::POS_READY);
?>