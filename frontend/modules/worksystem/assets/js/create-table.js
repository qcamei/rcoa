/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/** 提交生成表格 */
window.create_table = function(){
    $('#submit-save').click(function(){
        var value = $('#Worksystemcontent-type_name').val(),
            is_new = $('#Worksystemcontent-is_new input[name="WorksystemContent[is_new]"]:checked').val(),
            text = $('#Worksystemcontent-is_new input[name="WorksystemContent[is_new]"]:checked').parent().text(),
            tab = '',
            is_unique = value+'_'+is_new;       
        var url = "/worksystem/contentinfo/index";
        $.post(url, {content_id:value}, function(data){
            if(data['type'] != 1){
                $('#prompt').html('');
                $('#prompt').html('<span class="error-warn">'+data['message']+'</span>');
                return;
            }
            var dataId = data['data']['id']+'_'+is_new;
            var is_return = true;
            $('.table tbody tr').each(function(){
                if($(this).attr('id') == is_unique){
                    $('#prompt').html('<span class="error-warn">所选择的类型已存在！</span>');
                    is_return = false;
                }
            });
            if(is_return == false)
                return;
            if(is_new == 1){
                tab = '<tr id="'+dataId+'">'+
                    '<td class="course-name">'+data['data']['type_name']+'<input type="hidden" name="WorksystemContentinfo['+dataId+'][worksystem_content_id]" value="'+data['data']['id']+'" /></td>'+
                    '<td>'+text+'<input type="hidden" name="WorksystemContentinfo['+dataId+'][is_new]" value="'+is_new+'" /></td>'+
                    '<td><input type="number" name="WorksystemContentinfo['+dataId+'][price]" id="Worksystemcontentinfo-price-'+dataId+'" class="price" value="'+data['data']['price_new']+'" onfocus ="show($(this));" onblur="infoCost(); hide($(this))" /><span class="reference hidden-xs">（￥'+data['data']['price_new']+'/'+data['data']['unit']+'）</span><div class="worksystem-tooltip" data-toggle="tooltip" data-placement="bottom" title="￥'+data['data']['price_new']+'/'+data['data']['unit']+'"></div></td>'+
                    '<td><input type="number" name="WorksystemContentinfo['+dataId+'][budget_number]" id="Worksystemcontentinfo-budget_number-'+dataId+'" class="number" value="0" onfocus ="show($(this));" onblur="infoCost(); hide($(this))" /><span class="reference hidden-xs">（'+data['data']['unit']+'）</span><div class="worksystem-tooltip" data-toggle="tooltip" data-placement="bottom" title="'+data['data']['unit']+'"></div></td>'+
                    '<td class="hidden-xs">￥<span id="Worksystemcontentinfo-budget_cost-number-'+dataId+'">0.00</span><input type="hidden" name="WorksystemContentinfo['+dataId+'][budget_cost]" id="Worksystemcontentinfo-budget_cost-cost-'+dataId+'" class="info-cost" value="0" /></td>'+
                    '<td><a class="btn btn-danger btn-sm" onclick="removeAttr($(this))">删除</a></td>'+
                 '</tr>';
            }else{
                 tab = '<tr id="'+dataId+'">'+
                    '<td class="course-name">'+data['data']['type_name']+'<input type="hidden" name="WorksystemContentinfo['+dataId+'][worksystem_content_id]" value="'+data['data']['id']+'" /></td>'+
                    '<td>'+text+'<input type="hidden" name="WorksystemContentinfo['+dataId+'][is_new]" value="'+is_new+'" /></td>'+
                    '<td><input type="number" name="WorksystemContentinfo['+dataId+'][price]" id="Worksystemcontentinfo-price-'+dataId+'" class="price" value="'+data['data']['price_remould']+'" onfocus ="show($(this));" onblur="infoCost(); hide($(this))" /><span class="reference hidden-xs">（￥'+data['data']['price_remould']+'/'+data['data']['unit']+'）</span><div class="worksystem-tooltip" data-toggle="tooltip" data-placement="bottom" title="￥'+data['data']['price_remould']+'/'+data['data']['unit']+'"></div></td>'+
                    '<td><input type="number" name="WorksystemContentinfo['+dataId+'][budget_number]" id="Worksystemcontentinfo-budget_number-'+dataId+'" class="number" value="0" onfocus ="show($(this));" onblur="infoCost(); hide($(this))" /><span class="reference hidden-xs">（'+data['data']['unit']+'）</span><div class="worksystem-tooltip" data-toggle="tooltip" data-placement="bottom" title="'+data['data']['unit']+'"></div></td>'+
                    '<td class="hidden-xs">￥<span id="Worksystemcontentinfo-budget_cost-number-'+dataId+'">0.00</span><input type="hidden" name="WorksystemContentinfo['+dataId+'][budget_cost]" id="Worksystemcontentinfo-budget_cost-cost-'+dataId+'" class="info-cost" value="0" /></td>'+
                    '<td><a class="btn btn-danger btn-sm" onclick="removeAttr($(this))">删除</a></td>'+
                 '</tr>';
            }
            $('.contentinfo-table .table tbody').append(tab);
            $('#prompt').html('');
        });
    });
}