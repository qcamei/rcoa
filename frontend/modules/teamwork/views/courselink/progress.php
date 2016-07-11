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
    
    <table class="table table-list">
        <thead>
            <tr style="background-color:#eee">
              <th style="width:15%">阶段</th>
              <th style="width:25%">环节</th>
              <th style="width:15%">总量</th>
              <th style="width:15%">已完成</th>
              <th style="width:10%">进度</th>
              <th style="width:15%"></th>
              <th style="width:5%">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($coursePhase as $phase) {
            $className = $twTool->getIsLeader() && $phase->course->create_by == Yii::$app->user->id ?
                        'btn btn-primary' : 'btn btn-primary disabled';
            /* @var $phase CoursePhase */
            echo '<tr style="background-color:#eee">
            <td colspan="2">'.$phase->phase->name.'</td>
            <td colspan="2"></td>
            <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar progress-bar-danger',
                                    'style' => 'width:'.(int)($phase->progress * 100).'%',
                                ]).
                                (int)($phase->progress * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td><td></td>
            <td></td>
            </tr>';
            foreach ($phase->courseLinks as $link) {
                /* @var $link CourseLink */
                echo '<tr>
                <td></td>
                <td>'.$link->link->name.'</td>
                <td>'.$link->total.'</td>
                <td>'.$link->completed.'</td>
                <td>'.Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'style' => 'width:'.($link->completed / $link->total * 100).'%',
                                ]).
                                ($link->completed / $link->total * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div').'</td><td></td>
                <td>'.Html::a('录入',['entry', 'id' => $link->id], ['class' => $className]).'</td>
                </tr>';
            }
        } ?>
        
        </tbody>
    </table>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['course/view','id' => $course_id], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div id="myModal" class="fade modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content has-title">
            
        </div>
    </div>
</div>
<?php
$js = 
<<<JS
    $('.btn-primary').click(function(){
        var urlf = $(this).attr("href");
        $("#myModal").modal({remote:urlf});
        return false;
    });
    $('#myModal').on('loaded.bs.modal', function () {
        $('.carousel').carousel('pause');
        $('#myModal #submit').click(function()
        {
            $('#entry-manage-form').submit();
        });
        $("#myModal .modal-header .close").click(function(){
            window.location.reload();
        });
    });
    /** 隐藏之后触发该事件*/
    $('#myModal').on('hidden.bs.modal', function () {
        window.location.reload();
    });
        
    
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>