<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}-{List}',[
    'Scene' => Yii::t('app', 'Scene'),
    'List' => Yii::t('app', 'List'),
]);
$filter = Yii::$app->request->queryParams;
?>
<div class="scene-manage-index container">
    <div class="list col-lg-12">
        <!--场地列表-->
        <?php foreach ($sceneItem['querylist'] as $key => $scenes) :?>
        <div class="list-content col-lg-4 col-sm-6 col-xs-12">
            <div class="content-content">
                <div class="info-content">
                    <a href="<?= Url::to(['view', 'id' => $scenes['id']]) ?>" class="list-img" title="<?= $scenes['address']?>">
                        <div class="list-left">
                            <img src="<?= $scenes['img_path']?>">
                            <div class="list-mark bg-color <?= ($scenes['op_type'] == 1) ? 'add-red' : 'add-blue'?>">
                                                        <?= ($scenes['op_type'] == 1) ? '自营' : '合作'?></div>
                        </div>
                        <div class="list-right">
                            <div class="list-name"><?= $scenes['name']?></div>
                            <div class="list-area">区域：<span><?= $scenes['area']?></span>&nbsp;
                                                        <font class="font">(<?= $scenes['address']?>)</font></div>
                            <div class="list-type">内容：<span><?= $scenes['content_type']?></span></div>
                            <div class="list-price">价格：<span>￥<?= $scenes['price']?>/小时</span></div>
                        </div>
                    </a>
                </div>
                <a class="list-cog" href="<?= Url::to(['disable', 'site_id' => $scenes['id'], 'date' => date('Y-m-d'), 'date_switch' => 'month'])?>">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <!--分页-->
    <?= $this->render('/layouts/page', ['filter' => $filter, 'pages' => $sceneItem['listpages']]) ?>
</div>
<?php
$js = <<<JS
        
JS;
$this->registerJs($js, View::POS_READY);
?>
<?php
    SceneAsset::register($this);