<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $twTool TeamworkTool */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container item-manage-index item-manage">
    
    <div class="row">
    <?php 
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12', 
            'style' => 'padding-left:0px;padding-right:0px;']);
            echo Html::beginTag('div', ['class' => 'item-manage-head']);
                echo Html::beginTag('div', ['class' => 'item-manage-headline']).'课程中心'.Html::endTag('div');
                echo Html::beginTag('div', ['class' => 'lession-time']);
                    echo '<p><span>已完成：</span><span class="col-lg-2 col-md-2 col-sm-4 col-xs-6 completed-undone">'.
                            $completed.'</span><span>学时</span>'.
                            Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), 
                            ['course/index', 'status' => ItemManage::STATUS_CARRY_OUT]).'</p>
                        <p><span>在&nbsp;&nbsp;&nbsp;建：</span><span class="col-lg-2 col-md-2 col-sm-3 col-xs-6 completed-undone">'.
                            $undone.'</span><span>学时</span>'.
                            Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), 
                                    ['course/index', 'status' => ItemManage::STATUS_NORMAL]).'</p>';
                echo Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    </div>
    
    <div class="row">
    <?php
        foreach ($team as $value) {
            $completed = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_CARRY_OUT]);
            $undone = $twTool->getCourseLessionTimesSum(['team_id' => $value->id, 'status' => ItemManage::STATUS_NORMAL]);
            echo Html::beginTag('div', ['class' => 'col-lg-4 col-md-4 col-sm-6 col-xs-12']);
            echo Html::beginTag('div', ['class' => 'item-manage-head item-manage-bottom']);
                echo Html::beginTag('div', ['class' => 'item-manage-headline']).$value->name.
                        Html::a(Html::img(['/filedata/image/u13784_team.png'], ['class' => 'eye-team']), ['member', 'team_id' => $value->id]).
                        Html::endTag('div');
                echo Html::beginTag('div', ['class' => 'lession-time']);
                    echo '<p><span>已完成：</span><span class="col-lg-5 col-md-5 col-sm-5 col-xs-5 completed-undone">'.
                            $completed.'</span><span>学时</span>'.
                    Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), ['course/index', 
                        'team_id' => $value->id,
                        'status' => ItemManage::STATUS_CARRY_OUT
                    ]).'</p>
                     <p><span>在&nbsp;&nbsp;&nbsp;建：</span><span class="col-lg-5 col-md-5 col-sm-5 col-xs-5 completed-undone">'.
                            $undone.'</span><span>学时</span>'.
                    Html::a(Html::img(['/filedata/image/u13784_a.png'], ['class' => 'eye-team']), ['course/index', 
                        'team_id' => $value->id,
                        'status' => ItemManage::STATUS_NORMAL
                    ]).'</p>'; 
                echo Html::endTag('div');
            echo Html::endTag('div');
            echo Html::endTag('div');
        }
    ?>
    </div>
    
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
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>