/** 加载已经添加的产品 */
function productList(url, obj){
    $.get(url, function(data){
        if(data.type == 1){
            $.each(data.data, function(index, value){
                var dataHtml = '<div class="product-list">'+
                    '<a data_t="'+value['task_id']+'" data_p="'+value['product_id']+'" onclick="viewproduct($(this));">'+
                    '<div class="product-list-header"><img src="'+value['image']+'"></div>'+
                    '<div class="product-list-body"><p>【'+value['name']+'】</p><p class="des">'+value['des']+'</p><p class="price">'+
                    value['currency']+value['unit_price']+'<span class="number">×'+value['number']+'</span></p></div></a>'+
                    '<div class="product-list-footer">'+
                    '<a class="btn btn-danger btn-sm" data_t="'+value['task_id']+'" data_p="'+value['product_id']+'" onclick="deleteproduct($(this));">删除</a>'+
                    '</div></div>';  

                $(dataHtml).appendTo(obj);
            });
        }
    });
}
