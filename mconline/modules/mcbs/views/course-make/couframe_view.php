<?php

use yii\helpers\Html;

?>
<li id="{%id%}">
    <div class="head cou-default cou-{%frame_name%}">
        <?= Html::a("<i class=\"fa fa-minus-square-o\"></i>".
            "<span class=\"name\">{%name%}</span>".
            "<span class=\"name\">{%value_percent%}</span>",
            ['#data-{%id%}'],['data-toggle'=>'collapse']) 
        ?>
        <div class="cou-icon">
            <?= Html::a('<i class="fa fa-plus"></i>', 
                ['course-make/create-cou{%sub_frame%}','{%frame_name%}_id'=>'{%id%}'],
                ['onclick'=>'couFrame($(this));return false;'])
            ?>
            <?= Html::a('<i class="fa fa-pencil"></i>',
                ['course-make/update-cou{%frame_name%}','id'=>'{%id%}'],
                ['onclick'=>'couFrame($(this));return false;'])
            ?>
            <?= Html::a('<i class="fa fa-times"></i>',
                ['course-make/delete-cou{%frame_name%}','id'=>'{%id%}'],
                ['onclick'=>'couFrame($(this));return false;']) 
            ?>
            <?= Html::a('<i class="fa fa-arrows"></i>','javascript:;',['class'=>'handle']) ?>
        </div>
    </div>
    <div id="data-{%id%}">
        <ul class="sortable list cursor-move data-cou-{%sub_frame%}">
            
        </ul>
    </div>
</li>