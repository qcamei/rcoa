/**
 * 异步添加课程
 * @param {Window} win
 * @param {jQuery} $
 * @returns {void}
 */
(function(win,$){
    var Wskeee = win.Wskeee = win.Wskeee || {};
    
    var Import = function(config,courses){
        
        this.config = $.extend({
            container:'#import-log-container',
            maxPost:100,
            pushURL:'http://tt.ccoaadmin.gzedu.net/framework/import2/create',
            logItemRenderer:'<div class="import-log"><p>{%index%}、【{%result%}】【{%title%}】{%content%}</p></div>'
        },config);
        this.container = $(this.config['container']);
        //所有需要添加的课程
        this.courses = courses;
        //一次最大新增数量
        this.maxPost = config['maxPost'] ? config['maxPost'] : 100;
        //当前索引
        this.currentIndex = 0;
        //一共要新增多少数量
        this.maxLen = this.courses.length;
        //是否正在上传中
        this.isPushing = false;
        //进度容器
        this.progressContainer = $('<div></div>').appendTo(this.container);
    };
    
    var p = Import.prototype;
    
    /**
     * 上传数据，新增
     * @returns {void}
     */
    p.push = function(){
        if(this.isPushing)return;
        
        if(this.currentIndex>=this.maxLen-1){
            console.log('完成！');
            this.currentIndex = this.maxLen - 1;
            this.__updateProgress();
        }else{
            this.__push(this.courses.slice(this.currentIndex,this.currentIndex+this.maxPost));
        }
    }
    /**
     * 显示日志
     * @param {array} arr
     * @returns {void}
     */
    p.addLog = function(obj){
        var code = obj['code'];
        var msg = obj['msg'];
        var logs = obj['logs'];
        var log;
        
        var right = "<span style='color:#0000ff;'> √ </span>";
        var wrong = "<span style='color:#ff0000;'> × </span>";
        
        for(var i=0,len=logs.length;i<len;i++){
            log = logs[i];
           
            $(__renderDom(this.config['logItemRenderer'],{
                index:i,
                result:log['result'] = 0 ? wrong : right,
                title:log['title'],
                content:log['data']
            })).appendTo(this.container);
        }
    }
    /**
     * 
     * @returns {void}
     */
    p.__push = function(data){
        if(this.isPushing)return;
        this.isPushing = true;
        var _this = this;
        $.ajax({
            type: "POST",
            url: this.config['pushURL'],
            data: JSON.stringify({courses:data}),
            success: function (result) {
                _this.__pushResponse(result);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                 console.log(XMLHttpRequest['responseText']);
            },
            dataType: 'json',
            contentType: "application/json"
        });
    }
    
    /**
     * 新增回调
     * @param {type} result
     * @returns {undefined}
     */
    p.__pushResponse = function(result){
        var title = ""+this.currentIndex+"~"+(this.currentIndex+this.maxPost > this.maxLen-1 ? this.maxLen-1 : this.currentIndex+this.maxPost);
        this.__updateProgress();
        this.container.append($('<p class="title">'+title+'</p>'));
        this.isPushing = false;
        this.currentIndex = this.currentIndex + this.maxPost;
        this.addLog(result);
        this.push();
    }
    
    p.__updateProgress = function(){
        var progressRenderer = '<div class="progress">'
                                    +'<div class="progress-bar" role="progressbar" aria-valuenow="{%value%}" aria-valuemin="0" aria-valuemax="100" style="width: {%value%}%;">'
                                      +'{%value%}%'
                                    +'</div>'
                                  +'</div>';
        this.progressContainer.html(__renderDom(progressRenderer,{value:Math.round(this.currentIndex/this.maxLen*100)}));
    }
    
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
    
    Wskeee.framework = Wskeee.framework || {};
    Wskeee.framework.Import = Import;
})(window,jQuery);
