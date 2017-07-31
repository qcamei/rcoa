<?php

use common\widgets\cslider\CSlider;
use common\widgets\cslider\CSliderAssets;

?>
<div class="unittest-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
    <div style="display: inline-block;width: 100px">
        <?= CSlider::widget([
            'plugOptions' => [
                'height' => 6,                  //进度条高度
                'max' => 10,                    //最大值，默认是1
                'value' => 2,
                'trackColor' => '#ddd',         //滑动条底色块
                'sliderColor' => '#ef1e25',     //已选择颜色 #ef1e25 红色，#428bca 蓝色，#56cb90 绿色
                'tooltipColor' => '#ef1e25',    //提示颜色
            ]
        ]) ?>
    </div>
    <div>
        <?= CSlider::widget([
            'plugOptions' => [
                'width' => '200',
                'value' => 0.8,
                'valueText' => 'good',
                'sliderColor' => '#428BCA',     //已选择颜色
                'tooltipColor' => '#428BCA',    //提示颜色
            ]
        ]) ?>
    </div>
    
    <?= CSlider::widget([
        'plugOptions' => [
            'value' => 1,
            'valueText' => 'perfect',
            'sliderColor' => '#56cb90',     //已选择颜色
            'tooltipColor' => '#56cb90',    //提示颜色
        ]
    ]) ?>
    
     <?= CSlider::widget([
            'plugOptions' => [
                'width' => '200',
                'max' => 10,
                'value' => 5,
                'valueText' => '不达标',
                'sliderColor' => '#428BCA',     //已选择颜色
                'tooltipColor' => '#428BCA',    //提示颜色
            ]
        ]) ?>
</div>
<?php 
    CSliderAssets::register($this);
?>
