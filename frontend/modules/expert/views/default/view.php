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
                'value'=> Html::a(Html::img(Yii::$app->request->hostInfo.$model->personal_image,['width'=>'128px']), $model->personal_image),
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
                <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/expert/default/categories" method="get">
                    <ul class="dropdown clearfix" style="display:none;">
                        <li><input type="radio" id="all" name="fieldName" value="all" checked/><label for="all"><strong>全部</strong></label></li>
                        <li><input type="radio" id="job_title" name="fieldName" value="job_title"/><label for="job_title"><strong>头衔</strong></label></li>
                        <li><input type="radio" id="job_name" name="fieldName" value="job_name"/><label for="job_name"><strong>职称</strong></label></li>
                        <li><input type="radio" id="nickname" name="fieldName" value="nickname"/><label for="nickname"><strong>专家名称</strong></label></li>
                        <li><input type="radio" id="name" name="fieldName" value="name"/><label for="name"><strong>专家类型</strong></label></li>
                        <li><input type="radio" id="employer" name="fieldName" value="employer"/><label for="employer"><strong>单位信息</strong></label></li>
                        <li><input type="radio" id="attainment" name="fieldName" value="attainment"/><label for="attainment"><strong>主要成就</strong></label></li>
                    </ul>
                    <ul class="clearfix">
                        <li>
                            <input type="text" name="key" value="" id="keyword" class="form-control text searchtext" placeholder="请输入关键字..."/>
                            <span class="arrowDown"></span>
                        </li>
                    </ul>
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
   var ui = $('#form-assign-key');
		
    /** 对焦点上单击“显示”下拉列表中 **/
    ui.find('.searchtext').bind('focus click',function(){
        ui.find('.arrowDown').addClass('arrowUp').removeClass('arrowDown').andSelf().find('.dropdown').slideDown(50);
    });
   /** 鼠标离开隐藏下拉 **/
    ui.bind('mouseleave',function(){
        ui.find('.arrowUp').addClass('arrowDown').removeClass('arrowUp').andSelf().find('.dropdown').slideUp(50);
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