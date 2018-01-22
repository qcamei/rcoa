<?php

use common\models\question\QuestionOp;
use common\models\scene\SceneAppraise;
use common\models\scene\SceneBookUser;
use common\models\scene\searchs\SceneAppraiseSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneAppraiseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Scene Appraises');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-appraise-index">
    <div class="scene-appraise">
    <?php
        if(count($appraiseResult) > 0)
            echo '<h4>没有评价数据。</h4>';
        else{
            $index = 1;
            foreach($appraiseResult as $key => $allModels){
                echo '<div class="role-name"><h5><b>对'.SceneBookUser::$roleName[$key].'评价</b></h5></div>';
                foreach ($allModels as $appraise) {
                    /* @var $appraise SceneAppraise */
                    echo "<p>".Html::label(($index++).'、'.$appraise->question->title)."</p>";
                    foreach ($appraise->question->ops as $questionOp) {
                        /* @var $questionOp QuestionOp */
                        echo "<p>";
                        if($questionOp->value == $appraise->user_value)
                            echo "<span class=\"appraise add-color\">{$questionOp->value}分（{$questionOp->title}）</span>";
                        else 
                            echo "<span class=\"appraise\">{$questionOp->value}分（{$questionOp->title}）</span>";
                        echo "</p>";
                    }
                }
            }
        }
    ?>
    </div>
</div>
