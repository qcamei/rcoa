<?php

use frontend\modules\sites\SitesAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('rcoa', '场地');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
    SitesAsset::register($this);
?>