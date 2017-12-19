<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\Link;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
use wskeee\rbac\components\ResourceHelper;
use wskeee\rbac\RbacName;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model CourseLink */
/* @var $twTool TeamworkTool*/
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Links');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['course/index', 'status' => CourseManage::STATUS_NORMAL],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View'),
                    'url' => ['course/view', 'id' => $model->course_id],
                    'template' => '<li class="course-name">{link}</li>',
                ],
                [
                    'label' => Yii::t('rcoa', 'Deploy').'：'.$model->course->demandTask->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-link-index has-title item-manage">
    
    <table class="table table-list">
        <thead>
            <tr style="background-color:#eee;">
                <th style="width:232px;padding:8px;">阶段</th>
                <th style="max-width:434px;min-width:140px;padding:8px;">环节</th>
                <th style="width:130px;padding:8px;">权重</th>
                <th class="hidden-xs" style="width:130px;padding:8px;">类型</th>
                <th class="hidden-xs" style="width:130px;padding:8px;">单位</th>
                <th style="width:135px;padding:8px;">操作</th>
            </tr>
            
        </thead>
        <tbody>
        <?php foreach ($coursePhase as $phase) {
            /* @var $phase CoursePhase */
            echo '<tr style="background-color:#eee">
                <td>'.$phase->name.'</td>
                <td></td>
                <td>'.$phase->weights.'</td>
                <td class="hidden-xs"></td>
                <td class="hidden-xs"></td>
                <td>'.ResourceHelper::a('修改',['update', 'id' => $phase->id], ['class' => 'btn btn-primary'], $model->course->getIsNormal()).' '.
                 ResourceHelper::a('删除',['phase-delete', 'id' => $phase->id], ['class' => 'btn btn-danger'], $model->course->getIsNormal()).'</td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                /* @var $link CourseLink */
                echo '<tr>
                    <td></td>
                    <td>'.$link->name.'</td>
                    <td></td>
                    <td class="hidden-xs">'.Link::$types[$link->type].'</td>
                    <td class="hidden-xs">'.$link->unit.'</td>
                    <td><div class="hidden-xs" style="width:58px;height:34px;float:left;"></div>'.ResourceHelper::a('删除',['link-delete', 'id' => $link->id], ['class' => 'btn btn-danger'], $model->course->getIsNormal()).'</td>
                </tr>';
            }
        }
        ?>
        </tbody>
    </table>
    
</div>

<div class="controlbar">
    <div class="container">
        <?php
            /**
             * $buttonHtml = [
             *     [
             *         name  => 按钮名称，
             *         url  =>  按钮url，
             *         options  => 按钮属性，
             *         symbol => html字符符号：&nbsp;，
             *         conditions  => 按钮显示条件，
             *         adminOptions  => 按钮管理选项，
             *     ],
             * ]
             */
            $buttonHtml = [
                [
                    'name' => Yii::t('rcoa', 'Back'),
                    'url' => ['course/view','id' => $course_id],
                    'options' => ['class' => 'btn btn-default'],
                    'symbol' => '&nbsp;',
                    'conditions' => true,
                    'adminOptions' => true,
                ],
                [
                    'name' => '新增',
                    'url' => ['create', 'course_id' => $course_id],
                    'options' => ['class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->course->getIsNormal(),
                    'adminOptions' => true,
                ],
            ];

            foreach ($buttonHtml as $item) {
                echo ResourceHelper::a($item['name'], $item['url'], $item['options'], $item['conditions']).($item['conditions'] ? $item['symbol'] : null);
            }
            
            ?>
    </div>
</div>

<?php
$js = 
<<<JS

JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>