<?php

use common\models\shoot\ShootBookdetail;
use wskeee\rbac\RbacManager;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model ShootBookdetail */

$this->title = Yii::t('rcoa', 'Create Shoot Bookdetail');
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
          <?php echo '预约操作：【'.$model->site->name.'】'.
                date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName() ?>
    </div>
</div>
<!-- 添加 has-title 样式可使位置显示于标题下而不被标题 覆盖-->
<div class="container shoot-bookdetail-create has-title">
    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'teacherName'=>$teacherName,
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
                ['id'=>'submit','class' => (!$model->getIsValid()) ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Back'), ['exit-create', 'date' => date('Y-m-d', $model->book_time), 'b_id' => $model->id, 'site'=> $model->site_id], ['class' => 'btn btn-default']) ?>
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
                $('#bookdetail-create-form').submit();
            });
    
JS;
    /**
     * 注册 $js 到 ready 函数，以页面加载完成才执行 js
     */
$this->registerJs($js,  View::POS_READY);
?>