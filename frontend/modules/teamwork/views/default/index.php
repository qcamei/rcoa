<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $twTool TeamworkTool */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container item-manage-index item-manage">
    
    <?php 
        echo Html::beginTag('div', ['class' => 'item-manage-head']);
            echo Html::beginTag('div', ['class' => 'item-manage-headline']).'课程中心'.Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'lession-time']);
                echo '<p>已完成：<span>'.$completed.'</span>学时'.
                Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), 
                        ['index', 'status' => ItemManage::STATUS_CARRY_OUT]).'</p>
                <p>&nbsp;&nbsp;&nbsp;在做：<span>'.$undone.'</span>学时'.
                Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), 
                        ['index', 'status' => ItemManage::STATUS_NORMAL]).'</p>'; 
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    <?php
        foreach ($team as $value) {
            $completed = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT]);
            $undone = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL]);
            echo Html::beginTag('div', ['class' => 'item-manage-head item-manage-bottom']);
                echo Html::beginTag('div', ['class' => 'item-manage-headline']).$value->name.
                        Html::a(Html::img(['/filedata/image/u13784_team.png'], ['class' => 'eye-team']), ['member', 'team_id' => $value->id]).
                        Html::endTag('div');
                echo Html::beginTag('div', ['class' => 'lession-time']);
                    echo '<p>已完成：<span style="font-size:25px;">'.$completed.'</span>学时'.
                    Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), ['index', 
                        'team_id' => $value->id,
                        'status' => ItemManage::STATUS_CARRY_OUT
                    ]).'</p>
                    <p>&nbsp;&nbsp;&nbsp;在做：<span style="font-size:25px;">'.$undone.'</span>学时'.
                    Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), ['index', 
                        'team_id' => $value->id,
                        'status' => ItemManage::STATUS_NORMAL
                    ]).'</p>'; 
                echo Html::endTag('div');
            echo Html::endTag('div');
        }
    ?>
    
    
</div>

<?= $this->render('_footer'); ?>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#item-manage-form').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>