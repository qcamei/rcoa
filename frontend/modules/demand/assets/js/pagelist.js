(function(win,$){
    var Wskeee = win.Wskeee = win.Wskeee || {};
    Wskeee.demand = Wskeee.demand || {};
    
    var PageList = function(config){
        //配置
        this.config = $.extend({
             //根容器
            container:'#e-pl',
            //返回按钮
            comeback:'#pl-comeback',
            //目录(里面包含子内容)渲染
            dirRender:'<div class="pl-item pl-dir-render" data="{%id%}"><div class="pl-head"><span class="icon pl-dir-icon"/></div><div class="pl-body"><p class="pl-item-name">{%name%}</p><p class="pl-item-des">{%des%}</p></div><div class="pl-footer"><span class="pl-open"/></div></div>',
            //内容层渲染
            contentRender:'<div class="pl-item pl-leaf-render" data="{%id%}"><div class="pl-head"><span class="icon pl-leaf-icon"/></div><div class="pl-body"><p class="pl-item-name">{%name%}</p><p class="pl-item-des">{%des%}</p></div><div class="pl-footer"><span class="pl-price">{%price%}</span></div></div>',
            //状态样式 [移标移上样式，移标按下样式]
            statusStyles:['pl-item-over','pl-item-down'],
            //点击回调
            onItemSelected:null
            
        },config);
        //数据
        this.data = [];
        //列表容器
        this.container = null;
        //上下页，可实现左右切换
        this.pageA = null;
        this.pageB = null;
        this.currentPage = null;
        this.comeback = null;
        
        //当前对象
        this.currentItem = null;
        //itemId => {level:int,parent_id:string}
        this.__itemDatas = {};
        
        this.initChild();
    };
    
    var p = PageList.prototype;
    
    /**
     * 初始子对象
     * @returns {void}
     */
    p.initChild = function(){
        this.container = $(this.config['container']);
        this.pageA = $('<div class="pl-page"></div>').appendTo(this.container);
        this.pageB = $('<div class="pl-page"></div>').appendTo(this.container);
        
        this.comeback = $(this.config.comeback);
        this.comeback.click(this,function(e){
            var pl = e.data;
            pl.back();
        });
    }
    
    /**
     * 组件初始化
     * @param {array} data
     * [ 
     *  {
     *   id:int,            
     *   name:string,           //名称
     *   type:dir,              //类型 dir,content
     *   des:string,            //描述
     *   price:string,          //价格
     *   children:array         //子对象
     *  }
     * ]
     * @returns {viod}
     */
    p.init = function(data){
        this.reset();
        this.data = data;
        
        //添加顶级根目录
        this.__itemDatas['root'] = {id:'root',parent_id:-1,level:0,itemdata:{type:'dir',children:data}};
        this.__createData(data,0);
        this.goto(-1);  //显示根目录
    };
    
    /**
     * 构建数据
     * @param {array/Object} data 数据
     * @returns {void}
     */
    p.__createData = function(data,level){
        var isRoot = data instanceof Array;
        var itemdatas = isRoot ?  data : data.children;
        var parent_id = isRoot ?  -1 : data.id;
        
        level++;
        if(!itemdatas)return;
        for(var i=0,len=itemdatas.length;i<len;i++){
            this.__itemDatas[itemdatas[i].id] = {parent_id:parent_id,level:level,itemdata:itemdatas[i]};
            if(itemdatas[i].type == 'dir'){
                this.__createData(itemdatas[i],level);
            }
        }
    };
    
    /**
     * 渲染列表
     * @returns {void}
     */
    p.__render = function(){
        this.__renderPage(this.currentPage,this.currentItem.itemdata);
    };
    
    /**
     * 渲染 页
     * @param {Dom} dom 容器
     * @param {Array/Object} data 数据
     * @returns {void}
     */
    p.__renderPage = function(dom,data){
        var itemdatas = data.children;
        var item;
        //删除所有 item 鼠标事件并且清空 item
        dom.find('.pl-item').unbind('mouseover mouseout mousedown mouseup click');
        dom.empty();
        for(var i=0,len=itemdatas.length;i<len;i++){
            item = __renderDom(this.config[itemdatas[i].type == 'dir' ? 'dirRender' : 'contentRender'],itemdatas[i]);
            $(item).appendTo(dom);
        }
        $('.pl-item').mouseover(this,function(e){
            var config = e.data.config;
            $(this).addClass(config.statusStyles[0]);
        });
        $('.pl-item').mouseout(this,function(e){
            var config = e.data.config;
            $(this).removeClass(config.statusStyles[0]);
        });
        $('.pl-item').mousedown(this,function(e){
            var config = e.data.config;
            $(this).addClass(config.statusStyles[1]);
        });
        $('.pl-item').mouseup(this,function(e){
            var config = e.data.config;
            $(this).removeClass(config.statusStyles[1]);
        });
        $('.pl-item').click(this,function(e){
            var pl = e.data;
            var id = $(this).attr('data');
            pl.__itemOnSelected(id);
        });
    };
    
    /**
     * 跳转到指定id
     * @param {string} id 目标id，-1为要目录
     * @returns {void}
     */
    p.goto = function(id){
        if(id==-1 || !id)id = 'root';
        var itemData = this.__itemDatas[id];
        var nextPage = this.__getNextPage();
        //渲染要显示的页
        this.__renderPage(nextPage,itemData.itemdata);
        //调用动画
        this.animation(this.currentItem ? this.currentItem.level<itemData.level : false);
        this.currentPage = nextPage;
        this.currentItem = itemData;
        //设置后退按钮
        if(this.comeback){
            id == 'root' ? this.comeback.hide() : this.comeback.show();
        }
    };
    
    /**
     * 项选择处理
     * @param {type} itemId
     * @returns {void}
     */
    p.__itemOnSelected = function(itemId){
        var itemdata = this.__itemDatas[itemId].itemdata;
        
        if(itemdata.type == 'dir'){
            this.goto(itemId);
        }
        if(this.config.onItemSelected !=null && this.config.onItemSelected instanceof Function)
            this.config.onItemSelected.call(null,itemdata);
    };
    
    /**
     * 切换动画
     */
    p.animation = function(goIn){
        if(this.currentPage)
            this.currentPage.animate({left:((this.currentPage.width() + 20)*(goIn ? -1 : 1))+"px",zIndex:1},'fast','swing');
        var nextPage = this.__getNextPage();
        nextPage.css({left:((nextPage.width() + 20)*(goIn ? 1 : -1))+"px",zIndex:2});
        nextPage.animate({left:"0px"},'fast','swing');
    };
    /**
     * 获取下一页
     */
    p.__getNextPage = function(){
        return this.currentPage ? (this.currentPage == this.pageA ? this.pageB : this.pageA) : this.pageA;
    };
    
    /**
     * 后退
     * @returns {void}
     */
    p.back = function(){
        this.goto(this.currentItem.parent_id);
    };
    
    /**
     * 组件重置
     * @returns {void}
     */
    p.reset = function(){
        this.__itemDatas = {};
        this.currentItem = {};
    };
    
    /**
     * 销毁组件
     * @returns {void}
     */
    p.destory = function(){
        
    };
    
    /**
     * 创建DOM
     * @param {String} dom renderer = "<div>{%name%}</div><div>{%title%}</div>"
     * @param {Object} data {name:xxxx,title:xxxx}
     * @return Dom;
     */
    function __renderDom(renderer, data) 
    {
        var daName = [],
        daVal = [],
        efn = [];
        for (var i in data) {
            daName.push(i);
            daVal.push("data." + i);
        }
        var _renderer = "'" + renderer + "'";
        _renderer = _renderer.replace(/\{\%/g, "'+");
        _renderer = _renderer.replace(/\%\}/g, "+'");
        efn.push("(function(");
        efn.push(daName.join(","));
        efn.push("){");
        efn.push("return " + _renderer);
        efn.push("})(");
        efn.push(daVal.join(","));
        efn.push(")");
        return eval(efn.join(""));
    };
    
    
    
    Wskeee.demand.PageList = PageList;
    
})(window,jQuery);