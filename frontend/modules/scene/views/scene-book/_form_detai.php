<?php

use common\models\demand\DemandTask;
use common\models\scene\SceneBook;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model SceneBook */

$contacter = null;
$shootMan = null;
if(isset($sceneBookUser[$model->id])){
    foreach ($sceneBookUser[$model->id] as $userItem) {
            if($userItem['role'] == 1){
                $contacter .= $userItem['is_primary'] ? 
                    "<span style=\"color:#0066ff\">{$userItem['nickname']}（{$userItem['phone']}）</span>" : "{$userItem['nickname']}\t";
            }else if($userItem['role'] == 2){
                $shootMan .= $userItem['is_primary'] ? 
                    "<span style=\"color:#0066ff\">{$userItem['nickname']}（{$userItem['phone']}）</span>" : "{$userItem['nickname']}\t";
            }
    }
}
?>

<div class="scene-book-view scene-book">
<?= DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value' => ''],
            [
                'attribute' => 'site_id',
                'label' => Yii::t('app', 'Site'),
                'format' => 'raw',
                'value' => !empty($model->site_id) ? $model->sceneSite->name : null,
            ],
            [
                'attribute' => 'date',
                'format' => 'raw',
                'value' => "{$model->date}\t".SceneBook::$timeIndexMap[$model->time_index].
                        "\t<span style=\"color:#ff0000\">{$model->start_time}</span>（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）",
            ],
            [
                'attribute' => 'booker_id',
                'format' => 'raw',
                'value' => !empty($model->booker_id) ? $model->booker->nickname."（{$model->booker->phone}）" : null,
            ],
            [
                'label' => Yii::t('app', 'Contacter'),
                'format' => 'raw',
                'value' => $contacter,
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status,
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => date('Y-m-d H:i', $model->updated_at),
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">拍摄信息</span>','value' => ''],
            [
                'attribute' => 'content_type',
                'format' => 'raw',
                'value' => "<span class=\"content_type\">{$model->content_type}</span>",
            ],
            [
                'attribute' => 'is_photograph',
                'format' => 'raw',
                'value' => $model->is_photograph ? 
                    "<span class=\"photograph Yes\">".Yii::t('app', 'Y')."</span>" : 
                    "<span class=\"photograph No\">".Yii::t('app', 'N')."</span>",
            ],
            [
                'attribute' => 'lession_time',
                'format' => 'raw',
                'value' => $model->lession_time,
            ],
            [
                'attribute' => 'camera_count',
                'format' => 'raw',
                'value' => $model->camera_count,
            ],
            [
                'label' => Yii::t('app', 'Shoot Man'),
                'format' => 'raw',
                'value' => $shootMan,
            ],
            [
                'attribute' => 'remark',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->remark.'</div>',
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">课程信息</span>','value' => ''],
            [
                'attribute' => 'business_id',
                'format' => 'raw',
                'value' => !empty($model->business_id) ? $model->business->name : null,
            ],
            [
                'attribute' => 'level_id',
                'format' => 'raw',
                'value' => !empty($model->level_id) ? $model->level->name : null,
            ],
            [
                'attribute' => 'profession_id',
                'format' => 'raw',
                'value' => !empty($model->profession_id) ? $model->profession->name : null,
            ],
            [
                'attribute' => 'course_id',
                'format' => 'raw',
                'value' => !empty($model->course_id) ? $model->course->name : null,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">教师信息</span>','value' => ''],
            [
                'label' => Yii::t('app', 'Personal Image'),
                'format' => 'raw',
                'value' => Html::img(!empty($model->teacher_id) ? [$model->teacher->personal_image] : null, [
                    'width' => '128', 'height' => '125'
                ]),
            ],
            [
                'attribute' => 'teacher_id',
                'label' => Yii::t(null, '{teacher}{name}',[
                   'teacher' => Yii::t('app', 'Teacher'),
                   'name' => Yii::t('app', 'Name'),
                ]),
                'format' => 'raw',
                'value' => !empty($model->teacher_id) ? $model->teacher->user->nickname : null,
            ],
            [
                'attribute' => 'teacher_phone',
                'label' => Yii::t('app', 'Contact Mode'),
                'format' => 'raw',
                'value' => !empty($model->teacher_id) ? $model->teacher->user->phone : null,
            ],
            [
                'attribute' => 'teacher_email',
                'label' => Yii::t('app', 'Email'),
                'format' => 'raw',
                'value' => !empty($model->teacher_id) ? $model->teacher->user->email : null,
            ],
        ],
    ]) 
?>
</div>
