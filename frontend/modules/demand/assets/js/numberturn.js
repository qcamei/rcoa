/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



    $.fn.countTo = function (options) {
            options = options || {};

            return $(this).each(function () {
                    // 设置当前元素的选项 
                    //extend()该方法就是将src合并到jquery的全局对象中去
                    var settings = $.extend({}, $.fn.countTo.defaults, {
                            from:            $(this).data('from'),
                            to:              $(this).data('to'),
                            speed:           $(this).data('speed'),
                            refreshInterval: $(this).data('refresh-interval'),
                            decimals:        $(this).data('decimals')
                    }, options);

                    
                    var loops = Math.ceil(settings.speed / settings.refreshInterval),   //更新值多少次
                            increment = (settings.to - settings.from) / loops;          //每次更新的增量值是多少
                        
                        
                    // 参考和变量，将改变每个更新
                    var self = this,            //设置对象
                            $self = $(this),    //设置对象
                            loopCount = 0,      //设置自增值
                            value = settings.from,  //设置起始值
                            data = $self.data('countTo') || {}; //向被选元素附加数据
                    
                    $self.data('countTo', data);
                    
                    // 如果发现现有的间隔，首先清除它
                    if (data.interval) {
                        clearInterval(data.interval);
                    }
                    
                    data.interval = setInterval(updateTimer, settings.refreshInterval);
                    data.render = render;
                    
                    // 初始化具有起始值的元素
                    render(value);
                    
                    function updateTimer() {
                            value += increment;    //每次更新后的增量值 
                            loopCount++;
                            
                            render(value);
                            show(value);
                            
                            //typeof 判断其是否是数组 ** 没什么用处 **
                            if (typeof(settings.onUpdate) == 'function') {
                                    settings.onUpdate.call(self, value);
                            }
                        
                            if (loopCount >= loops) {
                                    //删除间隔
                                    $self.removeData('countTo'); //从元素中删除之前添加的数据
                                    clearInterval(data.interval);   //停止更新数值
                                    value = settings.to;
                                    if (typeof(settings.onComplete) == 'function') {
                                            settings.onComplete.call(self, value);
                                    }
                            }
                    }

                    function render(value) {
                            //formatter格式化数据表格列
                            //call调用一个对象的一个方法，以另一个对象替换当前对象
                            var formattedValue = settings.formatter.call(self, value, settings);
                            $self.html(number_format(formattedValue, '', '', ','));
                    }
                    
                    function show(loopCount,  loops, value){
                           if(loopCount >= loops && value == 999)
                               return $self.next().fadeTo(200, 1);
                    }
                    
                    
                    
            });
    };

    $.fn.countTo.defaults = {
            from: 0,               // 设置元素的起始数值
            to: 0,                 // 设置元素的结束数值
            speed: 1000,           // 要在目标数之间计算多长时间
            refreshInterval: 10 ,  // 元素应该更新多久
            decimals: 0,           // 要显示的小数位数
            formatter: formatter, // 渲染前格式化值的处理程序
            onUpdate: null,        // 每次更新元素的回调方法
            onComplete: null       // 当元素完成更新时的回调方法
    };

    function formatter(value, settings) {
            return value.toFixed(settings.decimals);
    }



  // 自定义格式的例子
  $('#count-number').data('countToOptions', {
	formatter: function (value, options) {
	  return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
	}
  });
  
  //$('.timer').each(count);  // 启动所有定时器
  
  function count(options) {
	var $this = $(this);
	options = $.extend({}, options || {}, $this.data('countToOptions') || {});
	$this.countTo(options);
  }
  
 function stop(){
    var $this = $(this).data('countTo') || {};   
    if ($this.interval) {
        clearInterval($this.interval);
    }
 }
 

//数字格式化
 function number_format(number, decimals, dec_point, thousands_sep) {  
    var n = !isFinite(+number) ? 0 : +number,  
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),  
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,  
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,  
        s = '',  
        toFixedFix = function (n, prec) {  
            var k = Math.pow(10, prec);  
            return '' + Math.round(n * k) / k;        };  
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;  
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');  
    if (s[0].length > 3) {  
        s[0] = s[0].replace(/(\d)(?=(?:\d{3})+$)/g, '$1'+sep);  
    }  
    if ((s[1] || '').length < prec) {  
        s[1] = s[1] || '';  
        s[1] += new Array(prec - s[1].length + 1).join('0');  
    }      
    return s.join(dec);  
}
