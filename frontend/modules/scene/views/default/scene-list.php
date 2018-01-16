<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}{List}',[
    'Scene' => Yii::t('app', 'Scene'),
    'List' => Yii::t('app', 'List'),
]);
$filter = Yii::$app->request->queryParams;
?>
<div class="scene-default-list container">
    <div class="list col-lg-12">
        <!--场地列表-->
        <?php foreach ($sceneItem['query'] as $key => $scnes) :?>
        <div class="list-content col-lg-6 col-xs-12">
            <div class="content-content">
                <div class="list-top">
                    <span class="list-name"><?= $scnes['name']?></span>
                    <span class="btn btn-default btn-sm" style="float: right">设置禁用</span>
                </div>
                <div class="list-content">
                    <a href="<?= Url::to(['view', 'id' => $scnes['id']]) ?>" class="list-img" title="<?= $scnes['address']?>">
                        <img src="<?= $scnes['img_path']?>">
                    </a>
                    <div class="list-right">
                        <div class="list-nature bg-color <?= ($scnes['op_type'] == 1) ? 'add-red' : 'add-blue'?>">
                                                    <?= ($scnes['op_type'] == 1) ? '自营' : '合作'?></div>
                        <div class="list-area">区域：<span><?= $scnes['area']?></span>&nbsp;
                                                    <font class="font">(<?= $scnes['address']?>)</font></div>
                        <div class="list-type">内容类型：<span><?= $scnes['content_type']?></span></div>
                        <div class="list-price">价格：<span>￥<?= $scnes['price']?>/小时</span> （4小时起）</div>
                    </div>
                </div>
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