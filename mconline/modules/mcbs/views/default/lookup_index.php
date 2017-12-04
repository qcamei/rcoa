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

$this->title = Yii::t(null, '{Courses}-{Lookup}', [
            'Lookup' => Yii::t('app', 'Lookup'),
            'Courses' => Yii::t('app', 'Courses'),
        ]);
?>
<div class="mcbs-course-attention default-lookup">

    <?= $this->render('/layouts/leftnav'); ?>

    <div class="col-lg-10 col-xs-12">
        <?=
        $this->render('_search_lookup', [
            'params' => $param,
            //条件
            'itemTypes' => $itemTypes,
            'items' => $items,
            'itemChilds' => $itemChilds,
            'createBys' => $createBys,
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
                    'label' => Yii::t('rcoa/worksystem', 'Item ID'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty($data['item_name']) ? $data['item_name'] : null;
                    },
                    'headerOptions' => [
                        'class' => [
                            'th' => 'hidden-xs hidden-sm hidden-md',
                        ],
                        'style' => [
                            'width' => '190px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'hidden-xs course-name list-td hidden-sm hidden-md',
                    ],
                ],
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
                            'width' => '260px',
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
                            'width' => '260px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'course-name list-td',
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Create By'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return !empty($data['created_by']) ? $data['created_by'] : null;
                    },
                    'headerOptions' => [
                        'class' => [
                            'th' => 'hidden-xs',
                        ],
                        'style' => [
                            'width' => '120px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' => [
                        'class' => 'list-td hidden-xs',
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
                                    'class' => 'btn btn-default btn-sm',
                        ]);
                    },
                    'contentOptions' => [
                        'style' => [
                            'width' => '65px',
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