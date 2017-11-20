<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model McbsCourse */

$this->title = Yii::t(null, '{Create}{Mcbs}{Courses}', [
            'Create' => Yii::t('app', 'Create'),
            'Mcbs' => Yii::t('app', 'Mcbs'),
            'Courses' => Yii::t('app', 'Courses'),
        ]);
?>

<?= 
    $this->render('/layouts/title', [
        'params' => ['index'],
        'title' => Yii::t('rcoa', 'Create'),
    ]) 
?>

<div class="container mcbs-course-create mcbs">

    <?= $this->render('_form', [
            'model' => $model,
            'itemTypes' => $itemTypes,
            'items' => $items,
            'itemChilds' => $itemChilds,
            'courses' => $courses,
        ])
    ?>

</div>

<?=
    $this->render('/layouts/footer', [
        'model' => $model,
        'params' => ['index'],
    ])
?>

<?php
$js = <<<JS
      
    $('.clickselected').click(function(){
        var dataValue = $(this).attr('data-value');
        $('#mcbs_type_id').find('option[value='+dataValue+']').attr('selected', true);
        $('#add-attribute').load("/worksystem/add-attributes/create?task_type_id="+dataValue);
    });
        
    $('#contentinfo').load("/worksystem/contentinfo/index");   
    
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>