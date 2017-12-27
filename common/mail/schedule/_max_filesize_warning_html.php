<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;

/**
 * 标题：空间占用超出警戒线
   内容： 
        请注意！文件空间已经超出警戒线，请及时处理！
        实际占用：｛251.00 BG｝ 
        警戒设置：｛250 GB｝ 
        上限设置：｛300 GB｝ 
        剩余空间：｛49.00 GB｝ 
        备注    ：｛备注｝
  
 * 马上查看
 */

 /* @var $model WorksystemTask */

?>
<div class="gray">请注意！文件空间已经超出警戒线，请及时处理！</div>
    
<div class="normal">实际占用：<?= Html::encode(Yii::$app->formatter->asShortSize($current_value)) ?></div>

<div class="normal">警戒设置：<?= Html::encode(Yii::$app->formatter->asShortSize($warning_value)) ?></div>

<div class="normal">上限设置：<?= Html::encode(Yii::$app->formatter->asShortSize($max_value)) ?></div>

<div class="highlight">剩余空间：<?= Html::encode(Yii::$app->formatter->asShortSize($remain_value)) ?></div>

<div class="normal">备注：<?= Html::encode($des) ?></div>