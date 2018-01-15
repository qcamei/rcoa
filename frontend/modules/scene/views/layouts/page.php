<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $pages Pagination */

?>

<div class="page">
    <div class="page-wrap">
        <div class="page-num">
            <?= LinkPager::widget([
                'pagination' => $pages,
                'options' => ['class' => 'pagination', 'style' => 'margin: 0px;border-radius: 0px;'],
                'prevPageCssClass' => 'page-prev',
                'nextPageCssClass' => 'page-next',
                'prevPageLabel' => '<i>&lt;</i>'.Yii::t('app', 'Prev'),
                'nextPageLabel' => Yii::t('app', 'Next').'<i>&gt;</i>',
                'maxButtonCount' => 2,
            ]); ?>
        </div>
        <div class="page-skip">
            <?php if($pages->pageCount >= 2): ?>
            共<b><?= $pages->pageCount; ?></b>页&nbsp;&nbsp;到第<?= Html::textInput('page', $pages->page+1, ['class' => 'input-txt']) ?>页
            <?= Html::a(Yii::t('app', 'Confirm'), "javascript:;", ['id' => 'submit-page', 'class' => 'btn btn-default btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$is_scroll = isset($filter['page']) ? 1 : 0;
unset($filter['page']);
$url = Url::to(array_merge([Yii::$app->controller->action->id], array_merge($filter)));
$js = <<<JS
    $("#submit-page").click(function(){
        var pageValue = $(".input-txt").val();
        window.location.href="$url&page="+pageValue+"#scroll";
    });
    if($is_scroll)
        window.location.href= "#scroll";
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
