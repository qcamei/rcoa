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

<div class="item-manage-index">
    <div class="container">
        <div class="item-manage-words">
            <span class="words-big">我们最大的目标是：要为顾客节省每一分钱。</span><br/>
            <span class="words-small">——沃尔玛创始人萨姆·沃尔顿</span>
            <!--<span class="words-ch">开发</span>
            <span class="words-en">Development</span>-->
        </div>
    </div>
    <div class="item-manage-team">
        <div class="container">
            <?php foreach($team as $value): ?>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 team">
                <div class="team-logo">
                    <?= Html::img([$value['team_logo']]) ?>
                </div>
                <div class="team-content">
                    <p>
                        <span class="team-name"><?= $value['name'] ?></span>
                        <?= Html::a('', ['member', 'team_id' => $value['id']], ['class' => 'see']) ?>
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
                        <span class="team-unit">万元</span>
                    </p>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>    

<?= $this->render('_footer', [
    'twTool' => $twTool
]); ?>

<?php 
$js = <<<JS
   /** 设置.item-manage-index 大小*/
   size();
   $(window).resize(function(){
        size();
    });
    function size(){
        var height = $(document.body).height() - 100;
        var width = $(document.body).width();
        if(height < 820)
            height = 820;
        $(".item-manage-index").css({height:height, display:"block"});
        var wordsH = height - $(".item-manage-team").outerHeight();
        if(width <= 425)
            wordsH = 200;
        $(".item-manage-words").css({height: wordsH});
    }    
    //$('.timer').each(count);  // 启动所有定时器
       
JS;
    $this->registerJs($js,  View::POS_READY); 
?>

<?php
    TwAsset::register($this);
?>