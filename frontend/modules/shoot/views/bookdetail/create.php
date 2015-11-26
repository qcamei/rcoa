<?php

use common\models\shoot\ShootBookdetail;
use wskeee\rbac\RbacManager;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model ShootBookdetail */

$this->title = Yii::t('rcoa', 'Create Shoot Bookdetail');
?>
<div class="title">
    <div class="container">
        <?php echo '预约操作：【'.$model->site->name.'】'.
                date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName() ?>
    </div>
</div>
<div class="container shoot-bookdetail-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'colleges' => $colleges,
        'projects' => $projects,
        'courses' => $courses,
    ]) ?>
</div>
<div class="controlbar">
    <div class="container">
        <?= Html::a(
                !$model->getIsValid() ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit','class' => (!$model->getIsValid()) ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Back'), ['exit-create', 'date' => date('Y-m-d', $model->book_time), 'b_id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php
    $js = 
 <<<JS
    $('#submit').click(function()
            {
                $('#bookdetail-create-form').submit();
            });
    
JS;
$this->registerJs($js,  View::POS_READY);
?>