<?php

use common\models\demand\DemandTask;
use common\models\teamwork\CourseManage;
use frontend\modules\demand\assets\DefaultAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
$this->title = Yii::t('rcoa/demand', 'Demand Tasks');
?>

<div class="demand-default-index">
    <div class="demand-default-head">
        <div class="demand-default-headtop"></div>
        <div class="demand-default-headbottom">
            <div class="container">
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                    <a href="./task/index?status=<?= DemandTask::STATUS_COMPLETED; ?>">
                        <div class="col-xs-12 demand-default-total">
                            <div class="visible-xs-inline-block">
                                <img src="/filedata/teamwork/image/completed.png"  class="total-icon"/>
                            </div>
                            <span class="completed-label">已完成</span>
                            <span class="completed-num timer" data-to="<?= $completed; ?>" data-speed="550">0</span>
                            <span>学时</span>
                        </div>
                    </a>

                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                    <a href="./task/index?status=<?= DemandTask::STATUS_DEFAULT; ?>">
                        <div class="col-xs-12 demand-default-total">
                            <div class="visible-xs-inline-block">
                                <img src="/filedata/teamwork/image/undone.png"  class="total-icon"/>
                            </div>
                            <span class="undone-label">在&nbsp;&nbsp;&nbsp;建</span>
                            <span class="undone-num timer" data-to="<?= $unfinished; ?>" data-speed="550">0</span>
                            <span>学时</span>
                        </div>
                    </a>

                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                    <a href="./task/index?status=<?= DemandTask::STATUS_DEFAULT; ?>">
                        <div class="col-xs-12 demand-default-total" style="border-right: none;">
                            <div class="visible-xs-inline-block">
                                <img src="/filedata/teamwork/image/undone.png"  class="total-icon"/>
                            </div>
                            <span class="undone-label">R&nbsp;M&nbsp;B</span>
                            <span class="undone-num timer" data-to="<?= 2223; ?>" data-speed="550">0</span>
                            <span>&nbsp;&nbsp;元</span>
                        </div>
                    </a>

                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="container demand-default-container">
    
    <?php foreach($team as $value): ?>
    
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="demand-default-team">
                <div class="team-top" style="background: url(<?= $value['image'] ?>)">
                    <?= Html::a(
                        Html::img([$value['team_icon']]).'<br/><span class="team-name">'.$value['name'].'</span>', [
                            'member', 'team_id' => $value['id']], [
                                'class' => 'team-icon'
                    ])?>
                </div>
                <div class="team-bottom">
                    
                    <div class="col-xs-12 team-total">
                        <span class="team-completed-label">已完成</span>
                        <div class="team-completed-num">
                            <span class="timer timer-completed" data-to="<?= isset($teamCompleted[$value['id']]) ? $teamCompleted[$value['id']] : 0 ; ?>" data-speed="550">0</span>
                            <span>学时</span>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 team-total">
                        <span class="team-undone-label">在&nbsp;&nbsp;&nbsp;建</span>
                        <div class="team-undone-num">
                            <span class="timer timer-undone" data-to="<?= isset($teamUnfinished[$value['id']]) ? $teamUnfinished[$value['id']] : 0; ?>" data-speed="550">0</span>
                            <span>学时</span>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 team-total">
                        <span class="team-undone-label">R&nbsp;M&nbsp;B</span>
                        <div class="team-undone-num">
                            <span class="timer timer-undone" data-to="<?= 332333; ?>" data-speed="550">0</span>
                            <span>&nbsp;&nbsp;元</span>
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </div>

    <?php endforeach;?>
    
</div>

<?php 
$js = <<<JS
    $('.timer').each(count);  // 启动所有定时器
       
JS;
    $this->registerJs($js,  View::POS_READY); 
?>

<?php
    DefaultAssets::register($this);
?>