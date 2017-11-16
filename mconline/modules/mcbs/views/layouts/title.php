<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\widgets\Breadcrumbs;

/* @var $model McbsCourse */
?>

<div class="title">
    <div class="container create_title">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t(null, '{Mcbs}{Courses}', [
                    'Mcbs' => Yii::t('app', 'Mcbs'),
                    'Courses' => Yii::t('app', 'Courses'),
                ]),
                'url' => isset($params) ? $params : null,
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => $title,
                    'template' => '<li class="course-name active">{link}</li>',
                ],
            ]
        ]);
        ?>
    </div>
</div>

<?php
    McbsAssets::register($this);
?>