<?php

use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootHistory;
use frontend\modules\shoot\components\EditHistoryList;
use frontend\modules\shoot\ShootAsset;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model ShootBookdetail */

?>
<div class="auth-item-view">
    <?php $form = ActiveForm::begin(['id' => 'form-assign-shoot_man', 'action'=>'assign?id='.$model->id]); ?>
    <?php
    /* @var $authManager RbacManager */
    $authManager = Yii::$app->authManager;
    $isShootManLeader = $authManager->isRole(RbacName::ROLE_SHOOT_LEADER, Yii::$app->user->id);
    
    echo DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value'=>''],
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            [
                'attribute' => 'u_contacter',
                'value' => $model->contacter->nickname. '( '.$model->contacter->phone.' )',
            ],
            [
                'attribute' => 'u_booker',
                'value' => $model->booker->nickname. '( '.$model->booker->phone.' )',
            ],
            [
                'attribute' => 'start_time',
                'value' => $model->start_time,
            ],
            
            
            ['label' => '<span class="btn-block viewdetail-th-head">课程信息</span>','value'=>''],
            [
                'attribute' => 'fw_college',
                'value' => $model->fwCollege->name,
            ],
            [
                'attribute' => 'fw_project',
                'value' => $model->fwProject->name,
            ],
            [
                'attribute' => 'fw_course',
                'value' => $model->fwCourse->name,
            ],
            [
                'attribute' => 'lession_time',
                'value' => $model->lession_time,
            ],
            
            
            ['label' => '<span class="btn-block viewdetail-th-head">老师信息</span>','value'=>''],
            [
                'attribute' => 'teacher_name',
                'value' => "$model->teacher_name( $model->teacher_phone )",
            ],
            [
                'attribute' => 'teacher_email',
                'value' => $model->teacher_email,
            ],
            
            
            ['label' => '<span class="btn-block viewdetail-th-head">拍摄信息</span>','value'=>''],
            [
                'attribute' => 'shoot_mode',
                'value' => $model->getShootModeName(),
            ],
            [
                'attribute' => 'photograph',
                'value' => $model->photograph ? '是' : '否',
            ],
            [
                'attribute' => 'u_shoot_man', 
                'format' => 'raw',
                'value' => $isShootManLeader ?
                         Html::activeDropDownList($model, 'u_shoot_man', $shootmans,['prompt'=>'选择摄影师...']) : (isset($model->u_shoot_man) ? $model->shootMan->nickname : "空"),
            ],
            [
                'attribute' => 'remark',
                //'format' => 'raw',
                'value' =>  $model->remark,
            ],
        ],
    ]);
    ?>
    <?= Html::hiddenInput('editreason') ?>
    <?php ActiveForm::end(); ?>
    <h5><b>历史记录</b></h5>
    <?=
    EditHistoryList::widget([
        'dataProvider' => $model->historys,
        'template' => '<tr><th class="viewdetail-th">{label}：</th><td class="viewdetail-td">{value}</td></tr>',
        'tableOptions' => ['class' => 'edithistory-table list-group-item'],
        'attributes' => [
            [
                'attribute' => 'created_at',
                'label' => '时间',
                'value' => function($model){
                    /* @var $model ShootHistory */
                    return date('Y/m/d H:i:s',$model->created_at);
                }
            ],
            [
                'attribute' => 'u_id',
                'label' => '操作者',
                'value' => function($model){
                    /* @var $model ShootHistory */
                    return $model->u->nickname;
                }
            ],
            'history',
        ],
    ]);
    ?>
</div>
<?php
    ShootAsset::register($this);
?>