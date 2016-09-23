<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\multimedia\searchs\MultimediaTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container multimedia-task-index multimedia-task">

    
</div>

<?= $this->render('_footer', [
    'multimedia' => $multimedia,
]); ?>