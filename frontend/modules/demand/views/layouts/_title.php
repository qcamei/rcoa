<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\widgets\Breadcrumbs;
    
/* @var $model DemandTask */

?>

<div class="title">
    <div class="container demand">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
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
    DemandAssets::register($this);
?>