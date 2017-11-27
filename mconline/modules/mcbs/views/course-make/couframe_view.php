<li>
    <div class="cou-default cou-{%frame%}">
        <a href="#data-{%frame%}-{%id%}"><i class="fa fa-minus-square-o"></i>{%name%}{%is_null%}</a>
        <div class="cou-icon">
            <a href="../course-make/create-cou{%subframe%}?{%frame%}_id={%id%}" onclick="couFrame($(this));return false;"><i class="fa fa-plus"></i></a>
            <a href="../course-make/update-{%action%}?id={%id%}" onclick="couFrame($(this));return false;"><i class="fa fa-pencil"></i></a>
            <a href="../course-make/delete-{%action%}?id={%id%}" onclick="couFrame($(this));return false;"><i class="fa fa-times"></i></a>
            <a href="javascript:;" class="handle"><i class="fa fa-arrows"></i></a>
        </div>
    </div>
    <div id="data-{%frame%}-{%id%}">
        <ul class="sortable list cursor-move data-cou-{%subframe%}">
            
        </ul>
    </div>
</li>