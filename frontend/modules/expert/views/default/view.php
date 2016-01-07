<?php

use common\models\expert\Expert;
use common\models\expert\ExpertProject;
use common\models\User;
use frontend\modules\expert\ExpertAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Expert */

$this->title = $model->u_id;
$this->params['breadcrumbs'][] = ['label' => 'Experts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $this->title =  '专家详细'; ?>
    </div>
</div>
<div class="container expert-view bookdetail-list has-title">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'personal_image',
                'format'=>'raw',
                'value'=> Html::a(Html::img($model->personal_image,['width'=>'128px']), $model->personal_image),
            ],
            'u_id',
            'user.username',
            'user.nickname',
            [
                'attribute' => 'user.sex',
                'value'=> User::$sexName[$model->user->sex],
            ],
            'type',
            'birth',
            'user.phone',
            'job_title',
            'job_name',
            'level',
            'employer',
            'attainment:ntext',
        ],
    ]) ?>
    
   
    
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= Html::textInput('s', '', [ 'class' => 'form-control', 'placeholder' => '请输入关键字...',]) ?>
            </div>
            <?= Html::a('搜索', '#', ['class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', ['type', 'id' => $model->type], ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>
<?php
    ExpertAsset::register($this);
?>