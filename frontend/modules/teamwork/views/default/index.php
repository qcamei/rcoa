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

<div class="row">
    <div class="item-manage-top-bg">
        <div class="container">
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12" style="padding: 0px;">
                <div class="item-manage-top-left">
                    <div class="lession-time">
                        <span>已完成：</span>
                        <span class="col-lg-4 col-md-4 col-sm-5 col-xs-4 completed-undone">
                            <?= $completed; ?>
                        </span>
                        <span>学时</span>
                        <?= Html::a('', ['course/index', 'status' => ItemManage::STATUS_CARRY_OUT], [
                            'class' => 'eye-icon-button',
                        ]); ?>
                    </div>
                    <div class="lession-time">
                        <span>在&nbsp;&nbsp;&nbsp;&nbsp;建：</span>
                        <span class="col-lg-4 col-md-4 col-sm-5 col-xs-4 completed-undone">
                            <?= $undone; ?>
                        </span>
                        <span>学时</span>
                        <?= Html::a('', ['course/index', 'status' => ItemManage::STATUS_NORMAL], [
                            'class' => 'eye-icon-button',
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-5 hidden-xs" style="padding:0px;float: right">
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
                            <span class="col-lg-4 col-md-4 col-sm-4 completed" style="padding: 0px;">0</span>
                            <span>学时</span>
                            <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_CARRY_OUT]*/, [
                                'class' => 'item-manage-team-icon-2 science-factory-icon-2'
                            ]) ?>
                        </div>
                        <div class="team-lession-time">
                            <span>在&nbsp;&nbsp;&nbsp;&nbsp;建：</span>
                            <span class="col-lg-4 col-md-4 col-sm-4 undone" style="padding: 0px;">0</span>
                            <span>学时</span>
                             <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_NORMAL]*/, [
                                'class' => 'item-manage-team-icon-2 science-factory-icon-2'
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
        foreach ($team as $value) {
            $completed = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT]);
            $undone = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL]);
            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="padding-left:5px;padding-right:5px;">';
                 echo '<div class="item-manage-team team-info">';
                      echo '<div class="item-manage-team-left team-info-left">';
                            echo '<center>';
                                echo Html::a('', ['member', 'team_id' => $value->id], [
                                    'class' => 'item-manage-team-icon-1 team-info-icon-1',
                                ]);
                            echo '</center>';
                      echo '</div>';
                      echo '<div class="item-manage-team-right">';
                           echo '<p>'.$value->name.'</p>';
                           echo '<div class="team-lession-time">';
                                echo '<span>已完成：</span>'
                                . '<span class="col-lg-4 col-md-3 col-sm-4 col-xs-3 completed" style="padding:0px;">'.$completed.'</span>'
                                . '<span>学时</span>'
                                . Html::a('', ['course/index', 'team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT], [
                                    'class' => 'item-manage-team-icon-2 team-info-icon-2'
                                ]);
                           echo '</div>';
                           echo '<div class="team-lession-time">';
                                echo '<span>在&nbsp;&nbsp;&nbsp;&nbsp;建：</span>'
                                . '<span class="col-lg-4 col-md-3 col-sm-4 col-xs-3 undone" style="padding:0px;">'.$undone.'</span>'
                                . '<span>学时</span>'
                                . Html::a('', ['course/index', 'team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL], [
                                    'class' => 'item-manage-team-icon-2 team-info-icon-2'
                                ]);
                           echo '</div>';
                      echo '</div>';
                 echo '</div>';
            echo '</div>';
        }
    ?>
        <div class="col-xs-12 visible-xs-inline-block" style="padding-left:5px;padding-right:5px;">
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
                            <span class="col-xs-3 completed" style="padding: 0px;">111</span>
                            <span>学时</span>
                            <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_CARRY_OUT]*/, [
                                'class' => 'item-manage-team-icon-2 science-factory-icon-2'
                            ]) ?>
                        </div>
                        <div class="team-lession-time">
                            <span>在&nbsp;&nbsp;&nbsp;&nbsp;建：</span>
                            <span class="col-xs-3 undone" style="padding: 0px;">111</span>
                            <span>学时</span>
                             <?= Html::a('', '#'/*['course/index', 'team_id' => '', 'status' => ItemManage::STATUS_NORMAL]*/, [
                                'class' => 'item-manage-team-icon-2 science-factory-icon-2'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
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