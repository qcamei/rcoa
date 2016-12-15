<?php

use common\models\demand\DemandTask;
use common\models\teamwork\CourseManage;
use wskeee\utils\DateUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandTask */

CourseManage::$progress = $progress;

?>
<div class="course-manage-view">
    
    <?php
        echo DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'table table-bordered detail-view'],
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => [
                ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">基本信息</span>','value' => ''],
                [
                    'attribute' => 'item_type_id',
                    'value' => !empty($model->item_type_id) ? $model->itemType->name : null,
                ],
                [
                    'attribute' => 'item_id',
                    'value' => !empty($model->item_id) ? $model->item->name : null,
                ],
                [
                    'attribute' => 'item_child_id',
                    'value' => !empty($model->item_child_id) ? $model->itemChild->name : null,
                ],
                [
                    'attribute' => 'course_id',
                    'value' => !empty($model->course_id) ? $model->course->name : null,
                ],
                [
                    'attribute' => 'teacher',
                    'value' => !empty($model->teacher) ? $model->speakerTeacher->nickname : null,
                ],
                [
                    'attribute' => 'lesson_time',
                    'value' => $model->lesson_time,
                ],
                [
                    'attribute' => 'credit',
                    'value' => $model->credit,
                ],
                [
                    'attribute' => 'course_description',
                    'format' => 'raw',
                    'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->course_description.'</div>',
                ],
                ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">开发信息</span>','value' => ''],
                [
                    'attribute' => 'mode',
                    'format' => 'raw',
                    'value' => $model->mode == DemandTask::MODE_NEWBUILT ?
                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->mode] : 
                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->mode] ,
                ],
                [
                    'attribute' => 'team_id',
                    'format' => 'raw',
                    'value' => !empty($model->team_id) ? '<span class="team-span">'.$model->team->name.'</span>' : null,
                ],
                [
                    'attribute' => 'undertake_person',
                    'format' => 'raw',
                    'value' => !empty($model->undertake_person) ? $model->undertakePerson->user->nickname : null,
                ],
                [
                    'attribute' => 'plan_check_harvest_time',
                    'value' => $model->plan_check_harvest_time,
                ],
                [
                    'attribute' => 'reality_check_harvest_time',
                    'value' => $model->reality_check_harvest_time,
                ],
                [
                    'attribute' => Yii::t('rcoa/teamwork', 'Now Progress'),
                    'format' => 'raw',
                    'value' => Html::beginTag('div', ['class' => 'col-lg-2', 'style' => 'padding:0px;']).
                                Html::beginTag('div', [
                                    'class' => 'progress table-list-progress',
                                    'style' => 'height:12px;margin:4px 0;border-radius:0px;'
                                ]).
                                    Html::beginTag('div', [
                                        'class' => 'progress-bar progress-bar',
                                        'style' => 'width:'.CourseManage::$progress[$model->id].'%;line-height: 12px;font-size: 10px;',
                                    ]).(!empty(CourseManage::$progress[$model->id]) ? CourseManage::$progress[$model->id] : 0).'%'.
                                    Html::endTag('div').
                               Html::endTag('div').
                            Html::endTag('div'),
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => $model->getStatusName(),
                ],
                ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">其它信息</span>','value' => ''],
                [
                    'attribute' => 'create_by',
                    'value' => !empty($model->create_by) ? $model->createBy->nickname : null,
                ],
                [
                    'attribute' => 'created_at',
                    'value' => date('Y-m-d H:i', $model->created_at),
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => date('Y-m-d H:i', $model->updated_at),
                ],
                [
                    'attribute' => 'des',
                    'format' => 'raw',
                    'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->des.'</div>',
                ],
            ],
        ]) 
    ?>

</div>
