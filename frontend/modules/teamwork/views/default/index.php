<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $twTool TeamworkTool */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row item-manage-row">
    <div class="head">
        <div class="head-top"></div>
        <div class="head-bottom">
            <div class="container">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 lession-time"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/completed.png'], ['class' => 'total-icon']).
                                Html::img(['/filedata/teamwork/image/view_completed.png ']).'</span>'.
                                '<span class="completed total-xs">'.number_format($completedHours).'</span>'.
                                '<span>学时</span><p class="hidden-xs">'.
                                Html::img(['/filedata/teamwork/image/view_completed.png ']).'</p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_CARRY_OUT
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 lession-time"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/completed.png'], ['class' => 'total-icon']).
                                Html::img(['/filedata/teamwork/image/view_completed.png ']).'</span>'.
                                '<span class="completed total-xs">'.number_format($completedDoor).'</span>'.
                                '<span>门</span><p class="hidden-xs">'.
                                Html::img(['/filedata/teamwork/image/view_completed.png ']).'</p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_CARRY_OUT
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 course-door"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/undone.png'], ['class' => 'total-icon']).
                                Html::img(['/filedata/teamwork/image/view_undone.png ']).'</span>'.
                                '<span class="undone total-xs">'.number_format($undoneHours).'</span>'.
                                '<span>学时</span><p class="hidden-xs">'.
                                Html::img(['/filedata/teamwork/image/view_undone.png ']).'</p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_NORMAL
                                ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 total">
                    <?= Html::a('<div class="col-xs-12 course-door" style="border-right: none"><span class="visible-xs-inline-block">'.
                                Html::img(['/filedata/teamwork/image/undone.png'], ['class' => 'total-icon']).
                                Html::img(['/filedata/teamwork/image/view_undone.png ']).'</span>'.
                                '<span class="undone total-xs">'.number_format($undoneDoor).'</span>'.
                                '<span>门</span><p class="hidden-xs">'.
                                Html::img(['/filedata/teamwork/image/view_undone.png ']).'</p></div>', [
                                    'course/index', 'status' => CourseManage::STATUS_NORMAL
                                ]) ?>
                </div>
                </div>
        </div>
    </div>
</div>

<div class="container item-manage-index item-manage">
    <center><div class="row">
        <?php
            foreach ($team as $value) {
            $teamCompletedHours = $twTool->getCourseLessionTimesSum([
                    'team_id' => $value->id, 'status' => CourseManage::STATUS_CARRY_OUT]);
            $teamUndoneHours = $twTool->getCourseLessionTimesSum([
            'team_id' => $value->id, 'status' => CourseManage::STATUS_NORMAL]);
                echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="padding:0px;">';
                     echo '<div class="team">';
                          echo '<div class="team-top" style="background:url('.$value->image.') no-repeat">';
                               echo '<div>'.Html::a(Html::img(['/filedata/teamwork/image/view_team_member.png']), [
                                   'member', 'team_id' => $value->id
                               ]).'</div>';
                               echo '<div class="team-name">'.$value->name.'</div>';
                          echo '</div>';
                          echo '<div class="team-bottom">';
                                echo Html::a('<div class="team-bottom-left"><p><span class="completed" style="margin-right:5px;">'.
                                            number_format(array_sum($teamCompletedHours)).'</span><span>学时</span></p><p>'.
                                            Html::img(['/filedata/teamwork/image/view_completed.png']).'</p></div>', [
                                                'course/index', 'team_id' => $value->id, 'status' => CourseManage::STATUS_CARRY_OUT
                                            ]);
                                echo Html::a('<div class="team-bottom-right"><p><span class="undone" style="margin-right:5px;">'.
                                            number_format(array_sum($teamUndoneHours)).'</span><span>学时</span></p><p>'.
                                            Html::img(['/filedata/teamwork/image/view_undone.png']).'</p></div>', [
                                                'course/index', 'team_id' => $value->id, 'status' => CourseManage::STATUS_NORMAL
                                            ]);        
                          echo '</div>';
                     echo '</div>';
                echo '</div>';
            }
            /** 学工厂 
            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="padding:0px;">';
                echo '<div class="team">';
                     echo '<div class="team-top" style="background:url('.$scienceFactory->image.') no-repeat">';
                          echo '<div>'.Html::img(['/filedata/teamwork/image/view_team_member.png']).'</div>';
                          echo '<div class="team-name">'.$scienceFactory->name.'</div>';
                     echo '</div>';
                     echo '<div class="team-bottom">';
                          echo '<div class="team-bottom-left">'.
                                  '<p><span class="completed">'.''.'</span><span>学时</span></p>'.
                                  '<p>'.Html::img(['/filedata/teamwork/image/view_completed.png']).'</p>'.
                              '</div>';
                          echo '<div class="team-bottom-right">'.
                                  '<p><span class="undone">'.''.'</span><span>学时</span></p>'.
                                  '<p>'.Html::img(['/filedata/teamwork/image/view_undone.png']).'</p>'.
                               '</div>';
                     echo '</div>';
                echo '</div>';
            echo '</div>';*/
        ?>
    </div></center>
</div>
<?= $this->render('_footer', [
    'twTool' => $twTool
]); ?>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#item-manage-form').submit();
    });
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<style type="text/css">
    .content {
        background-color: #e9e9e9;
    }
    .content span {
        font-size: 14px;
        color: #696969;
        display: inline-block;
        padding: 5px 0px;
    }
    @media (max-width: 767px){
        .head-bottom .container {
            padding: 0px;
        }
    }
</style>

<?php
    TwAsset::register($this);
?>