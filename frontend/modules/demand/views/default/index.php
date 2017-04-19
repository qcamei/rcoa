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
    <div class="container">
        <div class="demand-default-words">
            <span class="words-ch">任务</span>
            <span class="words-en">Task</span>
        </div>
    </div>
    <div class="demand-default-team">
        <div class="container">
            <?php foreach($team as $value): ?>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 team">
                <div class="team-logo">
                    <?= Html::img([$value['team_icon']]) ?>
                </div>
                <div class="team-content">
                    <p>
                        <span class="team-name"><?= $value['name'] ?></span>
                        <a href="#" class="see"></a>
                    </p>
                    <p>
                        <span class="team-label">已完成</span>
                        <span class="team-number"><?= isset($teamCompleted[$value['id']]) ? number_format($teamCompleted[$value['id']]) : 0 ?></span>
                        <span class="team-unit">学时</span>
                    </p>
                    <p>
                        <span class="team-label">在&nbsp;&nbsp;&nbsp;&nbsp;建</span>
                        <span class="team-number"><?= isset($teamUnfinished[$value['id']]) ? number_format($teamUnfinished[$value['id']]) : 0 ?></span>
                        <span class="team-unit">学时</span>
                    </p>
                    <p>
                        <span class="team-label">RMB</span>
                        <span class="team-number"><?= isset($teamCost[$value['id']]) ? $teamCost[$value['id']] : 0 ?></span>
                        <span class="team-unit">元</span>
                    </p>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>    


<?php 
$js = <<<JS
   /** 设置.demand-default-index 大小*/
   size();
   $(window).resize(function(){
        size();
    });
    function size(){
        var height = $(document.body).height() - 100;
        if(height < 820)
            height = 820;
        var Height = height / 2;
        $(".demand-default-index").css({height:height, display:"block"});
        var wordsH = height - $(".demand-default-team").outerHeight();
        $(".demand-default-words").css({height: wordsH});
    }    
    //$('.timer').each(count);  // 启动所有定时器
       
JS;
    $this->registerJs($js,  View::POS_READY); 
?>

<?php
    DefaultAssets::register($this);
?>