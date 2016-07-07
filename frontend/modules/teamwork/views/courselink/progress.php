<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Progress');
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
              <th>总量</th>
              <th>已完成</th>
              <th></th>
              <th style="width:95px">进度</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($coursePhase as $phase) {
            /* @var $phase CoursePhase */
            echo '<tr>
            <td colspan="2">'.$phase->phase->name.'</td>
            <td></td>
            <td colspan="2"></td>
            <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar progress-bar-success', //'progress-bar-danger',
                                    'role' => 'progressbar', 
                                    'style' => 'width:50%',
                                ]).
                                '50%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                //var_dump($link);
                /* @var $link CourseLink */
                echo '<tr>
                <td></td>
                <td>'.$link->link->name.'</td>
                <td>'.$link->total.'</td>
                <td>'.$link->completed.'</td>
                <td></td>
                <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => (($link->completed / $link->total) * 100) != 100 ? 'progress-bar ' : 'progress-bar progress-bar-danger', 
                                    'style' => 'width:'.(($link->completed / $link->total) * 100).'%',
                                ]).
                                (($link->completed / $link->total) * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td>
                </tr>';
            }
        }
        //exit;
        ?>
        </tbody>
    </table>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['course/view','id' => $course_id], ['class' => 'btn btn-default']) ?>
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