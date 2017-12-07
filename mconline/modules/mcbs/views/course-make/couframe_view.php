<li id="{%id%}">
    <div class="head cou-default cou-{%frame_name%}">
        <?php if(strstr(Yii::$app->controller->action->id,'cou') == 'couphase'): ?>
        <a>
            <i class="fa fa-bars"></i>
            <span class="name">{%name%}</span>
            <span class="value_percent">{%value_percent%}</span>
        </a>
        <?php else: ?>
        <a href="#data-{%id%}" data-toggle="collapse" aria-expanded="true" onclick="replace($(this))">
            <i class="fa fa-minus-square-o"></i>
            <span class="name">{%name%}</span>
            <span class="value_percent">{%value_percent%}</span>
        </a>
        <?php endif; ?> 
        <div class="cou-icon">
            <a href="../course-make/create-cou{%sub_frame%}?{%frame_name%}_id={%id%}" onclick="couFrame($(this));return false;">
                <i class="fa fa-plus"></i>
            </a>
            <a href="../course-make/update-cou{%frame_name%}?id={%id%}" onclick="couFrame($(this));return false;">
                <i class="fa fa-pencil"></i>
            </a>
            <a href="../course-make/delete-cou{%frame_name%}?id={%id%}" onclick="couFrame($(this));return false;">
                <i class="fa fa-times"></i>
            </a>
            <a href="javascript:;" class="handle"><i class="fa fa-arrows"></i></a>
        </div>
    </div>
    <div id="data-{%id%}" class="collapse in" aria-expanded="true">
        <ul id="mcbs_course_{%sub_frame%}" class="sortable list cursor-move"></ul>
    </div>
</li>