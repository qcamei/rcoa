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
    <?php if($expertProjects == null){
        echo '无';
    } ?>
    <ol>
    <?php foreach ($expertProjects as $keyProject): ?>
    
    <li><?= DetailView::widget([
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
                'value'=> Yii::$app->formatter->asCurrency($keyProject->cost),
            ],
            [
                'label' => '合作融洽度',
                'value'=> ExpertProject::$compatibilityMap[$keyProject->compatibility],
            ],
        ],
    ]) ?></li>
    <?php endforeach;?>
    </ol>
    
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <form id="form-assign-key" action="http://rcoa.gzedu.net/expert/default/categories" method="get">
                    <div id="radio">
                        <input type="radio" name="fieldName" value="all" checked/><label>全部</label>
                        <input type="radio" name="fieldName" value="job_title"/><label>头衔</label>
                        <input type="radio" name="fieldName" value="job_name"/><label>职称</label>
                        <input type="radio" name="fieldName" value="nickname"/><label>专家名称</label>
                        <input type="radio" name="fieldName" value="name"/><label>专家类型</label>
                        <input type="radio" name="fieldName" value="employer"/><label>单位信息</label>
                        <input type="radio" name="fieldName" value="attainment"/><label>主要成就</label>
                    </div>
                    <input type="text" name="key" class="form-control" placeholder="请输入关键字...">
                </form>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', ['type', 'id' => $model->type], ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
   /** 单击显示搜索字段 */
   $('#form-assign-key input[name = "key"]').click(function(){
       $('#radio').css("display","block");
   });
    
   /** 提交搜索 */
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