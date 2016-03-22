<?php

use common\models\expert\Expert;
use common\models\expert\searchs\ExpertSearch;
use frontend\modules\expert\ExpertAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Experts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container expert-index" style="padding:0;">
    <?= Html::img(Yii::$app->request->hostInfo.'/filedata/expert/personalImage/u183.jpg', [
        'id' => 'img',
        'class' => 'col-sm-12 col-md-12 col-xs-12 ',
        'height' => '360',
        'style' => 'padding:1.5%;'
    ])?>
    <?php foreach ($modelType as $modelBtn){
        echo Html::a($modelBtn->name.'('.Expert::find()
                        ->where(['type' => $modelBtn->id])
                        ->count().')', 
                        ['type', 'id' => $modelBtn->id], 
                        ['class' => 'btn btn-default btn-lg dropdown-toggle']);
       
    }?>
    <b style="float: right;">专家总数：
    <?php 
        echo Expert::find()
            ->count();
    ?></b>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-10 col-md-11 col-xs-9">
                <?= $this->render('_form_search')?>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php
    ExpertAsset::register($this);
?>