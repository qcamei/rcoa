function wx(url, element, text){
    $.post(url,function(data)
    {
        $('<option/>').val('').text(text).appendTo(element);
        $.each(data['data'],function()
        {
            $('<option>').val(this['id']).text(this['name']).appendTo(element);
        });
    });
}

/*var remove = '<span class="select2-selection__clear">×</span>';
    $('#select2-item_type_id').change(function(){
        $('.search-span').css('display', 'block');
        $('.search-input').css('width', '10%');
        $('#span-item_type_id').html();
        $('#span-item_type_id').addClass('inline-block-span').html($('#label-item_type_id').text()+'：'+$(this).find('option:selected').text()+remove);
    });
    $('#select2-item_id').change(function(){
        $('.search-span').css('display', 'block');
        $('.search-input').css('width', '10%');
        $('#span-item_id').html();
        $('#span-item_id').addClass('inline-block-span').html($('#label-item_id').text()+'：'+$(this).find('option:selected').text()+remove);
    });
    $('#select2-item_child_id').change(function(){
        $('.search-span').css('display', 'block');
        $('.search-input').css('width', '10%');
        $('#span-item_child_id').html();
        $('#span-item_child_id').addClass('inline-block-span').html($('#label-item_child_id').text()+'：'+$(this).find('option:selected').text()+remove);
    });*/
