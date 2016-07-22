<?php

use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootHistory;
use frontend\modules\shoot\components\EditHistoryList;
use frontend\modules\shoot\ShootAsset;
use kartik\widgets\Select2;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\ArrayHelper;
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
                'attribute' => 'u_booker',
                'format' => 'raw',
                'value' => $model->booker->nickname. '('.  Html::a($model->booker->phone, 'tel:'.$model->booker->phone).' )',
            ],
            [
                'attribute' => 'u_contacter',
                'format' => 'raw',
                'value' => (isset($model->u_contacter) ? implode(',', $reloadContacts) : "空"),
            ],
            [
                'attribute' => 'start_time',
                'value' => $model->start_time,
            ],
            
            
            ['label' => '<span class="btn-block viewdetail-th-head">课程信息</span>','value'=>''],
            [
                'attribute' => 'business_id',
                'value' => $model->business->name,
            ],
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
                'attribute' => 'personal_image',
                'format' => 'raw',
                'value' => Html::img($model->teacher->personal_image,[
                    'width' => '140',
                    'height' => '140',
                ]),
            ],
            [
                'attribute' => 'u_teacher',
                'format' => 'raw',
                'value' => $model->teacher->user->nickname.'('. Html::a($model->teacher->user->phone, 'tel:'.$model->teacher->user->phone) .')',
            ],
            [
                'attribute' => 'teacher_email',
                'format' => 'raw',
                'value' => Html::a($model->teacher->user->email, 'mailto:'.$model->teacher->user->email),
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
                'value' => $isShootManLeader && ($model->getIsAssign() || $model->getIsStausShootIng())?
                            Select2::widget([
                                'name' => 'shoot_man',
                                'value' => empty($model->u_shoot_man) ?  '' : $shootMansKey,
                                'data' => empty($model->u_shoot_man) ? $shootmans : ArrayHelper::merge($assignedShootMans, $shootmans),
                                'size' => 'lg',
                                'maintainOrder' => true,
                                'hideSearch' => true,
                                'options' => [
                                    'placeholder' => '选择摄影师...',
                                    'multiple' => true
                                ],
                                'toggleAllSettings' => [
                                    'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                                    'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                                    'selectOptions' => ['class' => 'text-success'],
                                    'unselectOptions' => ['class' => 'text-danger'],
                                ],
                                'pluginOptions' => [
                                    'tags' => false,
                                    'maximumInputLength' => 10,
                                    'allowClear' => true,
                                ],
                                'pluginEvents' => [
                                    'change' => 'function(){ select2Log();}'
                                ]
                            ]): (isset($model->u_shoot_man) ? implode(',', $reloadShootMans) : "空"),
            ],
            [
                'attribute' => 'remark',
                //'format' => 'raw',
                'value' =>  $model->remark,
            ],
        ],
    ]);
    ?>
    <?= Html::hiddenInput('b_id',$model->id) ?>
    
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