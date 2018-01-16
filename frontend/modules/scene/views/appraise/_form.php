<?php

use common\models\question\QuestionOp;
use common\models\scene\SceneAppraise;
use common\models\scene\SceneAppraiseTemplate;
use common\models\scene\SceneBookUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneAppraise */
/* @var $form ActiveForm */

?>

<div class="scene-appraise-form">
    
    <?php $form = ActiveForm::begin(['options'=>['id' => 'scene-book-form','class'=>'form-horizontal']]); ?>
    
    
    <?php
        $index = 1;
        foreach($subjects as $appraise){
            /* @var $appraise SceneAppraiseTemplate */
            if($model->role == $appraise->role){
                $items = ArrayHelper::map($appraise->question->ops, 'value', function($questionOp){
                    /* @var $op QuestionOp */
                    return $questionOp->value."分 ( $questionOp->title )";
                });
                echo Html::label(($index++).'、'.$appraise->question->title);              
                echo Html::radioList("SceneAppraise[user_value][{$appraise->q_id}]", 
                        (count($appraiseResult[$appraise->role]) > 0 ? 
                            $appraiseResult[$appraise->role][$appraise->q_id]->user_value : $appraise->value),
                        $items, 
                        [
                            'class'=>'form-group appraise',
                            'itemOptions' => [
                                'labelOptions'=>[
                                    'class' =>'radio-group',
                                ],
                                'disabled' => count($appraiseResult[$appraise->role]) > 0 ? true : false,
                            ],
                        ]);
                
                echo Html::hiddenInput("SceneAppraise[q_id][{$appraise->q_id}]", $appraise->q_id);
                
                echo Html::hiddenInput("SceneAppraise[q_value][{$appraise->q_id}]", $appraise->value);
                
                echo Html::hiddenInput("SceneAppraise[index][{$appraise->q_id}]", $appraise->index);
            }

        }
    ?>
                
    <?= Html::activeHiddenInput($model, 'book_id') ?>
    
    <?= Html::activeHiddenInput($model, 'role') ?>
    
    <?= Html::activeHiddenInput($model, 'user_id') ?>
    
    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
    //给<label>标签后面添加<br/>标签
    $(".appraise>label").after("<br/>");
    //设置radio选中改变颜色
    $(".appraise>label>input:radio:checked").parent().css("color","#2E6DA4");
    $(".appraise>label>input:radio").change(function (){
        $('.appraise>label>input:radio[name="'+this.name+'"]').parent().css("color","#000000");
        $('.appraise>label>input:radio:checked[name="'+this.name+'"]').parent().css("color","#2E6DA4");  
    });
        
JS;
    $this->registerJs($js, View::POS_READY);
?>