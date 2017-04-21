<?php

use common\models\team\Team;
use frontend\modules\demand\assets\DefaultAssets;
use frontend\modules\teamwork\TeamworkTool;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $team Team */
/* @var $twTool TeamworkTool */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/team', 'Team Members');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title.'：'.$team->name ?>
    </div>
</div>

<div class="container demand-default-member has-title">
    
    <div class="des"><?= $team->des; ?></div>
    
    <div class="row">
        <?php foreach ($team->teamMembers as $member): ?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xm-12">
            <div class="member-bg">
                <div class="member-left">
                    <?= Html::img([$member->user->avatar], ['class' => 'img-circle']); ?>
                </div>
                <div class="member-right">
                    <p><span class="nickname"><?= $member->user->nickname; ?></span></p>
                    <p><span class="position"><?= $member->position->name; ?></span></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

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
    DefaultAssets::register($this);
?>
