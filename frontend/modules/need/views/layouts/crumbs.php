<?php

use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumbs'],
            'homeLink' => [
                'label' => Yii::t('app', 'Need Tasks'),
                'url' => isset($params) ? $params : null,
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => $this->title,
                    'template' => '<li class="course-name active">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>