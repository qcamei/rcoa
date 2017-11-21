<?php

use common\models\mconline\searchs\McbsCourseUserSearch;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel McbsCourseUserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Phase');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mcbs-couphase-index course-make-couframe">

    <ul class="sortable list cursor-move data-cou-phase">
        <?php foreach ($dataCouphase as $phase=>$couphase): ?>
        <li>
            <div class="cou-default cou-phase">
<!--                <i class="fa fa-plus-square-o"></i>-->
                <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>{$couphase['name']}（{$couphase['value_percent']}分）","#data-phase-{$phase}",['data-toggle'=>'collapse']) ?>
                <div class="cou-icon">
                    <?= Html::a('<i class="fa fa-plus"></i>',['course-make/create-coublock','phase_id'=>$couphase['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                    <?= Html::a('<i class="fa fa-pencil"></i>',['course-make/update-couphase','id'=>$couphase['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                    <?= Html::a('<i class="fa fa-times"></i>',['course-make/delete-couphase','id'=>$couphase['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                    <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                </div>
            </div>
            <div id="data-phase-<?= $phase ?>">
                <ul class="sortable list cursor-move data-cou-block">
                    <?php foreach($dataCoublock as $block=>$coublock): ?>
                    <?php if($coublock['phase_id'] == $couphase['id']): ?>
                    <li>
                        <div class="cou-default cou-block">
                            <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>{$coublock['name']}", "#data-block-{$block}",['data-toggle'=>'collapse']) ?>
                            <div class="cou-icon">
                                <?= Html::a('<i class="fa fa-plus"></i>',['course-make/create-couchapter','block_id'=>$coublock['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                <?= Html::a('<i class="fa fa-pencil"></i>',['course-make/update-coublock','id'=>$coublock['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                <?= Html::a('<i class="fa fa-times"></i>',['course-make/delete-coublock','id'=>$coublock['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                            </div>
                        </div>
                        <div id="data-block-<?= $block ?>">
                            <ul class="sortable  list cursor-move data-cou-chapter">
                                <?php foreach($dataCouchapter as $chapter=>$couchapter): ?>
                                <?php if($couchapter['block_id'] == $coublock['id']): ?>
                                <li>
                                    <div class="cou-default cou-chapter">
                                        <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>{$couchapter['name']}", "#data-chapter-{$chapter}",['data-toggle'=>'collapse']) ?>
                                        <div class="cou-icon">
                                            <?= Html::a('<i class="fa fa-plus"></i>',['course-make/create-cousection','chapter_id'=>$couchapter['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                            <?= Html::a('<i class="fa fa-pencil"></i>',['course-make/update-couchapter','id'=>$couchapter['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                            <?= Html::a('<i class="fa fa-times"></i>',['course-make/delete-couchapter','id'=>$couchapter['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                            <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                                        </div>
                                    </div>
                                    <div id="data-chapter-<?= $chapter ?>">
                                        <div class="cou-default chapter-des">
                                            <p><b>本章信息</b></p>
                                            <p><?= $couchapter['des'] ?></p>
                                        </div>
                                        <ul class="sortable  list cursor-move data-cou-section">
                                            <?php foreach($dataCousection as $section=>$cousection): ?>
                                            <?php if($cousection['chapter_id'] == $couchapter['id']): ?>
                                            <li>
                                                <div class="cou-default cou-section">
                                                    <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>{$cousection['name']}", "#data-activity-{$section}",['data-toggle'=>'collapse']) ?>
                                                    <div class="cou-icon">
                                                        <?= Html::a('<i class="fa fa-plus"></i>',['course-make/create-couactivity','section_id'=>$cousection['id']]) ?>
                                                        <?= Html::a('<i class="fa fa-pencil"></i>',['course-make/update-cousection','id'=>$cousection['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                                        <?= Html::a('<i class="fa fa-times"></i>',['course-make/delete-cousection','id'=>$cousection['id']],['onclick'=>'couFrame($(this));return false;']) ?>
                                                        <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                                                    </div>
                                                </div>
                                                <div id="data-activity-<?= $section ?>">
                                                    <ul class="sortable  list cursor-move data-cou-activity cou-default cou-activity">
                                                        <li>
                                                            <div class="act-content">
                                                                <i class="acttype"></i>
                                                                <span class="actname">【课程导学】：怎样学好</span>
                                                                <div class="cou-icon">
                                                                    <?= Html::a('<i class="fa fa-eye"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-pencil"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-times"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="act-content">
                                                                <i class="acttype"></i>
                                                                <span class="actname">【模拟测验】：怎样学好</span>
                                                                <div class="cou-icon">
                                                                    <?= Html::a('<i class="fa fa-eye"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-pencil"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-times"></i>') ?>
                                                                    <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript',['class'=>'handle']) ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </li>
        <?php endforeach; ?>
        <li class="disabled">
            <div class="cou-default cou-phase">
                <center>
                    <?= Html::a('<i class="fa fa-plus-square"></i>&nbsp;&nbsp;'.Yii::t(null, '{add}{phase}',[
                        'add' => Yii::t('app', 'Add'),
                        'phase' => Yii::t('app', 'Phase')
                    ]),['course-make/create-couphase', 'course_id'=>$course_id],['onclick'=>'couFrame($(this));return false;']) ?>
                </center>
            </div>
        </li>  
    </ul>
       
</div>

<?php
$js = 
<<<JS
        
    sortable('.data-cou-phase', {
        forcePlaceholderSize: true,
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        handle: '.fa-arrows',
	items: 'li',
        //placeholderClass: 'border border-orange mb1'
    });
    sortable('.data-cou-block', {
        forcePlaceholderSize: true,
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        handle: '.fa-arrows',
	items: 'li',
        //placeholderClass: 'border border-maroon mb1'
    });
    sortable('.data-cou-chapter', {
        forcePlaceholderSize: true,
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        handle: '.fa-arrows',
	items: 'li',
        //placeholderClass: 'border border-maroon mb1'
    });
    sortable('.data-cou-section', {
        forcePlaceholderSize: true,
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        handle: '.fa-arrows',
	items: 'li',
        //placeholderClass: 'border border-maroon mb1'
    });
    sortable('.data-cou-activity', {
        forcePlaceholderSize: true,
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        handle: '.fa-arrows',
	items: 'li',
        //placeholderClass: 'border border-maroon mb1'
    });
   
//    document.querySelector('.sortable').addEventListener('sortupdate', function(evt){
//        console.log('Index: '+evt.detail.oldindex+' -> '+evt.detail.index);
//    });
//    document.querySelector('.sortable-inner').addEventListener('sortupdate', function(evt){
//        console.log('Index: '+evt.detail.oldindex+' -> '+evt.detail.index);
//    });
        
    //课程框架弹出框
    function couFrame(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
    }

JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>