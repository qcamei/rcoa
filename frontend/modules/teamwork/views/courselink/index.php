<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
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
    
    <table class="table table-striped table-list">
        <thead>
            <tr>
              <th>阶段</th>
              <th>环节</th>
              <th>权重</th>
              <th>类型</th>
              <th>单位</th>
              <th style="width:75px">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($coursePhase as $phase) {
            /* @var $phase CoursePhase */
            echo '<tr>
            <td colspan="2">'.$phase->phase->name.'</td>
            <td>'.$phase->weights.'</td>
            <td colspan="2"></td>
            <td>'.Html::a('删除',['update', 'id' => $phase->id], ['class' => 'btn btn-danger']).'</td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                /* @var $link CourseLink */
                echo '<tr>
                <td></td>
                <td colspan="2">'.$link->link->name.'</td>
                <td>'.$link->link->types[$link->link->type].'</td>
                <td>'.$link->link->unit.'</td>
                <td>'.Html::a('删除',['update', 'id' => $link->id], ['class' => 'btn btn-danger']).'</td>
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
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-primary']) ?>
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