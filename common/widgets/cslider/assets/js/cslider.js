/* 
 * v1.0.0
 * 进度条
 * author wskeee
 * time 2017-04-19 10:46
 */
(function(win,$){
    
    var CSlider = function(config){
        this.config = $.extend({
            id:'#cslider'+Math.round(Math.random()*100000),
            height: 10,
            min: 0,
            max: 1,                     //最大值
            step: 0.1,
            value: 0,                   //当前值 >= min  && <= max
            valueText: null,            //指定显示文字
            trackColor: '#ddd',         //滑动条底色块
            sliderColor: '#428BCA',     //已选择颜色
            tooltipColor: '#000',       //提示颜色
        },config);
        
        this.__init();
        this.__createChild();
        //this.reflash();
        var _this = this;
        this.reflashID = setTimeout(function(){
            _this.reflash.call(_this);
        },10);
        
    };
    
    var p = CSlider.prototype;
    
    /**
     * 参数过滤
     * @returns {void}
     */
    p.__init = function(){
        var v = this.config['value'];
        if(v<this.config['min'])v=this.config['min'];
        if(v>this.config['max'])v=this.config['max'];
        this.config['value'] = v;
    };
    
    /**
     * 创建Dom
     * @returns {String Dom}
     */
    p.__createChild = function(){
        
        this.ideui = $('#'+this.config['id']);
        
        //滑动条容器
        this.track = $('<div class="c-slider-track"></div>');
        //已选择
        this.selection = $('<div class="c-slider-selection"></div>');
        //提示容器
        this.tooltip = $('<div class="c-tootip"></div>');
        //提示文本
        this.tooltipTxt = $('<div class="c-tooltip-inner"></div>');
        //提示箭头
        this.tooltipArrow = $('<div class="c-tooltip-arrow"></div>');
        
        this.track.append(this.selection);
        this.tooltip.append(this.tooltipTxt);
        this.tooltip.append(this.tooltipArrow);
        this.ideui.append(this.track);
        this.ideui.append(this.tooltip);
        this.ideui.addClass('c-slider');
    };
    
    /**
     * 更新 Dom 显示
     * @returns {void}
     */
    p.reflash = function(){
        clearTimeout(this.reflashID);
        
        var left = this.config['value'] / (this.config['max'] - this.config['min']) * 100 +'%';
        var width = this.config['width'] ? this.config['width'] + 'px' : null;
        
        //显示提示文字
        this.tooltipTxt.html(this.config.valueText ? this.config.valueText : this.config.value);
        
        //修改已选择样式：颜色、宽
        this.selection.css({
            'background-color': this.config['sliderColor'],
            'width': left
        });
        //修改提示箭头颜色
        this.tooltipArrow.css({
            'border-top-color': this.config['tooltipColor']
        });
        //修改提示文字背景颜色
        this.tooltipTxt.css({
            'background-color': this.config['tooltipColor'],
        });
        
        if(this.tooltip.width() == 0)
        {
            //console.log('render late!',this.tooltip.css('display'));
            var _this = this;
            this.reflashID = setTimeout(function(){
                _this.reflash.call(_this);
            },100);
        }
        //console.log(this.tooltip.width());
        //修改提示位置
        this.tooltip.css({
            'left': left,
            'margin-left': -this.tooltip.width()/2+'px',
            'bottom': this.config.height + 3 +'px',
            'opacity': 1,
        });
        
        //更新高度
        this.ideui.css({'height':this.config['height']+'px','width':width,'margin-top': this.config['height'] + this.tooltip.height() + 3 + "px"});
    }
    
    
    win.ccoa_Widgets_CSlider = CSlider;
    
})(window,jQuery);

