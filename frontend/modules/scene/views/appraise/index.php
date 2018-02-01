<?php

use common\models\question\QuestionOp;
use common\models\scene\SceneAppraise;
use common\models\scene\SceneBookUser;
use common\models\scene\searchs\SceneAppraiseSearch;
use frontend\modules\scene\assets\SceneAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneAppraiseSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('app', 'Scene Appraises');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-appraise-index">
    <div class="scene-appraise">
    <?php
        if(count($appraiseResults['results']) <= 0)
            echo '<h5>没有评价数据。</h5>';
        else{
            $index = 1;
            foreach($appraiseResults['results'] as $key => $allModels){
                $score = array_sum($appraiseResults['user_value'][$key])/array_sum($appraiseResults['q_value'][$key]) * count($appraiseResults['q_value'][$key]);
                echo "<div class=\"role-name\">"
                        ."<h5><b>对".SceneBookUser::$roleName[$key]."评价</b></h5>"
                            .'<div id="star" class="star" data-score="'.$score.'"></div>'
                    ."</div>";
                foreach ($allModels as $appraise) {
                    if($index > 3) $index = 1;
                    /* @var $appraise SceneAppraise */
                    echo "<div class=\"appraise-title\">".Html::label(($index++).'、'.$appraise->question->title)."</div>";
                    echo '<div class="form-group appraise">';
                        foreach ($appraise->question->ops as $questionOp) {
                            /* @var $questionOp QuestionOp */
                            echo "<p>";
                            if($questionOp->value == $appraise->user_value)
                                echo "<span class=\"add-color\">{$questionOp->value}分（{$questionOp->title}）</span>";
                            else 
                                echo "<span>{$questionOp->value}分（{$questionOp->title}）</span>";
                            echo "</p>";
                        }
                    echo '</div>';
                }
            }
        }
    ?>
    </div>
</div>

<?php

$js = 
<<<JS
    //评价星星
    $('.star').raty({
        number: 3,
        score: function() {
            return Math.floor($(this).attr('data-score'));
        },
        path: '/filedata/scene/icons',
        readOnly: true,
    });
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>
