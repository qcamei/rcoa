<?php

use common\models\mconline\searchs\McbsCourseSearch;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $searchModel McbsCourseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Courses}-{Attention}', [
            'Courses' => Yii::t('app', 'Courses'),
            'Attention' => Yii::t('app', 'Attention'),
        ]);
?>
<div class="mcbs-course-attention default-attention">

    <?= $this->render('/layouts/leftnav'); ?>

    <div class="col-md-10 col-xs-12">
        <?=
        $this->render('_search_attention', [
            'params' => $param,
        ])
        ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => [
                'class' => 'hidden',
            ],
            'pager' => [
                'options' => [
                    'class' => 'hidden',
                ]
            ],
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'label' => Yii::t('app', 'Item Child ID'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty($data['item_child_name']) ? $data['item_child_name'] : null;
                    },
                    'headerOptions' => [
                        'class' => [
                            'th' => 'hidden-xs hidden-sm',
                        ],
                        'style' => [
                            'width' => '220px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'hidden-xs course-name list-td hidden-sm',
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Course ID'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty($data['item_course_name']) ? $data['item_course_name'] : null;
                    },
                    'headerOptions' => [
                        'style' => [
                            'width' => '220px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'course-name list-td',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa', 'Create By'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty($data['create_by']) ? $data['create_by'] : null;
                    },
                    'headerOptions' => [
                        'class' => [
                            'th' => 'hidden-xs',
                        ],
                        'style' => [
                            'width' => '220px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'list-td hidden-xs',
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Updated At'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty(date('Y-m-d H:i', $data['updated_at'])) ? date('Y-m-d H:i', $data['updated_at']) : null;
                    },
                    'headerOptions' => [
                        'class' => [
                            'th' => 'hidden-xs hidden-sm',
                        ],
                        'style' => [
                            'width' => '180px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'hidden-xs updated-at list-td hidden-sm',
                    ],
                ],
                [
                    'header' => Yii::t('app', 'Operating'),
                    'headerOptions' => [
                        'style' => [
                            'width' => '60px',
                        ],
                    ],
                    'format' => 'raw',
                    'value' => function($data) {
                        return Html::a('查看', Url::to(['view', 'id' => $data['id']]), [
                                    'class' => 'btn btn-default',
                        ]);
                    },
                    'contentOptions' => [
                        'style' => [
                            'width' => '75px',
                        ],
                    ],
                ],
            ],
        ]);
        ?>

        <?php
        $page = !isset($param['page']) ? 1 : $param['page'];
        $pageCount = ceil($totalCount / 20);
        if ($pageCount > 0) {
            echo "<div class=\"summary\">第<b>" . (($page * 20 - 20) + 1) . "</b>-<b>" . ($page != $pageCount ? $page * 20 : $totalCount) . "</b>条，总共<b>{$totalCount}</b>条数据.</div>";
        }
        ?>

        <?=
        LinkPager::widget([
            'pagination' => new Pagination([
                'totalCount' => $totalCount,
                    ]),
        ])
        ?> 
    </div>
</div>

<?= $this->render('/layouts/footernav'); ?>

<?php
$js = <<<JS
        
    $('#submit').click(function(){
        $('#mcbs-search').submit();
    })  
        
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
McbsAssets::register($this);
?>