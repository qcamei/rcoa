<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model WorksystemTask */

$statusProgress = $this->render('_form_phase', [
    'model' => $model,
]);

?>

<table id="w0" class="table table-striped table-bordered detail-view">
    
    <tbody>
        <!--基本信息-->
        <tr>
            <th class="viewdetail-th"><span class="btn-block viewdetail-th-head">基本信息</span></th>
            <td class="viewdetail-td"></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Item Type ID') ?></th>
            <td class="viewdetail-td"><?= !empty($model->item_type_id) ? $model->itemType->name: '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Item ID') ?></th>
            <td class="viewdetail-td"><?= !empty($model->item_id) ? $model->item->name : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Item Child ID') ?></th>
            <td class="viewdetail-td"><?= !empty($model->item_child_id) ? $model->itemChild->name : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Course ID') ?></th>
            <td class="viewdetail-td"><?= !empty($model->course_id) ? $model->course->name : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Name') ?></th>
            <td class="viewdetail-td"><?= !empty($model->name) ? $model->name : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <!--开发信息-->
        <tr>
            <th class="viewdetail-th"><span class="btn-block viewdetail-th-head">开发信息</span></th>
            <td class="viewdetail-td"></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Level') ?></th>
            <td class="viewdetail-td"><?= $model->level == WorksystemTask::LEVEL_ORDINARY ? WorksystemTask::$levelName[$model->level] : '<span class="error-warn">'.WorksystemTask::$levelName[$model->level].'</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Is Epiboly') ?></th>
            <td class="viewdetail-td"><?= $model->getIsCancelEpiboly() ? '否' : '是' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Task Type ID') ?></th>
            <td class="viewdetail-td"><?= !empty($model->task_type_id) ? $model->worksystemTaskType->name : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Task Cost') ?></th>
            <td class="viewdetail-td"><?= !empty($model->budget_cost) || !empty($model->reality_cost) ? 
                            '￥'.number_format($model->budget_cost, 2, '.', ',').' / ￥'.number_format($model->reality_cost, 2, '.', ',') : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Task Bonus') ?></th>
            <td class="viewdetail-td"><?= !empty($model->budget_bonus) || !empty($model->reality_bonus) ? 
                            '￥'.number_format($model->budget_bonus, 2, '.', ',').' / ￥'.number_format($model->reality_bonus, 2, '.', ',') : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <?php foreach($attributes as $item): ?>
        <tr>
            <th class="viewdetail-th"><?= $item['name'] ?></th>
            <td class="viewdetail-td"><?= str_replace("\r\n", "、", $item['value']) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Plan End Time') ?></th>
            <td class="viewdetail-td"><span class="error-warn"><?= $model->plan_end_time ?></span></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'External Team') ?></th>
            <td class="viewdetail-td"><?= !empty($model->external_team) && !empty($model->create_team) ? 
                           ($model->external_team != $model->create_team && $model->getIsSeekEpiboly() ? '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) . '<span class="team-span team-span-left epiboly-team-span">'.$model->externalTeam->name.'</span>' : 
                           ($model->external_team != $model->create_team && $model->getIsSeekBrace() ? '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) . '<span class="team-span team-span-left">'.$model->externalTeam->name.'</span>' : '<span class="team-span">'.$model->createTeam->name.'</span>')) : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Producer') ?></th>
            <td class="viewdetail-td"><?= !empty($producer) ? $producer : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Phase') ?></th>
            <td class="viewdetail-td"><?= $statusProgress ?></td>
        </tr>
        <!--其它信息-->
        <tr>
            <th class="viewdetail-th"><span class="btn-block viewdetail-th-head">其它信息</span></th>
            <td class="viewdetail-td"></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Create Team') ?></th>
            <td class="viewdetail-td"><?= !empty($model->create_team) ? '<span class="team-span">'.$model->createTeam->name.'</span>' : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa', 'Create By') ?></th>
            <td class="viewdetail-td"><?= !empty($model->create_by) ? $model->createBy->nickname : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Created At') ?></th>
            <td class="viewdetail-td"><?= date('Y-m-d H:i', $model->created_at) ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Updated At') ?></th>
            <td class="viewdetail-td"><?= date('Y-m-d H:i', $model->updated_at) ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Finished At') ?></th>
            <td class="viewdetail-td"><?= !empty($model->finished_at) ? date('Y-m-d H:i', $model->finished_at) : '<span class="not-set">(未设置)</span>' ?></td>
        </tr>
        <tr>
            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Des') ?></th>
            <td class="viewdetail-td" style="height:65px;"><?= str_replace("\r\n", "<br/>", $model->des) ?></td>
        </tr>
        
    </tbody>
    
</table>

