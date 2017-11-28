<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Phase');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mcbs-couphase-index course-make-couframe">

   <ul class="sortable list cursor-move data-cou-phase">
        <?php foreach ($dataCouphase as $couphase): ?>

        <li id="<?= $couphase['id'] ?>">
            <div class="head cou-default cou-phase">

                <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                   "<span class=\"name\">{$couphase['name']}</span>".
                   "<span class=\"value_percent\">（{$couphase['value_percent']}分）</span>",
                   "#data-{$couphase['id']}",['data-toggle'=>'collapse']) 
                ?>
                <div class="cou-icon">
                    <?= Html::a('<i class="fa fa-plus"></i>',
                        ['course-make/create-coublock','phase_id'=>$couphase['id']],
                        ['onclick'=>'couFrame($(this));return false;']) 
                    ?>
                    <?= Html::a('<i class="fa fa-pencil"></i>',
                        ['course-make/update-couphase','id'=>$couphase['id']],
                        ['onclick'=>'couFrame($(this));return false;']) 
                    ?>
                    <?= Html::a('<i class="fa fa-times"></i>',
                        ['course-make/delete-couphase','id'=>$couphase['id']],
                        ['onclick'=>'couFrame($(this));return false;']) 
                    ?>
                    <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                </div>
            </div>
            <div id="data-<?= $couphase['id'] ?>">
                <ul class="sortable list cursor-move data-cou-block">
                    
                    <?php foreach($dataCoublock as $coublock): ?>
                    <?php if($coublock['phase_id'] == $couphase['id']): ?>

                    <li id="<?= $coublock['id'] ?>">
                        <div class="head cou-default cou-block">

                            <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                                "<span class=\"name\">{$coublock['name']}</span>",
                                "#data-{$coublock['id']}",['data-toggle'=>'collapse']) 
                            ?>
                            <div class="cou-icon">
                                <?= Html::a('<i class="fa fa-plus"></i>',
                                    ['course-make/create-couchapter','block_id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-pencil"></i>',
                                    ['course-make/update-couphase','id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-times"></i>',
                                    ['course-make/delete-couphase','id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                            </div>

                        </div>
                        <div id="data-<?= $coublock['id'] ?>">
                            <ul class="sortable  list cursor-move data-cou-chapter">
                                
                                <?php foreach($dataCouchapter as $couchapter): ?>
                                <?php if($couchapter['block_id'] == $coublock['id']): ?>
                                
                                <li id="<?= $couchapter['id'] ?>">
                                    <div class="head cou-default cou-chapter">

                                        <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                                            "<span class=\"name\">{$couchapter['name']}</span>",
                                            "#data-{$couchapter['id']}",['data-toggle'=>'collapse']) 
                                        ?>
                                        <div class="cou-icon">
                                            <?= Html::a('<i class="fa fa-plus"></i>',
                                                ['course-make/create-cousection','chapter_id'=>$couchapter['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-pencil"></i>',
                                                ['course-make/update-couphase','id'=>$couphase['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-times"></i>',
                                                ['course-make/delete-couphase','id'=>$couphase['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                                        </div>
                                        <div id="data-<?= $couchapter['id'] ?>" class="collapse" aria-expanded="false" style="height:0px;">
                                            <div class="cou-default chapter-des">
                                                <p><b>本章信息</b></p>
                                                <p class="des"><?= $couchapter['des'] ?></p>
                                            </div>
                                            <ul class="sortable  list cursor-move data-cou-section">
                                                
                                                <?php foreach($dataCousection as $cousection): ?>
                                                <?php if($cousection['chapter_id'] == $couchapter['id']): ?>
                                                
                                                <li id="<?= $cousection['id'] ?>">
                                                    <div class="head cou-default cou-section">
                                                        <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                                                            "<span class=\"name\">{$cousection['name']}</span>",
                                                            "#data-{$cousection['id']}",['data-toggle'=>'collapse']) 
                                                        ?>
                                                        <div class="cou-icon">
                                                            <?= Html::a('<i class="fa fa-plus"></i>',
                                                                ['course-make/create-couactivity','section_id'=>$cousection['id']],
                                                                ['onclick'=>'couFrame($(this));return false;']) 
                                                            ?>
                                                            <?= Html::a('<i class="fa fa-pencil"></i>',
                                                                ['course-make/update-cousection','id'=>$cousection['id']],
                                                                ['onclick'=>'couFrame($(this));return false;']) 
                                                            ?>
                                                            <?= Html::a('<i class="fa fa-times"></i>',
                                                                ['course-make/delete-cousection','id'=>$cousection['id']],
                                                                ['onclick'=>'couFrame($(this));return false;']) 
                                                            ?>
                                                            <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                                                        </div>
                                                    </div>
                                                    <div id="data-<?= $cousection['id'] ?>">
                                                    
                                                        <ul class="sortable  list cursor-move data-cou-activity cou-default cou-activity">
                                                            
                                                            <?php foreach($dataCouactivity as $couactivity): ?>
                                                            <?php if($couactivity['section_id'] == $cousection['id']): ?>
                                                            
                                                            <li id="<?= $couactivity['id'] ?>">
                                                                <div class="act-content">
                                                                    <i class="<?= $couactivity['iocn_type'] ?>"></i>
                                                                    <span class="actname name"><?= "【{$couactivity['sect_name']}】：{$couactivity['name']}" ?></span>
                                                                    <div class="cou-icon">
                                                                        <?= Html::a('<i class="fa fa-eye"></i>',
                                                                            ['course-make/couactivity-view','id'=>$couactivity['id']]) 
                                                                        ?>
                                                                        <?= Html::a('<i class="fa fa-pencil"></i>',
                                                                            ['course-make/update-couactivity','id'=>$couactivity['id']]) 
                                                                        ?>
                                                                        <?= Html::a('<i class="fa fa-times"></i>',
                                                                            ['course-make/delete-couactivity','id'=>$couactivity['id']],
                                                                            ['onclick'=>'couFrame($(this));return false;']) 
                                                                        ?>
                                                                        <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                                                                    </div>
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

                                    </div>
                                
                                
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
    </ul>
    
    <ul class="sortable list cursor-move">
        <li>
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
	items: 'li'
        //placeholderClass: 'border border-maroon mb1'
    });
    
//    document.querySelector('.fa-arrows').addEventListener('sortupdate', function(evt){
//        console.log('Index: '+evt.detail.oldindex+' -> '+evt.detail.index);
//    });
//    document.querySelector('.data-cou-phase').addEventListener('sortupdate', function(evt){
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