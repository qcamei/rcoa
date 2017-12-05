<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Phase');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mcbs-couphase-index course-make-couframe">
   <!--阶段-->
   <ul id="mcbs_course_phase" class="sortable list cursor-move">
        <?php foreach ($dataCouphase as $couphase): ?>

        <li id="<?= $couphase['id'] ?>">
            <div class="head cou-default cou-phase">

                <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                   "<span class=\"name\">{$couphase['name']}</span>".
                   "<span class=\"value_percent\">（{$couphase['value_percent']}分）</span>",
                   "#data-{$couphase['id']}",['data-toggle'=>'collapse', 'aria-expanded'=> 'true', 'onclick'=>'replace($(this))']) 
                ?>
                <div class="cou-icon">
                <?php if($isPermission): ?>
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
                 <?php endif; ?>
                </div>
            </div>
            <div id="data-<?= $couphase['id'] ?>" class="collapse in" aria-expanded="true">
                <!--区块-->
                <ul id="mcbs_course_block" class="sortable list cursor-move">
                    
                    <?php foreach($dataCoublock as $coublock): ?>
                    <?php if($coublock['phase_id'] == $couphase['id']): ?>

                    <li id="<?= $coublock['id'] ?>">
                        <div class="head cou-default cou-block">

                            <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                                "<span class=\"name\">{$coublock['name']}</span>",
                                "#data-{$coublock['id']}",['data-toggle'=>'collapse','aria-expanded'=> 'true','onclick'=>'replace($(this))']) 
                            ?>
                            <div class="cou-icon">
                            <?php if($isPermission): ?>
                                <?= Html::a('<i class="fa fa-plus"></i>',
                                    ['course-make/create-couchapter','block_id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-pencil"></i>',
                                    ['course-make/update-coublock','id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-times"></i>',
                                    ['course-make/delete-coublock','id'=>$coublock['id']],
                                    ['onclick'=>'couFrame($(this));return false;']) 
                                ?>
                                <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                            <?php endif; ?>
                            </div>

                        </div>
                        <div id="data-<?= $coublock['id'] ?>" class="collapse in" aria-expanded="true">
                            <!--章-->
                            <ul id="mcbs_course_chapter" class="sortable  list cursor-move">
                                
                                <?php foreach($dataCouchapter as $couchapter): ?>
                                <?php if($couchapter['block_id'] == $coublock['id']): ?>
                                
                                <li id="<?= $couchapter['id'] ?>">
                                    <div class="head cou-default cou-chapter">

                                        <?= Html::a("<i class=\"fa fa-plus-square-o\"></i>".
                                            "<span class=\"name\">{$couchapter['name']}</span>",
                                            "#data-{$couchapter['id']}",['data-toggle'=>'collapse','aria-expanded'=> 'false','onclick'=>'replace($(this))']) 
                                        ?>
                                        <div class="cou-icon">
                                        <?php if($isPermission): ?>
                                            <?= Html::a('<i class="fa fa-plus"></i>',
                                                ['course-make/create-cousection','chapter_id'=>$couchapter['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-pencil"></i>',
                                                ['course-make/update-couchapter','id'=>$couchapter['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-times"></i>',
                                                ['course-make/delete-couchapter','id'=>$couchapter['id']],
                                                ['onclick'=>'couFrame($(this));return false;']) 
                                            ?>
                                            <?= Html::a('<i class="fa fa-arrows"></i>', 'javascript:;',['class'=>'handle']) ?>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                    <div id="data-<?= $couchapter['id'] ?>" class="collapse" aria-expanded="false" style="height:0px;">
                                        <div class="cou-default chapter-des">
                                            <p><b>本章信息</b></p>
                                            <p class="des"><?= $couchapter['des'] ?></p>
                                        </div>
                                        <!--节-->
                                        <ul id="mcbs_course_section" class="sortable list cursor-move">

                                            <?php foreach($dataCousection as $cousection): ?>
                                            <?php if($cousection['chapter_id'] == $couchapter['id']): ?>

                                            <li id="<?= $cousection['id'] ?>">
                                                <div class="head cou-default cou-section">
                                                    <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
                                                        "<span class=\"name\">{$cousection['name']}</span>",
                                                        "#data-{$cousection['id']}",['data-toggle'=>'collapse','aria-expanded'=> 'true','onclick'=>'replace($(this))']) 
                                                    ?>
                                                    <div class="cou-icon">
                                                    <?php if($isPermission): ?>
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
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div id="data-<?= $cousection['id'] ?>" class="collapse in" aria-expanded="true">
                                                    <!--活动-->
                                                    <ul id="mcbs_course_activity" class="sortable list cursor-move">

                                                        <?php foreach($dataCouactivity as $couactivity): ?>
                                                        <?php if($couactivity['section_id'] == $cousection['id']): ?>
                                                        
                                                        <li id="<?= $couactivity['id'] ?>">
                                                            <div class="head cou-default cou-activity">
                                                                <?= Html::a(Html::img([$couactivity['icon_path']],['width'=>25,'height'=>25,'class'=>'icon_path']).
                                                                    "<span class=\"type_name\">【{$couactivity['type_name']}】：</span>".
                                                                    "<span class=\"name\">{$couactivity['name']}</span>") 
                                                                ?>
                                                                <div class="cou-icon">
                                                                    <?= Html::a('<i class="fa fa-eye"></i>',
                                                                        ['course-make/couactivity-view','id'=>$couactivity['id']])
                                                                    ?>
                                                                <?php if($isPermission): ?>
                                                                    <?= Html::a('<i class="fa fa-pencil"></i>',
                                                                        ['course-make/update-couactivity','id'=>$couactivity['id']],
                                                                        ['onclick'=>'couFrame($(this));return false;']) 
                                                                    ?>
                                                                    <?= Html::a('<i class="fa fa-times"></i>',
                                                                        ['course-make/delete-couactivity','id'=>$couactivity['id']],
                                                                        ['onclick'=>'couFrame($(this));return false;']) 
                                                                    ?>
                                                                    <?= Html::a('<i class="fa fa-arrows"></i>', 
                                                                        'javascript:;',
                                                                        ['class'=>'handle', 'mousedown'=>'moves($(this))']) 
                                                                    ?>
                                                                <?php endif; ?>
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
    </ul>
    <?php if($isPermission): ?>
    <ul class="sortable list cursor-move">
        <li>
            <div class="cou-default cou-phase">
                <center>
                    <?= Html::a('<i class="fa fa-plus-square"></i>&nbsp;&nbsp;'.Yii::t(null, '{add}{phase}',[
                            'add' => Yii::t('app', 'Add'),
                            'phase' => Yii::t('app', 'Phase')
                        ]),['course-make/create-couphase', 'course_id'=>$course_id],['onclick'=>'couFrame($(this));return false;']) 
                    ?>
                </center>
            </div>
        </li>
    </ul>
   <?php endif; ?>
</div>

<?php

$actlog = Url::to(['course-make/log-index', 'course_id' => $course_id]);

$js = 
<<<JS
    //初始化组件
    sortable('.sortable', {
        forcePlaceholderSize: true,
        handle: '.fa-arrows',
	items: 'li',
        //items: ':not(.disabled)',
        //connectWith: '.data-cou-phase',
        //placeholderClass: 'border border-orange mb1'
    });
    //提交更改顺序
    $(".sortable").each(function(i,e){
        //var tableName = e.attr("id");
        e.addEventListener('sortupdate', function(evt){
            var oldList = evt.detail.oldStartList,
                newList = evt.detail.newEndList,
                oldIndexs = {},
                newIndexs = {};
            $.each(oldList,function(index,item){
                if(newList[index] != item){
                    oldIndexs[$(item).attr('id')] = index
                }
            });
            $.each(newList,function(index,item){
                if(oldList[index] != item){
                    newIndexs[$(item).attr('id')] = index;
                }
            });
            
            $.post("/mcbs/course-make/sort-order",
                {"tableName":e.id,"oldIndexs":oldIndexs,"newIndexs":newIndexs,"course_id":"$course_id"},
            function(data){
                if(data['code'] == '200'){
                    $("#action-log").load("$actlog");
                }else{
                    alert("顺序调整失败");
                }
            });
        });
    }); 
    

    //课程框架弹出框
    function couFrame(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
    }
    //替换图标
    function replace(elem){
        if(elem.attr("aria-expanded") == 'true')
            elem.children('i').removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
        else
            elem.children('i').removeClass("fa-plus-square-o").addClass("fa-minus-square-o");
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>