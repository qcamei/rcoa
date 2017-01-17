(function (win,$) {
    "use stric";
    var Wskeee = win.Wskeee = win.Wskeee || {};
    Wskeee.ccoa = Wskeee.ccoa || {};
    //-------------------------------------------------------------
    //
    // constructor
    //
    //-------------------------------------------------------------
    var RichButton = function RichButton(config) {
        this.config = $.extend({
            path:'',                            //路径
            container:'#richbutton',
            onSelected:null                     //选择回调
        },config);

        this.container = null;
        this.canvas = null;
        this.stage = null;
        this.images = images || {};
        this.ss = ss || {};

        this.title = null;                      //按钮标题
        this.circle = null;                     //圆底
        this.icon_big = null;                   //大图标
        this.icon_mins = [];                    //3个小图标
        this.name_mins = [];                    //3个按钮名称
        this.ray = null;                        //射线

        this.consts = {btnnum:3,ispc:IsPC()};                       //初始值保存
        this.selected = false;
        this.easeing = false;                 //动画中

        this.__initAssets();
    };
    var p = RichButton.prototype;

    //--------------------------------------------------
    // 初始资源加载
    //--------------------------------------------------
    p.__initAssets = function(){
        try {
            document.createElement("canvas").getContext("2d");
        } catch (e) {
            return;
        }
        this.container = $(this.config['container']);
        this.container.empty();
        this.canvas = $('<canvas id="canvas" width="250" height="250"></canvas>').appendTo(this.container)[0];
        var _this = this;


        var loader = new createjs.LoadQueue(false);
        loader.addEventListener("fileload", function(evt) {
            if (evt.item.type == "image") { _this.images[evt.item.id] = evt.result; }
        });
        loader.addEventListener("complete", function(evt) {
            var queue = evt.target;
            _this.stage = new createjs.Stage(_this.canvas);
            _this.skin = new lib.MainUI();

            if(!_this.consts['ispc'])
                createjs.Touch.enable(_this.stage);

            createjs.Ticker.setFPS(lib.properties.fps);
            createjs.Ticker.addEventListener("tick", _this.stage);

            _this.stage.addChild(_this.skin);
            _this.stage.update();
            _this.stage.enableMouseOver(10);

            _this.__init();
        });
        var manifest = lib.properties.manifest;

        for(var i= 0,len=manifest.length;i<len;i++)
            manifest[i]['src'] = this.config['path']+manifest[i]['src']
        loader.loadManifest(manifest);
    };


    //-------------------------------------------------------------
    //
    // private methods:
    //
    //-------------------------------------------------------------
    /**
     * 对象初始化
     * @private
     */
    p.__init = function(){
        this.__initChild();
        this.__initChildEvent();
    };


    p.__initChild = function(){
        this.title = this.skin['title'];                            //按钮标题
        this.consts['title'] = {x:this.title.x,y:this.title.y};
        this.circle = this.skin['circle'];                          //圆底
        this.icon_big = this.circle['icon_big'];                  //大图标
        for(var i= 0,len=this.consts['btnnum'];i<len;i++){
            this.icon_mins.push(this.circle['icon_min'+i]);
            this.name_mins.push(this.skin['name_min'+i]);
            this.icon_mins[i]['icon'].alpha = this.name_mins[i].alpha = 0;
        }
        this.ray = this.circle['ray'];                            //射线

        this.__resetChild();
    };
    /**
     * 重置子对象初始状态
     * @private
     */
    p.__resetChild = function(){
        var _this = this;
        this.easeing = true;
        //标题回放到最初始位置
        createjs.Tween.get(this.title,{override:true}).
            wait(500).
            to({alpha:1,x:this.consts['title'].x,y:this.consts['title'].y},500,createjs.Ease.circOut).
            call(function(){_this.easeing = false});
        //大图标恢复
        createjs.Tween.get(this.icon_big,{override:true}).
            wait(400).
            to({alpha:1,scaleX:1,scaleY:1},500,createjs.Ease.circOut);
        //射线隐藏
        createjs.Tween.get(this.ray,{override:true}).
            wait(200).
            to({alpha:0,scaleX:0,scaleY:0},800,createjs.Ease.circOut);
        //名称与图标
        for(var i= 0,len=this.consts['btnnum'];i<len;i++){
            createjs.Tween.get(this.icon_mins[i]['icon'],{override:true}).
                to({alpha:0,scaleX:0,scaleY:0},500,createjs.Ease.circOut);
            createjs.Tween.get(this.name_mins[i],{override:true}).
                to({alpha:0},500,createjs.Ease.circOut);
        }
        this.__enabledMinBtn(false);
    };

    /**
     * 显示选择中状态
     * @private
     */
    p.__showSelectState = function(){
        var _this = this;
        this.easeing = true;
        //标题回放到最初始位置
        createjs.Tween.get(this.title,{override:true}).
            to({alpha:0,y:this.consts['title'].y - 10},500,createjs.Ease.circOut);
        //大图标恢复
        createjs.Tween.get(this.icon_big,{override:true}).
            to({alpha:0,scaleX:0,scaleY:0},400,createjs.Ease.circOut);
        //射线显示
        createjs.Tween.get(this.ray,{override:true}).
            wait(200).
            to({alpha:1,scaleX:1,scaleY:1},200,createjs.Ease.circOut);
        //名称与图标
        for(var i= 0,len=this.consts['btnnum'];i<len;i++){
            createjs.Tween.get(this.icon_mins[i]['icon'],{override:true}).
                wait(400).
                to({alpha:0.5,scaleX:1,scaleY:1},500,createjs.Ease.circOut).
                call(function(){_this.easeing = false;_this.__enabledMinBtn(true);});
            createjs.Tween.get(this.name_mins[i],{override:true}).
                wait(400).
                to({alpha:0.3},500,createjs.Ease.circOut);
        }
    };

    //--------------------------------------------------
    // __initChildEvent
    //--------------------------------------------------
    p.__initChildEvent = function () {
        var _this = this;
        if(!this.consts['ispc'])
        {
            //手机端
            this.circle.addEventListener('click',function(evt){
                if(!_this.selected)
                    _this.setSelecte(true);
            });
            this.circle.addEventListener('pressmove',function(evt){
                if(_this.easeing)return;
                if(!_this.selected)
                    _this.setSelecte(true);
                else
                    _this.__iconMinPressMove(false);
            });
            this.stage.addEventListener('pressup',function(evt){
                if(_this.easeing || !_this.selected)return;
                _this.__iconMinPressMove(true);
            });
        }else{
            this.circle.addEventListener('rollover',function(evt){
                _this.setSelecte(true);
            });
            this.circle.addEventListener('rollout',function(evt){
                _this.setSelecte(false);
            });
        }


        for(var i=0,len=this.consts['btnnum'];i<len;i++){
            this.icon_mins[i].index = i;
            //pc
            if(!this.consts['ispc'])continue;
            this.icon_mins[i].addEventListener('click',function(evt){
                if(_this.easeing)return;
                _this.__iconMinClick(evt.currentTarget.index);
                _this.__iconMinPressMove(false);
            });
            this.icon_mins[i].addEventListener('rollover',function(evt){
                _this.___iconMinActive(evt.currentTarget.index,true);
            });
            this.icon_mins[i].addEventListener('rollout',function(evt){
                _this.___iconMinActive(evt.currentTarget.index,false);
            });
        }
    };

    /**
     * 子按钮鼠标状态
     * @param bo
     * @private
     */
    p.___iconMinActive = function(index,bo){
        if(this.icon_mins[index].active == bo)return;

        this.icon_mins[index].active = bo;
        createjs.Tween.get(this.icon_mins[index]['icon'],{override:true}).
            to({alpha:bo ? 1 : 0.3},500,createjs.Ease.circOut);
        createjs.Tween.get(this.name_mins[index],{override:true}).
            to({alpha:bo ? 1 : 0.3},500,createjs.Ease.circOut);
    };

    /**
     * 子按钮点击
     * @param index
     * @private
     */
    p.__iconMinClick = function(index){
        if(this.config['onSelected'])
            this.config['onSelected'](index);
    };

    /**
     * 子按钮按压检测
     * @param {boolean} changeState 当所有子按钮都没有选择时是否改变状态
     * @private
     */
    p.__iconMinPressMove = function(changeState){
        var p;
        var hit;
        var isHit = false;
        changeState = !!changeState;
        for(var i=0,len=this.consts['btnnum'];i<len;i++){
            p = this.icon_mins[i].globalToLocal(this.stage.mouseX, this.stage.mouseY);
            hit = this.icon_mins[i].hitTest(p.x, p.y);
            this.___iconMinActive(i,hit);
            if(hit)isHit = true;
            if(changeState && hit)
                this.__iconMinClick(i);
        }

        if(changeState && !isHit)
            this.setSelecte(false);

    };

    /**
     * 启用/禁用子按钮
     * @private
     */
    p.__enabledMinBtn = function(bo){
        if(this.icon_mins[0].mouseChildren == bo)return;

        for(var i=0,len=this.consts['btnnum'];i<len;i++){
            this.icon_mins[i].mouseChildren = this.icon_mins[i].mouseEnabled = bo;
        }
    };

    p.__destoryChildEvent = function () {

    };
    //-------------------------------------------------------------
    //
    // static public methods
    //
    //-------------------------------------------------------------

    //-------------------------------------------------------------
    //
    // public methods
    //
    //-------------------------------------------------------------
    /**
     * 设置选择
     * @param bo
     */
    p.setSelecte = function(bo){
        this.selected = bo;
        this.selected ? this.__showSelectState() : this.__resetChild();
    };
    //--------------------------------------------------
    // util method
    //--------------------------------------------------
    function IsPC() {
        var userAgentInfo = navigator.userAgent;
        var Agents = ["Android", "iPhone",
            "SymbianOS", "Windows Phone",
            "iPad", "iPod"];
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }
    //-------------------------------------------------------------
    //
    // apply Class
    //
    //-------------------------------------------------------------
    Wskeee.ccoa.RichButton = RichButton;
})(window,jQuery);