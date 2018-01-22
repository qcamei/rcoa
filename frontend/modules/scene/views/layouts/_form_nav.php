<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\web\View;
 
/* @var $this View */
/* @var $model SceneBook */
?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), isset($params) ? $params : 'javascript:;', ['class' => 'btn btn-default'])?>
        
        <?= Html::a(
            !$model->getIsValid() ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 'javascript:;', 
            ['id'=>'submit', 'class' => !$model->getIsValid() ? 'btn btn-success' : 'btn btn-primary']);
        ?>
    </div>
</div>

<?php

$js = 
<<<JS
        
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>