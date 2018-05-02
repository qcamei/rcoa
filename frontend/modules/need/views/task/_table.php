<?php

use common\models\need\NeedTask;
use yii\web\View;

/* @var $this View */
/* @var $model NeedTask */

?>
<div class="col-xs-12 frame">
    <div class="col-xs-12 title">
        <i class="glyphicon glyphicon-usd"></i>
        <span><?= Yii::t('app', '开发成本') ?></span>
    </div>
    <table class="table table-list table-bordered table-frame table-view">
        <thead>
            <tr>
                <th style="width: 100px;"></th>
                <th style="width: 300px;">预计</th>
                <th style="width: 300px;">实际</th>
                <th style="width: 300px;">差值</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: right">内容</td>
                <td>￥<?= $model->plan_content_cost ?></td>
                <td>
                    <?= $model->reality_content_cost > $model->plan_content_cost ? 
                        '<span class="danger">￥'. $model->reality_content_cost .' ↑</span>' : 
                        ($model->reality_content_cost < $model->plan_content_cost ? 
                        '<span class="primary">￥'. $model->reality_content_cost .' ↓</span>' : '￥'.$model->reality_content_cost)
                    ?>
                </td>
                <td>
                    <?php $Dvalue = $model->reality_content_cost - $model->plan_content_cost;
                        echo $Dvalue > 0 ?  '<span class="danger">+'. $Dvalue .'</span>' :  ($Dvalue < 0 ? '<span class="primary">'. $Dvalue.'</span>' : $Dvalue)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">外包</td>
                <td>￥<?= $model->plan_outsourcing_cost ?></td>
                <td>
                    <?= $model->reality_outsourcing_cost > $model->plan_outsourcing_cost ? 
                        '<span class="danger">￥'. $model->reality_outsourcing_cost .' ↑</span>' : 
                        ($model->reality_outsourcing_cost < $model->plan_outsourcing_cost ? 
                        '<span class="primary">￥'. $model->reality_outsourcing_cost .' ↓</span>' : '￥'.$model->reality_outsourcing_cost)
                    ?>
                </td>
                <td>
                    <?php $Dvalue = $model->reality_outsourcing_cost - $model->plan_outsourcing_cost;
                        echo $Dvalue > 0 ?  '<span class="danger">+'. $Dvalue .'</span>' :  ($Dvalue < 0 ? '<span class="primary">'. $Dvalue.'</span>' : $Dvalue)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">绩效</td>
                <td>
                    ￥<?= $model->plan_content_cost * $model->performance_percent ?>
                </td>
                <td>
                    <?php $plan_score = $model->plan_content_cost * $model->performance_percent;
                        $reality_score = $model->reality_content_cost * $model->performance_percent;
                        echo $reality_score > $plan_score ? '<span class="danger">￥'. $reality_score .' ↑</span>' : 
                        ($reality_score < $plan_score ? '<span class="primary">￥'. $reality_score .' ↓</span>' : '￥'.$reality_score)
                    ?>
                </td>
                <td>
                    <?php $Dvalue = $reality_score - $plan_score;
                        echo $Dvalue > 0 ?  '<span class="danger">+'. $Dvalue .'</span>' :  ($Dvalue < 0 ? '<span class="primary">'. $Dvalue.'</span>' : $Dvalue)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">总</td>
                <td>
                    <?php $plan_total = $model->plan_content_cost + $plan_score + $model->plan_outsourcing_cost; 
                        echo '￥' . $plan_total;
                    ?>
                </td>
                <td>
                   <?php $reality_total = $model->reality_content_cost + $reality_score + $model->reality_outsourcing_cost;
                        echo $reality_total > $plan_total ? '<span class="danger">￥'. $reality_total .' ↑</span>' : 
                        ($reality_total < $plan_total ? '<span class="primary">￥'. $reality_total .' ↓</span>' : '￥'.$reality_total)
                   ?> 
                </td>
                <td>
                    <?php $Dvalue = $reality_total - $plan_total;
                        echo $Dvalue > 0 ?  '<span class="danger">+'. $Dvalue .'</span>' :  ($Dvalue < 0 ? '<span class="primary">'. $Dvalue.'</span>' : $Dvalue)
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="tip">
        注意： 
        <span><i class="danger">↑</i>成本增加</span>
        <span><i class="primary">↓</i>成本下降</span>
    </div>
    
</div>
