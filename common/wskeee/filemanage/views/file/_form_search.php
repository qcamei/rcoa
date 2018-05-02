<?php

use wskeee\filemanage\models\searchs\FileManageSearch;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model FileManageSearch */
/* @var $form ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'id' => 'form-search',
    'action' => ['search'],
    'method' => 'get',
]); ?>

<?= Html::textInput('keyword', null, ['class' => 'form-control', 'placeholder'=>'请输入搜索关键字...']) ?>

<?php ActiveForm::end(); ?>
            
<?php  
 $js =   
<<<JS
         
   $('#submit').click(function(){
        if($('#form-search input[name="keyword"]').val() == '')
           alert("请输入关键字");
        else
           $('#form-search').submit();
   });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 