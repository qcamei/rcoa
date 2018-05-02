<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseSummary;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model CourseSummary */

$this->title = Yii::t('rcoa/teamwork', 'Create Course Summary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Summaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container course-summary-create has-title">

    <div class="container" role="document" style="margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">

                <?= $this->render('_form', [
                    'model' => $model,
                    'weekly' => $weekly,
                ]) ?>

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
        $('#course-summary-form').submit();
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>