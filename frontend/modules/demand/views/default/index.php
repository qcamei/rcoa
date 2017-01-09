<?php

use common\models\teamwork\CourseManage;
use frontend\modules\demand\assets\DefaultAssets;
use yii\helpers\Html;
use yii\web\View;

    /* @var $this View */
?>

<div class="demand-default-index">
    <div class="head">
        <div class="head-top"></div>
        <div class="head-bottom">
            <div class="container">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 lession-time"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/completed.png'], ['class' => 'total-icon']).
                                '<span class="completed-label">已完成</span>'.'</span>'.
                                '<span class="completed total-xs">'.number_format(2222).'</span>'.
                                '<span>学时</span><p class="hidden-xs">'.
                                '<span class="completed-label">已完成</span></p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_CARRY_OUT
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 lession-time"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/completed.png'], ['class' => 'total-icon']).
                                '<span class="completed-label">已完成</span>'.'</span>'.
                                '<span class="completed total-xs">'.number_format(42424).'</span>'.
                                '<span>门</span><p class="hidden-xs">'.
                                '<span class="completed-label">已完成</span></p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_CARRY_OUT
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 course-door"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/undone.png'], ['class' => 'total-icon']).
                                '<span class="doing-label">在 建</span>'.'</span>'.
                                '<span class="undone total-xs">'.number_format(4242).'</span>'.
                                '<span>学时</span><p class="hidden-xs">'.
                                '<span class="doing-label">在 建</span></p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_NORMAL
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 course-door" style="border-right: none"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/undone.png'], ['class' => 'total-icon']).
                                '<span class="doing-label">在 建</span>'.'</span>'.
                                '<span class="undone total-xs">'.number_format(42424).'</span>'.
                                '<span>门</span><p class="hidden-xs">'.
                                '<span class="doing-label">在 建</span></p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_NORMAL
                                ]) ?>
                </div>
                </div>
        </div>
    </div>
</div>
<?php 
    $js = <<<JS
JS;
    $this->registerJs($js);
    DefaultAssets::register($this);
?>
