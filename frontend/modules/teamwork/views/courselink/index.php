<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model CourseLink */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Links');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title ?>
    </div>
</div>

<div class="container course-link-index has-title">
    
    <table class="table table-list">
        <thead>
            <tr style="background-color:#eee;">
              <th style="width:20%">阶段</th>
              <th style="width:25%">环节</th>
              <th style="width:15%">权重</th>
              <th style="width:15%">类型</th>
              <th style="width:13%">单位</th>
              <th style="width:12%">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($coursePhase as $phase) {
            /* @var $phase CoursePhase */
            $classUpdate = $twTool->getIsLeader() && $phase->course->create_by == Yii::$app->user->id ?
                         'btn btn-primary' : 'btn btn-primary disabled';
            $classDeletee = $twTool->getIsLeader() && $phase->course->create_by == Yii::$app->user->id ?
                         'btn btn-danger' : 'btn btn-danger disabled';
            echo '<tr style="background-color:#eee">
            <td colspan="2">'.$phase->phase->name.'</td>
            <td>'.$phase->weights.'</td>
            <td colspan="2"></td>
            <td style="text-align:right">'.Html::a('修改',['update', 'id' => $phase->id], ['class' => $classUpdate]).' '.
                 Html::a('删除',['phase-delete', 'id' => $phase->id], ['class' => $classDeletee]).'</td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                /* @var $link CourseLink */
                echo '<tr>
                <td></td>
                <td colspan="2">'.$link->link->name.'</td>
                <td>'.$link->link->types[$link->link->type].'</td>
                <td>'.$link->link->unit.'</td>
                <td style="text-align:right">'.
                        Html::a('删除',['link-delete', 'id' => $link->id], ['class' => $classDeletee]).'</td>
                </tr>';
            }
        }
        ?>
        </tbody>
    </table>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['course/view','id' => $course_id], ['class' => 'btn btn-default']) ?>
        <?php
            /**
             * 新增 按钮显示必须满足以下条件：
             * 1、必须是【队长】
             * 2、创建者是自己
             */
            if($twTool->getIsLeader() && $model->course->create_by == Yii::$app->user->id)
               echo Html::a('新增', ['create', 'course_id' => $course_id], ['class' => 'btn btn-primary']) 
        ?>
        <?= Html::a('进度', ['progress', 'course_id' => $course_id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#course-manage-form').submit();
    });
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>