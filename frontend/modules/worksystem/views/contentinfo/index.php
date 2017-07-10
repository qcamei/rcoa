<?php

use common\models\worksystem\searchs\WorksystemContentinfoSearch;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorksystemContentinfoSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('rcoa/worksystem', 'Worksystem Contentinfos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container worksystem worksystem-contentinfo-index">
    
    <?= $this->render('_form') ?>
            
</div>

<?php
$js =   
<<<JS
     
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>