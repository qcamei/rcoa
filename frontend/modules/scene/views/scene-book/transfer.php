<?php

use common\models\mconline\McbsActionLog;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model McbsActionLog */

$this->title = Html::encode('申请转让');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-actlog-view mcbs">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'options'=>[
                        'id' => 'scene-book-form',
                        'class'=>'form-horizontal',
                        'method' => 'post',
                    ],
                ]); ?>
                
                <?= Html::activeHiddenInput($model, 'is_transfer', ['value' => 1]) ?>
                
                <div class="form-group field-content">
                    <label class="col-lg-1 col-md-1 control-label form-label" for="content">原因</label>
                    <div class="col-lg-10 col-md-10">
                        <?= Html::textarea('content', '无', ['rows' => 6, 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
                </div>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Submit'), ['id'=>'submitsave','class'=>'btn btn-primary',
                'onclick' => 'submitsave();']) ?>
            </div>
       </div>
    </div>

</div>

<?php
$js = 
<<<JS
    
    window.submitsave = function(){
        $("#scene-book-form").submit();
    }
   
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>