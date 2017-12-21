/**
 * Created by wskeee on 2017-03-23 .
 */
(function (win,$) {
    "use stric";
    var Wskeee = win.Wskeee = win.Wskeee  || {};
    Wskeee.ccoa = Wskeee.ccoa || {};
    //-------------------------------------------------------------
    //
    // constructor
    //
    //-------------------------------------------------------------
    var NetButton = function NetButton(config) {
        this.config = $.extend({
            path:'',                            //路径
            container:'#richbutton',
            onSelected:null,                    //选择回调
			onReady:null,						//初始化完成回调
        },config);

        this.container = null;
        this.canvas = null;
        this.stage = null;
        this.images = images || {};
        this.ss = ss || {};

        this.consts = {
            btnnum:9,
            ispc:IsPC(),
            links:[
                    "0-1","0-6","0-5","0-7",
                    "1-7","1-6","1-8","1-2",
                    "2-6","2-8","2-3",
                    "3-8","3-4",
                    "4-8","4-6","4-5","4-7",
                    "5-7"
                ]
        };
        this.circle = null;
        this.circle_hui = null;
        this.icon = null;
        this.nameLabelSelectd = null;
        this.nameLabelUnSelected = null;
        this.lineTopPan = null;
        this.lineBottomPan = null;
        this.btns = [];

        this.lineValue = 0;     //当前画线比值
        this.lineDir = 1;       //画线状态，1是画，0是退
        this.lineDV = 0.03;     //画线速度
        this.selected = false;
        this.easeing = false;
        this.drawLineID = null;

        this.__initAssets();
    };
    var p = NetButton.prototype;
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
        this.canvas = $('<canvas id="canvas" width="340" height="370"></canvas>').appendTo(this.container)[0];
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
        //console.log(manifest);
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
        this.circle = this.skin['big_circle'];
        this.circle_hui = this.skin['big_circle_hui'];
        this.icon = this.skin['icon'];
        this.nameLabelSelectd = this.skin['big_label_rgb'];
        this.nameLabelUnSelected = this.skin['big_label_no_rgb'];
        this.lineTopPan = this.skin['line_top'].addChild(new createjs.Shape());
        this.lineBottomPan = this.skin['line_bottom'].addChild(new createjs.Shape());

        this.lineTopPan.graphics.setStrokeStyle(1).beginStroke("#1A7FB3");
        this.lineBottomPan.graphics.setStrokeStyle(1).beginStroke("#A2B6BC");
        this.circle_hui.mouseChildren = this.circle_hui.mouseEnabled = false;


        var b;
        for(var i= 0,len=this.consts['btnnum'];i<len;i++){
            b = new Wskeee.ccoa.NB(this.skin['b'+i]);
            b.index = i;
            b.ideui.alpha = 0;
            this.btns.push(b);
        }

        //字符转成索引
        var linkPaths = [];
        var link;
        for(i=0,len=this.consts.links.length;i<len;i++){
            link = this.consts.links[i];
            link = link.split("-");
            linkPaths.push([link[0],link[1]]);
        }
        this.consts.linkPaths = linkPaths;

        this.__resetChild();
        var _this = this;
        setTimeout(function(){
            if(_this.config['onReady'])
                _this.config['onReady']();
        },100);
    };
    /**
     * 重置子对象初始状态
     * @private
     */
    p.__resetChild = function(){
        var _this = this;
        this.easeing = true;
        var centerX = this.circle.x;
        var centerY = this.circle.y;

        this.__drawLine(-2);

        //隐藏按钮
        var b;
        for(var i= 0,len=this.btns.length;i<len;i++){
            b = this.btns[i];
            b.showName(false);
            b.stopSwim();
            createjs.Tween.get(b.ideui,{override:true}).
                wait(300).
                to({alpha:0,x:centerX,y:centerY,scaleX:0,scaleY:0},500,createjs.Ease.circOut);
        }
        //显示图标
        createjs.Tween.get(this.icon,{override:true}).
            wait(500).
            to({alpha:1},500,createjs.Ease.circOut).
            call(function(){_this.easeing = false});
        //名称恢复位置
        createjs.Tween.get(this.nameLabelSelectd,{override:true}).
            wait(300).
            to({x:this.nameLabelUnSelected.x,y:this.nameLabelUnSelected.y},500,createjs.Ease.circOut).
            call(function(){
                _this.nameLabelSelectd.visible = false;
                _this.nameLabelUnSelected.visible = true});
        //大图标恢复
        createjs.Tween.get(this.circle,{override:true}).
            wait(300).
            to({scaleX:1,scaleY:1},500,createjs.Ease.circOut);
        //大图标恢复
        createjs.Tween.get(this.circle_hui,{override:true}).
            wait(300).
            to({scaleX:1,scaleY:1,alpha:1},500,createjs.Ease.circOut);


        this.__enabledMinBtn(false);
    };

    /**
     * 显示选择中状态
     * @private
     */
    p.__showSelectState = function(){
        var _this = this;
        this.easeing = true;
        this.nameLabelUnSelected.visible = false;
        this.nameLabelSelectd.visible = true;
        //标题下移
        createjs.Tween.get(this.nameLabelSelectd,{override:true}).
            to({y:this.nameLabelUnSelected.y + 70},500,createjs.Ease.circOut);
        //图标隐藏
        createjs.Tween.get(this.icon,{override:true}).
            to({alpha:0},500,createjs.Ease.circOut);
        //大图标缩小
        createjs.Tween.get(this.circle,{override:true}).
            to({scaleX:0.8,scaleY:0.8},400,createjs.Ease.circOut).
            call(function(){
                _this.__enabledMinBtn(true);
                _this.__drawLine(1);
            });
        createjs.Tween.get(this.circle_hui,{override:true}).
            to({scaleX:0.8,scaleY:0.8,alpha:0},400,createjs.Ease.circOut);

        //名称与图标
        var b;
        for(var i= 0,len=this.consts['btnnum'];i<len;i++){
            b = this.btns[i];
            b.showName(false);
            createjs.Tween.get(b.ideui,{override:true}).
                to({alpha:1,x: b.initTransform.x,y:b.initTransform.y,scaleX:1,scaleY:1},500,createjs.Ease.circOut).
                call(function(b){
                    b.showName(true,true);
                    b.startSwim();
                },[b]);
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
        }else{
            this.circle.addEventListener('rollover',function(evt){
                if(!_this.selected)
                    _this.setSelecte(true);
            });
        }
        //移出区域恢复未选状态
        this.skin.addEventListener('rollout',function(evt){
            _this.setSelecte(false);
        });

        var b;
        for(var i=0,len=this.consts['btnnum'];i<len;i++){
            b = this.btns[i];
			if(i==6){
				b.cursor = 'pointer';
			}
            b.ideui.on('click',function(evt){
                _this.__iconMinClick(this.index);
            },b);
            b.ideui.on('rollover',function(evt){
                _this.___iconMinActive(this.index,true);
            },b);
            b.ideui.on('rollout',function(evt){
                _this.___iconMinActive(this.index,false);
            },b);
        }
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
    /**
     * 子按钮鼠标状态
     * @param bo
     * @private
     */
    p.___iconMinActive = function(index,bo){
        var b = this.btns[index];
        var scale = b.initTransform.circleScale;
        if(b.active == bo)return;

        b.active = bo;

        createjs.Tween.get(b.ideui['circle'],{override:true}).
            to({scaleX:bo ? scale + 0.3 : scale,scaleY:bo ? scale + 0.3 : scale},500,createjs.Ease.circOut);

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
     * 启用/禁用子按钮
     * @private
     */
    p.__enabledMinBtn = function(bo){
        if(this.btns[0].ideui.mouseChildren == bo)return;

        for(var i=0,len=this.consts['btnnum'];i<len;i++){
            this.btns[i].ideui.mouseChildren = this.btns[i].ideui.mouseEnabled = bo;
        }
    };

    /**
     * 画线
     * @private
     */
    p.__drawLine = function(dv){

        var linkPaths = this.consts.linkPaths;
        var path;
        var b;

        this.lineDir = dv;
        this.lineTopPan.graphics.clear();
        this.lineBottomPan.graphics.clear();
        this.lineTopPan.graphics.setStrokeStyle(1).beginStroke("#1A7FB3");
        this.lineBottomPan.graphics.setStrokeStyle(1).beginStroke("#A2B6BC");
        this.lineValue += this.lineDir*this.lineDV;
        if(this.lineValue < 0) {
            this.lineValue = 0;
        }
        if(this.lineValue > 1){
            this.lineValue = 1;
        }

        var pan;
        var lineToP;
        for(var i= 0,len=linkPaths.length;i<len;i++){
            path = linkPaths[i];
            pan = path[1]>=7 ? this.lineBottomPan : this.lineTopPan;
            b = this.btns[path[0]];
            pan.graphics.moveTo(b.ideui.x, b.ideui.y);
            b = this.btns[path[1]];
            lineToP = interpolate(this.btns[path[0]].ideui,this.btns[path[1]].ideui,this.lineValue);
            pan.graphics.lineTo(lineToP.x,lineToP.y);
        }
        var _this = this;
        clearTimeout(this.drawLineID);
        this.drawLineID = setTimeout(function(){_this.__drawLine(dv)},10);
    };
    //-------------------------------------------------------------
    //
    // utils
    //
    //-------------------------------------------------------------
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

    function interpolate(pa,pb,f){
        var dx = pa.x + (pb.x - pa.x) * f;
        var dy = pa.y + (pb.y - pa.y) * f;
        return {x:dx,y:dy};
    }
    //-------------------------------------------------------------
    //
    // apply Class
    //
    //-------------------------------------------------------------
    Wskeee.ccoa.NetButton = NetButton;
})(window,jQuery);

/**
 * NB
 */
(function(win,$){
    "use stric";
    var Wskeee = win.Wskeee = win.Wskeee  || {};
    Wskeee.ccoa = Wskeee.ccoa || {};
    //-------------------------------------------------------------
    //
    // constructor
    //
    //-------------------------------------------------------------
    var r = 20;

    var NB = function NB(ui) {
        this.ideui = ui;
        this.circle = null;
        this.name_txt = null;

        this.initTransform = {x:ui.x, y:ui.y,circleScale:ui['circle'].scaleX};

        this.__initChild();
    };
    var p = NB.prototype;
    p.index = -1;
    p.active = false;
    p.swiming = false;
    p._vx = 0;
    p._vy = 0;
    p._swimingID = null;
    p._resetSwimID = null;

    //-------------------------------------------------------------
    //
    // private methods:
    //
    //-------------------------------------------------------------
    //--------------------------------------------------
    // initChild
    //--------------------------------------------------
    p.__initChild = function () {
        this.circle = this.ideui['circle'];
        this.name_txt = this.ideui['name_txt'];
    };

    p.__destoryChild = function () {

    };

    p.__swim = function(){
        this.ideui.x += this._vx;
        this.ideui.y += this._vy;

        if(this.ideui.x<this.initTransform.x - r/2){
            this.ideui.x = this.initTransform.x - r/2;
            this._vx = this._vx*-1;
        }
        if(this.ideui.x>this.initTransform.x + r/2){
            this.ideui.x = this.initTransform.x + r/2;
            this._vx = this._vx*-1;
        }


        if(this.ideui.y<this.initTransform.y - r/2){
            this.ideui.y = this.initTransform.y - r/2;
            this._vy = this._vy*-1;
        }
        if(this.ideui.y>this.initTransform.y + r/2){
            this.ideui.y = this.initTransform.y + r/2;
            this._vy = this._vy*-1;
        }

    };

    p.__resetSwim = function(){
        this._vx = (Math.random()*2 - 1)*0.1;
        this._vy = (Math.random()*2 - 1)*0.1;

        var _this = this;
        clearInterval(this._swimingID);
        this._swimingID = setInterval(function(){_this.__swim()},10);

        clearTimeout(this._resetSwimID);
        this._resetSwimID = setTimeout(function(){_this.__resetSwim()},Math.random()*1000);
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
    p.startSwim = function(){
        this.__resetSwim();
    };
    p.stopSwim = function(){
        clearInterval(this._swimingID);
        clearTimeout(this._resetSwimID);
    };

    p.showName = function(bo,easeing){
        easeing = !!easeing;
        if(this.name_txt){
            if(!easeing)
                //this.name_txt.alpha = bo ? 1 : 0;
                createjs.Tween.get(this.name_txt,{override:true}).
                    to({alpha:bo ? 1 : 0},1);
            else
                createjs.Tween.get(this.name_txt,{override:true}).
                    wait(Math.round(Math.random()*1000)).
                    to({alpha:bo ? 1 : 0},500,createjs.Ease.circOut);
        }


    };
    //-------------------------------------------------------------
    //
    // apply Class
    //
    //-------------------------------------------------------------
    Wskeee.ccoa.NB = NB;
})(window,jQuery);