<?php

use common\models\workitem\Workitem;
use frontend\modules\demand\assets\WorkitemList;
use yii\web\View;

/* @var $this View */
/* @var $model Workitem */

$this->title = '样例库';

?>
<div class="container workitem-cabinet-index">
    <div class="list-container md-12">
        <span class="page-title">样例库</span>
        <?php foreach ($models as $model): ?>   
            <?php if (count($model->cabinets) > 0): ?>
                <hr class="item-hr"/>
                <div class="list-item row">
                    <div class="col-md-6 col-sm-12 cabinet-container">
                        <video id="<?= $model->cabinets[0]->id ?>" class="cabinet-video" src="<?=$model->cabinets[0]->path?>"  poster="<?=$model->cabinets[0]->poster?>"></video>
                        <image videoid="<?= $model->cabinets[0]->id ?>" class="cabinet-play" src='/filedata/workitem/cabinet/play.png'/>
                    </div>
                    <div class="col-md-6 col-sm-12 workitem-info">
                        <div class="workitem-title"><?= $model->name ?></div>
                        <div class="workitem-cost">￥<?= $costs[$model->id]['cost_new'] ?></div>
                        <div class="workitem-des"><?= $model->des ?></div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php
    $js=<<<JS
    $('.cabinet-play').on('click',function(){
        var video = $('#'+$(this).attr('videoid'))[0];
        video.controls = true;
        video.play();
        $(this).css({display:'none'});    
    });
JS;
    $this->registerJs($js);
?>
<?php
    WorkitemList::register($this);
?>