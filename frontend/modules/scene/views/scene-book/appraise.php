<?php

use common\models\scene\SceneAppraise;
use frontend\modules\scene\assets\SceneAsset;
use yii\web\View;

/* @var $this View */
/* @var $model SceneAppraise */

?>

<div class="col-xs-12 frame">
    <div class="col-xs-12 frame-title">
        <i class="icon fa fa-file-text-o"></i>
        <span><?= Yii::t('app', '评价结果') ?></span>
    </div>
    <div class="col-xs-12 frame-table">
        <?= $this->render('/appraise/index', ['appraiseResult' => $appraiseResult]) ?>
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