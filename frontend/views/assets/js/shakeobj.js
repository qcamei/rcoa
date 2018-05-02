(function(win,$){
	
	var ShakeObj = function ShakeObj(elem, config) {
		if(!elem){
			if(console){
				console.error('[ShakeObj] 创建失败！elem 不能为空！');
			}
		}
		var config = $.extend({
			styleName: 'top',		//改变的样式
			rang: 6,				//
			interactive: true,		//是否启用交互，禁用后可手动调用显示效果
			startFun: null,			//效果开始时回调
			endFun: null			//效果结束后回调
		},config);
		
		var curMax = config.rang;
		var drct = 0;
		var step = 1;
		var timeOutID;
		var styleName = config.styleName;
		
		startFunc = config.startFun;
		endFunc = config.endFun;

		init();

		function init() { 
			if(config['interactive']){
				active();
			}
		}
		
		function active() {
			elem.onmouseover = function(e) {
				if(!drct){
					shakeUpward();
				}    
			} 
		}
		
		function deactive() {
			elem.onmouseover = null; 
			clearTimeout(timeOutID);
			ShakeObj.elems[elem] = null;
			delete ShakeObj.elems[elem];
		}

		/** 向上跳动 */
		function shakeUpward() {
			clearTimeout(timeOutID);
			var t = parseInt(elem.style[styleName]); 
			
			if (!drct) motionStart();
			else {
				var nextTop = t - step * drct;
				if (nextTop >= -curMax && nextTop <= 0) {
					elem.style[styleName] = nextTop + 'px';
				}else if(nextTop < -curMax) {
					drct = -1;
				}else {
					var nextMax = curMax / 2;
					if (nextMax < 1) {
						motionOver();
						return;
					}
					curMax = nextMax;
					drct = 1;
				}
			}
			timeOutID = setTimeout(function(){shakeUpward()}, 200 / (curMax+3) + drct * 3);
		}
		
		function motionStart() {
			if(startFunc != null){
				startFunc.apply(this);
			}
			elem.style[styleName] = '0';
			drct = 1;
		}
		
		function motionOver() {
			if(endFunc != null){
				endFunc.apply(this);
			}
			curMax = config.rang;
			drct = 0;
			elem.style[styleName] = '0';
		}

		this.start = shakeUpward;
		this.active = active;
		this.deactive = deactive;
	} 
	
	ShakeObj.elems = {};
	ShakeObj.get = function(elem,config){
		var id = elem.id || (config && config.id) || '#shake'+Math.round(Math.random() * 100000);
		var obj = ShakeObj.elems[id];
		if(!obj){
			if(!elem.id)
				elem.id = id;
			obj  = new ShakeObj(elem,config);
			ShakeObj.elems[id] = obj;
		}
		return obj;
	}
	
	win.ShakeObj = ShakeObj;
})(window,jQuery);