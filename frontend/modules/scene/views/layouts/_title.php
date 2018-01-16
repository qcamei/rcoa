<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\widgets\Breadcrumbs;
    
?>

<div class="title">
    <div class="container scene">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t('app', 'Scene'),
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
    SceneAsset::register($this);
?>