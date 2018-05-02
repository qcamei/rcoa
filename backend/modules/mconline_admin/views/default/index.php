<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\widgets\DetailView;

$this->title = Yii::t('null', '{Information}{Statistics}', [
            'Information' => Yii::t('app', 'Information'),
            'Statistics' => Yii::t('app', 'Statistics'),
        ]);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mconline_admin-default-index mcbs default-view">
    <div class="col-md-12 col-xs-12 frame frame-left">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('null', '{Space}{Information}',[
                'Space' => Yii::t('app', 'Space'),
                'Information' => Yii::t('app', 'Information'),
            ]) ?></span>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => [
                [
                    'label' => '历史总文件数',
                    'value' => !empty($model['dataHistroy']['number']) ? $model['dataHistroy']['number'] . ' 个' : null,
                ],
                [
                    'label' => '现存总文件数',
                    'value' => !empty($model['dataNow']['number']) ? $model['dataNow']['number'] . ' 个' : null,
                ],
                [
                    'label' => '历史总文件大小',
                    'value' => !empty($model['dataHistroy']['size']) ? \Yii::$app->formatter->asShortSize($model['dataHistroy']['size']) : null,
                ],
                [
                    'label' => '现存总文件大小',
                    'value' => !empty($model['dataNow']['size']) ? \Yii::$app->formatter->asShortSize($model['dataNow']['size']) : null,
                ],
//                [
//                    'label' => '已标记文件个数',
//                    'value' => null,
//                ],
//                [
//                    'label' => '已标记文件大小',
//                    'value' => null,
//                ],
            ]
        ]);?>
    </div>
    <div class="col-md-12 col-xs-12 frame frame-left">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('null', '{Courses}{Information}',[
                'Courses' => Yii::t('app', 'Courses'),
                'Information' => Yii::t('app', 'Information'),
            ]) ?></span>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => [
                [
                    'label' => '总课程数',
                    'value' => !empty($model['coursedata']['courseNum'] . ' 门') ? $model['coursedata']['courseNum'] . ' 门' : null,
                ],
                [
                    'label' => '总 章/节 数',
                    'value' => !empty($model['coursedata']['chapterNum'] . ' 章' . '/' . $model['coursedata']['sectionNum'] . ' 节') ?
                            $model['coursedata']['chapterNum'] . ' 章' . '/' . $model['coursedata']['sectionNum'] . ' 节' : null,
                ],
                [
                    'label' => '总活动数',
                    'value' => !empty($model['coursedata']['activityNum'] . ' 个') ? $model['coursedata']['activityNum'] . ' 个' : null,
                ],
            ]
        ]);?>
    </div>

</div>

<?php
    McbsAssets::register($this);
