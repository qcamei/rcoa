<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;

/**
 * 标题：任务-审核申请结果 
   内容： 
        您好！任务已经通过审核并指派,请及时查看！ | 接收到任务指派,请及时查看！ 
        课程名称   ： ｛课程名称｝ 
        任务名称   ： ｛课程名称｝ 
        要求完成时间： ｛计划验收时间｝ 
        制作人   ： ｛制作人名称｝ |  发布人   ： ｛发布人名称｝
        备注     ： ｛备注｝
  
 * 马上查看(连接到任务详细页) 
 */

 /* @var $model WorksystemTask */

?>
<?php if(isset($nickname)): ?>
<div class="gray">您好！任务已经通过审核并指派,请及时查看！ </div>
<?php else: ?>
<div class="gray">您好！接收到任务指派,请及时查看！ </div>
<?php endif; ?>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">任务名称：<?= Html::encode($model->name) ?></div>

<?php if(isset($nickname)): ?>
<div class="normal">制作人：<?= Html::encode($nickname) ?></div>
<div class="normal">要求完成时间：<?= Html::encode($model->plan_end_time) ?></div>
<div class="highlight">备注：<?= Html::encode($des) ?></div>
<?php else: ?>
<div class="normal">发布人：<?= Html::encode($model->createBy->nickname) ?></div>
<div class="highlight">要求完成时间：<?= Html::encode($model->plan_end_time) ?></div>
<div class="normal">备注：<?= Html::encode($des) ?></div>
<?php endif; ?>
