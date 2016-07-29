/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function(win,$){
    
    win.teamwork = win.teamwork || {};
    /**
     * 创建表
     * @param {String} title
     * @param {Dom} dom
     * @param {Array} datas
     * @returns
     */
    var PicChart = function(title,dom,datas){
        this.init(dom);
        this.reflashChart(title,datas);
        var _this = this;
        $(win).resize(function(){
            _this.chart.resize();
        });
    }
    var p = PicChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;
    
    p.init = function(dom){
        this.canvas = dom;
        this.chart = echarts.init(dom);
        this.chartOptions = {
            title : {
                text: '某站点用户访问来源',
                subtext: '数据来自课程中心平台',
                x:'left'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)",
                position: ($(dom).width() >= 720 ? null : ['0%', '15%'])
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: []
            },
            series : [
                {
                    name: '',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        /*
                        {value:335, name:'直接访问'},
                        {value:310, name:'邮件营销'},
                        {value:234, name:'联盟广告'},
                        {value:135, name:'视频广告'},
                        {value:1548, name:'搜索引擎'} 
                         */
                    ],
                    label:{
                        normal:{
                            show:true,
                            formatter:'{b} ( {c}学时 ) {d}%',
                            left:'left'
                        }
                    },
                    
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };
    };
    
    /**
     * 刷新图标
     * @param {String} title 标题
     * @param {Array} data 出错步骤数据
     * @returns 
     */
    p.reflashChart = function(title,data){
        this.chartOptions.title.text = title;
        this.chartOptions.series[0].data = data;
        this.chart.setOption(this.chartOptions);
    }
    
    win.teamwork.PicChart = PicChart;
})(window,jQuery);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function(win,$){
    
    win.teamwork = win.teamwork || {};
    /**
     * 创建表
     * @param {String} title
     * @param {Dom} dom
     * @param {Array} datas
     * @returns
     */
    var BarChart = function(title,dom,datas){
        this.init(dom);
        this.reflashChart(title,datas);
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
    
    p.init = function(dom){
        this.canvas = dom;
        this.chart = echarts.init(dom);
        this.chartOptions = {
            title: {
                text: '世界人口总量',
                subtext: '数据来自网络'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: []
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: ['巴西','印尼','美国','印度','中国','世界人口(万)']
            },
            series: [
                {
                    name: '',
                    type: 'bar',
                    label: {
                        normal: {
                            show: true,
                            position:"insideRight",
                            formatter:"{c} 学时"
                        }
                    },
                    data: [18203, 23489, 29034, 104970, 131744, 630230]
                }
            ]
        };

    };
    
    /**
     * 刷新图标
     * @param {String} title 标题
     * @param {Array} data 出错步骤数据
     * @returns 
     */
    p.reflashChart = function(title,data){
        
        var keys = [];
        var values = [];
        
        for(var i=0,len=data.length;i<len;i++)
        {
            keys.push(data[i]["name"]);
            values.push(data[i]["value"]);
        }
        
        
        this.chartOptions.title.text = title;
        this.chartOptions.yAxis.data = keys;
        this.chartOptions.series[0].data = values;
        this.chart.setOption(this.chartOptions,true);
    }

    win.teamwork.BarChart = BarChart;
})(window,jQuery);