<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}{List}',[
    'Scene' => Yii::t('app', 'Scene'),
    'List' => Yii::t('app', 'List'),
]);
$filter = Yii::$app->request->queryParams;
?>
<div class="scene-manage-index container">
    <div class="list col-lg-12">
        <!--场地列表-->
        <?php foreach ($sceneItem['querylist'] as $key => $scenes) :?>
        <div class="list-content col-lg-6 col-xs-12">
            <div class="content-content">
                <div class="list-top">
                    <span class="list-name"><?= $scenes['name']?></span>
                    <?= Html::a(Yii::t('app', '{Set}{Disabled}',[
                            'Set' => Yii::t('app', 'Set'),
                            'Disabled' => Yii::t('app', 'Disabled'),
                        ]),['disable', 'site_id' => $scenes['id'], 'date' => date('Y-m-d'), 'date_switch' => 'month'],[
                            'class' => 'btn btn-default btn-sm',
                            'style' => ['float' => 'right']
                        ])?>
                </div>
                <div class="list-content">
                    <a href="<?= Url::to(['view', 'id' => $scenes['id']]) ?>" class="list-img" title="<?= $scenes['address']?>">
                        <img src="<?= $scenes['img_path']?>">
                    </a>
                    <div class="list-right">
                        <div class="list-nature bg-color <?= ($scenes['op_type'] == 1) ? 'add-red' : 'add-blue'?>">
                                                    <?= ($scenes['op_type'] == 1) ? '自营' : '合作'?></div>
                        <div class="list-area">区域：<span><?= $scenes['area']?></span>&nbsp;
                                                    <font class="font">(<?= $scenes['address']?>)</font></div>
                        <div class="list-type">内容类型：<span><?= $scenes['content_type']?></span></div>
                        <div class="list-price">价格：<span>￥<?= $scenes['price']?>/小时</span> （4小时起）</div>
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