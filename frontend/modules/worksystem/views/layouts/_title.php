<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\widgets\Breadcrumbs;
    
/* @var $model WorksystemTask */

?>

<div class="title">
    <div class="container worksystem">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t('rcoa/worksystem', 'Worksystem'),
                'url' => isset($params) ? $params : null,
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => $title,
                    'template' => '<li class="course-name active">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<?php
    WorksystemAssets::register($this);
?>