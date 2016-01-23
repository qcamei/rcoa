<?php

use common\models\expert\Expert;
use common\models\expert\ExpertProject;
use common\models\User;
use frontend\modules\expert\ExpertAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
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
    
    <h5><b>合作项目</b></h5>
    
    <?php foreach ($expertProjects as $keyProject): ?>
        <?= DetailView::widget([
        'model' => $keyProject,
        'options' => ['class' => 'table ees table-striped table-bordered detail-view'],
        'attributes' => [
            [
                'label' => '项目名称',
                'value'=> $keyProject->project->name,
            ],
            [
                'label' => '合作时间',
                'value'=> $keyProject->start_time.  '—'  .( $keyProject->end_time != null ? $keyProject->end_time : "至今"),
            ],
            [
                'label' => '费用报酬',
                'value'=> Yii::$app->formatter->asCurrency($keyProject->cost,"￥"),
            ],
            [
                'label' => '合作融洽度',
                'value'=> ExpertProject::$compatibilityMap[$keyProject->compatibility],
            ],
        ],
    ]) ?>
    <?php endforeach;?>
    
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?php $form = ActiveForm::begin(['action' => ['categories'],'method' => 'get', 'id' => 'form-assign-key']); ?>
                <?= Html::textInput('key', '', ['class' => 'form-control', 'placeholder' => '请输入关键字...',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', ['type', 'id' => $model->type], ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
   $('#submit').click(function(){
       if($('#form-assign-key input[name="key"]').val() == '')
           alert("请输入关键字");
        else
           $('#form-assign-key').submit();
   });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    ExpertAsset::register($this);
?>