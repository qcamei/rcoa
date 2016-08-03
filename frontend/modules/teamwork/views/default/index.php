<?php

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
    <div class="head-bg">
        <div class="container">
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12" style="padding: 0px;">
                <div class="total">
                    <div class="lession-time">
                        <span>已完成学时：</span>
                        <span class="col-lg-6 col-md-6 col-sm-5 col-xs-4 completed-undone">
                            <?= $completed; ?>
                        </span>
                        <?= Html::a('', ['course/index', 'status' => ItemManage::STATUS_CARRY_OUT], [
                            'class' => 'view_big',
                        ]); ?>
                    </div>
                    <div class="lession-time">
                        <span>未完成学时：</span>
                        <span class="col-lg-6 col-md-6 col-sm-5 col-xs-4 completed-undone">
                            <?= $undone; ?>
                        </span>
                        <?= Html::a('', ['course/index', 'status' => ItemManage::STATUS_NORMAL], [
                            'class' => 'view_big',
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 hidden-xs" style="padding:0px;float: right">
                <div class="science-factory">
                    <div class="science-factory-left">
                        <?= Html::a(Html::img(['/filedata/teamwork/image/view_science_factory.png'], [
                            'class' => 'view-science-factory',
                        ]), ['member', 'team_id' => '']) ?>
                    </div>
                    <div class="science-factory-right">
                        <p><?= $scienceFactory->name?></p>
                        <div class="lession-time">
                            <span>已完成学时：</span>
                            <span class="col-lg-5 col-md-5 col-sm-5 completed-undone" style="padding: 0px;">00000000</span>
                            <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_CARRY_OUT]*/, [
                                'class' => 'view-small',
                                'style' => 'margin-top:-20px'
                            ]) ?>
                        </div>
                        <div class="lession-time">
                            <span>未完成学时：</span>
                            <span class="col-lg-5 col-md-5 col-sm-5 completed-undone" style="padding: 0px;">00000000</span>
                             <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_NORMAL]*/, [
                                'class' => 'view-small',
                                 'style' => 'margin-top:-20px'
                             ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container item-manage-index item-manage">
    <div class="row">
    <?php
        foreach ($teamMember as $value) {
            $completed = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT]);
            $undone = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL]);
            echo '<center><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="padding-left:5px;padding-right:5px;">';
                 echo '<div class="team-lession-time">';
                    echo '<div class="team-lession-time-top" style="background:url('.$value->image.') no-repeat">';
                        echo '<center>';
                            echo Html::a(Html::img(['/filedata/teamwork/image/view_team_member.png'], [
                                'class' => 'view-team-member',
                            ]), ['member', 'team_id' => $value->id]);
                        echo '</center>';
                    echo '</div>';
                    echo '<div class="team-lession-time-bottom">';
                        echo '<center><p>'.$value->name.'</p></center>';
                        echo '<div class="lession-time">';
                            echo '<i class="icon completed-icon"></i><span>已完成学时：</span>'
                            . '<span class="col-lg-4 col-md-4 col-sm-4 col-xs-4 completed" style="padding:0px;">'.$completed.'</span>'
                            . Html::a('', ['course/index', 'team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT], [
                                'class' => 'view-small'
                            ]);
                        echo '</div>';
                        echo '<div class="lession-time">';
                            echo '<i class="icon undone-icon"></i><span>未完成学时：</span>'
                            . '<span class="col-lg-4 col-md-4 col-sm-4 col-xs-4 undone" style="padding:0px;">'.$undone.'</span>'
                            . Html::a('', ['course/index', 'team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL], [
                                'class' => 'view-small'
                            ]);
                        echo '</div>';
                    echo '</div>';
                 echo '</div>';
            echo '</div></center>';
        }
    ?>
        <!--<div class="col-xs-12 visible-xs-inline-block" style="padding-left:5px;padding-right:5px;">
            <div class="item-manage-team science-factory">
                <div class="item-manage-team-left science-factory-left">
                    <center>
                        <?= Html::a('', ['member', 'team_id' => ''], [
                            'class' => 'item-manage-team-icon-1 science-factory-icon-1'
                        ]) ?>
                    </center>
                </div>
                <div class="item-manage-team-right">
                    <p>学工厂</p>
                    <div class="team-lession-time">
                        <span>已完成：</span>
                        <span class="col-xs-3 completed" style="padding: 0px;">0</span>
                        <span>学时</span>
                        <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_CARRY_OUT]*/, [
                            'class' => 'item-manage-team-icon-2 science-factory-icon-2'
                        ]) ?>
                    </div>
                    <div class="team-lession-time">
                        <span>在&nbsp;&nbsp;&nbsp;&nbsp;建：</span>
                        <span class="col-xs-3 undone" style="padding: 0px;">0</span>
                        <span>学时</span>
                         <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_NORMAL]*/, [
                            'class' => 'item-manage-team-icon-2 science-factory-icon-2'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>

<?= $this->render('_footer'); ?>

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

<?php
    TwAsset::register($this);
?>