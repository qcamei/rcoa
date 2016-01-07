<?php

use common\models\expert\Expert;
use frontend\modules\expert\ExpertAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Expert */
$this->title = 'ExpertsType';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $this->title = $model->expertType->name; ?>
    </div>
</div>

<div class="container expert-type bookdetail-list has-title">
    <?php foreach ($modelExpert as $expert): ?>
    <a href="http://rcoa.gzedu.net/expert/default/view?id=<?= $expert['u_id']; ?>"><div style="height: 74px; border:1px solid #CCC;">
        <div style="float: left; ">
        <?= Html::img($expert['personal_image'], [
            'class' => 'img-rounded',
            'style' => 'margin:5px',
            'width' => '60',
            'height' => '60',
        ])?>
        </div>
        <div>
            <span style="margin-top:0.5%; display: block;"><b><?= $expert['user']['nickname'] ?>(<?= $expert['job_title'] ?>)</b></span>
            <p style="margin:0;"><span>职称：</span><?= $expert['job_name'] ?></p>
            <p class="course-name" ><span>描述：</span><?= $expert['attainment'] ?></p>
        </div>
    </div></a>
    <?php endforeach;?>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= Html::textInput('s', '', [ 'class' => 'form-control', 'placeholder' => '请输入关键字...',]) ?>
            </div>
            <?= Html::a('搜索', '#', ['class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', 'index', ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>
<?php
    ExpertAsset::register($this);
?>