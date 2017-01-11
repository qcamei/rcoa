<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\TwAsset;
use wskeee\rbac\RbacName;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Progress');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['course/index', 'status' => CourseManage::STATUS_NORMAL],
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', '进度'),
                ],
            ]
        ]);?>
    </div>
</div>
<div class="container course-link-index has-title item-manage">
    
    <table class="table table-list">
        <thead>
            <tr style="background-color:#eee;">
                <th style="width:232px;padding:8px;">阶段</th>
                <th style="max-width:434px;min-width:140px;padding:8px;">环节</th>
                <th class="hidden-xs" style="width:130px;padding:8px;">总量</th>
                <th class="hidden-xs" style="width:171px;padding:8px;">已完成</th>
                <th class="hidden-xs" style="width:130px;padding:8px;">单位</th>
                <th style="width: 143px;padding:8px;">进度</th>
                <th style="width:80px;padding:8px;">操作</th>
            </tr>
            
        </thead>
        <tbody>
        <?php 
        $isUserBelongProducer = $twTool->getIsUserBelongProducer($course_id);
        
        foreach ($coursePhase as $phase) {
            $className = $phase->course->getIsNormal() && ($isUserBelongProducer 
                || $phase->course->coursePrincipal->u_id == Yii::$app->user->id || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)) ?
                    'btn btn-primary' : 'btn btn-primary disabled';
            /* @var $phase CoursePhase */
            echo '<tr style="background-color:#eee">
                <td>'.$phase->name.'</td>
                <td></td>
                <td class="hidden-xs"></td>
                <td class="hidden-xs"></td>
                <td class="hidden-xs"></td>
                <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar progress-bar-danger',
                                    'style' => 'width:'.($phase->progress * 100).'%',
                                ]).
                                ($phase->progress * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td>
                <td></td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                /* @var $link CourseLink */
                echo '<tr>
                    <td></td>
                    <td>'.$link->name.'</td>
                    <td class="hidden-xs">'.$link->total.'</td>
                    <td class="hidden-xs">'.$link->completed.'</td>
                    <td class="hidden-xs">'.$link->unit.'</td>
                    <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'style' => 'width:'.($link->total == 0 ? 0 :(int)(($link->completed / $link->total) * 100)).'%',
                                ]).
                                ($link->total == 0 ? 0 :(int)(($link->completed / $link->total) * 100)).'%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td>
                    <td>'.Html::a('录入', 'javascript:;', 
                            ['class' => $className, 'data_id' =>$link->id, 'onclick' => 'entry($(this));']).'</td>
                </tr>';
            }
        }
        ?>
        
        </tbody>
    </table>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
    </div>
</div>

<div class="item-manage">
    <?= $this->render('/course/_form_model')?>    
</div>

<script type="text/javascript">
    function entry(obj){
        var data_id = $(obj).attr("data_id");
        $(".myModal").modal('show');
        $(".myModal .modal-dialog .modal-content").load("/teamwork/courselink/entry?id="+data_id);
        return false;
    }
</script>

<?php
$js = 
<<<JS
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。
    $('.myModal').on('hidden.bs.modal', function () {
        window.location.reload();
    });*/    
        
    $(".myModal .modal-dialog").addClass("modal-md");
    $(".myModal .modal-dialog .modal-content").addClass("has-title");    
   
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>