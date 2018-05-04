<?php

use frontend\modules\need\assets\ModuleAssets;
use yii\web\View;

/* @var $this View */

$this->title = '任务-主页';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="need-default-index">
    
    <div class="head-top" style="background-image: url(/filedata/need/images/need_head.png)"></div>
    
    <div class="container content">
        <div class="content-title">
            <i class="fa fa-bar-chart">&nbsp;&nbsp;<?= date('Y')?>第<?= $season; ?>季在建统计</i>
        </div>
        <div class="need-cost">
            <div class="need-cost-title">
                需求<span>（成本）</span>
            </div>
            <div class="need-cost-content">
                <?php foreach ($needCosts as $needCost): ?>
                    <div class="user-need">
                        <div class="user-avatar">
                            <img class="img-circle" src="<?= $needCost['avatar']; ?>"/>
                        </div>
                        <div class="user-name"><?= $needCost['nickname']; ?></div>
                        <div class="user-cost">￥<?= number_format($needCost['need_cost'], 2); ?></div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        
        <div class="need-cost">
            <div class="need-cost-title">
                开发<span>（绩效）</span>
            </div>
            <div class="need-cost-content">
                <?php foreach ($demands as $demand): ?>
                    <div class="user-need">
                        <div class="user-avatar">
                            <img class="img-circle" src="<?= $demand['avatar']; ?>"/>
                        </div>
                        <div class="user-name"><?= $demand['nickname']; ?></div>
                        <div class="user-cost">￥<?= number_format($demand['demand'], 2); ?></div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    
</div>
<?php

$js = <<<JS

JS;
    $this->registerJs($js, View::POS_READY);
?>
<?php
    ModuleAssets::register($this);
?>
