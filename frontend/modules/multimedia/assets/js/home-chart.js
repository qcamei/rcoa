(function(win,$){
    win.multimedia = win.multimedia || {};
    
    /**
     * 创建表
     * @param {Dom} dom
     * @param {Array} datas
     * @param {Object[Array]} legend 分类说明
     * @returns
     */
    var BarChart = function(dom,datas,legend){
        this.init(dom,datas,legend);
        this.reflashChart(datas,legend);
        var _this = this;
        $(win).resize(function(){
            _this.chart.resize();
        });
    }
    var p = BarChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;
    
    p.init = function(dom,datas,legend){
        var _this = this;
        this.canvas = dom;
        
         //重新计算图标的高度，高度由显示的数据相关
        var len=0;
        for(var i in datas)len++;
        $(this.canvas).css('height',(len*(40+10)-10+60)+"px");
        
        this.chart = echarts.init(dom);
        this.chart.on('legendselectchanged', function(params){_this.legendselectchanged(params,datas,legend)});
        this.chartOptions = {
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data: ['板书'],
                selected:{}
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis:  {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: []
            },
            series: [
            ]
        };
    };
    
    /**
     * 刷新图标
     * @param {Array} datas
     * @param {Object[Array]} legend 分类说明
     * @returns 
     */
    p.reflashChart = function(datas,legend){
       //创建类型初始数据
        var _legend = [];
        var series = [];
        for(var id in legend)
        {
            _legend.push(legend[id]);
            series.push({
                    name: legend[id],
                    type: 'bar',
                    stack: '总量',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: []
                })
        }
        //分类数据
        this.chartOptions.legend.data = _legend;
        this.chartOptions.series = series;
        this.chart.setOption(this.chartOptions,true);
        this.chart.dispatchAction({type: 'legendToggleSelect'})//初始显示所有数据
    }
    /**
     * 类型 显示/隐藏 状态改变事件
     * @param {Object} params   分类 显示/禁用 状态
     * @param {Array} datas 所有数据
     * @param {Object[Array]} legend 分类说明
     * @returns {void}
     */
    p.legendselectchanged = function(params,datas,legend){        
         //根据类型过滤算出汇总,得到从高到低的排序结果
        var all = this.getAllByLegend(datas,params.selected);

        var serie,index=0;
        for(var id in legend){
            //拿到对应分类 serie
            serie = this.chartOptions.series[index++];
            serie.data = [];
            //合并分类的 data 数据，由于series第一个会排序最底下再往上增，所以使用倒序合并
            for(var i=all.length-1;i>=0;i--)
                serie.data.push(datas[all[i].name][legend[id]]);
        }
        
        //生成每个bar的名称
        var yAxisData = [];
        for(var i=all.length-1;i>=0;i--)
            yAxisData.push(all[i]["name"]+"\n("+all[i]["value"]+")");
        
        
        this.chartOptions.yAxis.data = yAxisData;
        this.chartOptions.legend.selected = params.selected;
        this.chart.setOption(this.chartOptions,true);
    }
    /**
     * 类型过滤算出汇总
     * @param {Array} datas
     * @param {Array} filterLegend    显示的类型
     * @returns {Object}
     */
    p.getAllByLegend = function(datas,filterLegend){
        var arr = [];
        var vv = 0;
        for(var i in datas){
            vv = 0;
            for(var legend_name in datas[i])
            {
                //只计算显示的分类
                if(filterLegend[legend_name])
                    vv += datas[i][legend_name];
            }
            //添加到数组方便排序
            arr.push({name:i,value:vv});
        }
        return arr.sort(function(a,b){return b.value-a.value});
    }
    
    win.multimedia.BarChart = BarChart;
})(window,jQuery);