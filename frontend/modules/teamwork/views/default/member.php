<?php

use common\models\team\Team;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
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

<div class="container item-manage-member has-title item-manage">
    <div class="row">
    <?php
        foreach ($teamMember as $value) {
            echo Html::beginTag('div', ['class' => 'col-lg-3 col-md-3 col-sm-4 col-xm-12']);
                echo Html::beginTag('div', ['class' => 'member-bg']);
                    echo Html::beginTag('div', ['class' => 'left']).
                        Html::img([$value->u->avatar], ['class' => 'img-circle']).Html::endTag('div');
                    echo Html::beginTag('div', ['class' => 'right']).
                        '<p><span class="span-name">'.$value->u->nickname.'</span></p>
                         <p><span class="span-position">'.$value->position->name.'</span></p>' .Html::endTag('div');
                echo Html::endTag('div');
            echo Html::endTag('div');
        }
        //exit;
    ?>
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
    TwAsset::register($this);
?>
