(function (lib, img, cjs, ss, an) {

var p; // shortcut to reference prototypes
lib.ssMetadata = [];


// symbols:



(lib.course_dev_icon = function() {
	this.initialize(img.course_dev_icon);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,126,127);// helper functions:

function mc_symbol_clone() {
	var clone = this._cloneProps(new this.constructor(this.mode, this.startPosition, this.loop));
	clone.gotoAndStop(this.currentFrame);
	clone.paused = this.paused;
	clone.framerate = this.framerate;
	return clone;
}

function getMCSymbolPrototype(symbol, nominalBounds, frameBounds) {
	var prototype = cjs.extend(symbol, cjs.MovieClip);
	prototype.clone = mc_symbol_clone;
	prototype.nominalBounds = nominalBounds;
	prototype.frameBounds = frameBounds;
	return prototype;
	}


(lib.line = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = getMCSymbolPrototype(lib.line, null, null);


(lib.icon = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.course_dev_icon();
	this.instance.parent = this;
	this.instance.setTransform(-63,-63.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.icon, new cjs.Rectangle(-63,-63.5,126,127), null);


(lib.circle_shape = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f().s("#FFFFFF").ss(1,1,1).p("AB1AAQAAAxgiAiQgiAigxAAQgwAAgigiQgigiAAgxQAAgwAigiQAigiAwAAQAxAAAiAiQAiAiAAAwg");

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AhSBTQgigiAAgxQAAgwAigiQAigiAwAAQAxAAAiAiQAiAiAAAwQAAAxgiAiQgiAigxAAQgwAAgigig");

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.circle_shape, new cjs.Rectangle(-12.7,-12.7,25.4,25.4), null);


(lib.big_label_rgb = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#93BCDA").s().p("AAQBAIgkgpIAAAAIAAApIgQAAIAAh/IAQAAIAABQIAAAAIAigmIAUAAIgmAoIApAtg");
	this.shape.setTransform(10.8,12.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#93BCDA").s().p("AgcAoIAAgPQALAIAOAAQAQAAAAgMQAAgFgEgDQgDgDgLgFQgOgFgFgFQgEgGAAgIQAAgLAKgHQAKgHAMAAQAMAAAJAEIAAAOQgJgGgNAAQgGAAgFADQgFAEAAAFQAAAFAEADQADADAKAEQAPAFAFAFQAFAGAAAJQAAAMgKAGQgKAHgOAAQgNAAgKgFg");
	this.shape_1.setTransform(2.2,14.1);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#93BCDA").s().p("AgbAmQgHgGAAgMQABgXAcgEIAYgEQAAgVgRAAQgOAAgOALIAAgPQANgIAQAAQAgAAAAAhIAAA2IgQAAIAAgNIAAAAQgJAPgRAAQgMAAgIgHgAAAADQgKABgFAEQgDAEAAAHQAAAGAEAEQAEAEAIAAQAIAAAHgHQAGgHAAgLIAAgIg");
	this.shape_2.setTransform(-6.2,14.1);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#93BCDA").s().p("AgHA8IAAhqIgjAAIAAgNIBVAAIAAANIgjAAIAABqg");
	this.shape_3.setTransform(-15,12.4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#1A7FB3").s().p("AhdBVQA4gSALglIg2AAIAAgOIA4AAIACgUIAQAAIgCAUIBNAAIgDAxQgEAcgdAAIghgBIgEgQIAkACQARAAACgRIADgfIhBAAQgKAvg/AVIgJgNgAhigEQAxgIAkgOQgXgNgNgTQgPASgSAPIgLgKQAkgdARghIAPAGIgJAOIBpAAIAAANQgTAWgfARQAhAMAtADIgLAPQgzgGgigQQgmARg3AIIgIgMgAADghQAcgNASgSIhVAAQAPATAYAMg");
	this.shape_4.setTransform(10.6,-8.5);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#1A7FB3").s().p("AhDBgIAAhyQgLASgMAPIgGgQQAdgpASg1IAQAGQgJAXgJAUIAACOgAggBUIAAgOIA4AAIAAg+Ig8AAIAAgOIA8AAIAAg6IgyADIgDgPQBBgDAygGIADAPIgxAFIAAA7IA5AAIAAAOIg5AAIAAA+IA3AAIAAAOg");
	this.shape_5.setTransform(-9.4,-8.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.big_label_rgb, new cjs.Rectangle(-21.7,-24.6,44.5,49.3), null);


(lib.big_label_no_rgb = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(112,112,112,0.6)").s().p("AAQBAIgkgpIAAAAIAAApIgQAAIAAh/IAQAAIAABQIAAAAIAigmIAUAAIgmAoIApAtg");
	this.shape.setTransform(10.8,12.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("rgba(112,112,112,0.6)").s().p("AgcAoIAAgPQALAIAOAAQAQAAAAgMQAAgFgEgDQgDgDgLgFQgOgFgFgFQgEgGAAgIQAAgLAKgHQAKgHAMAAQAMAAAJAEIAAAOQgJgGgNAAQgGAAgFADQgFAEAAAFQAAAFAEADQADADAKAEQAPAFAFAFQAFAGAAAJQAAAMgKAGQgKAHgOAAQgNAAgKgFg");
	this.shape_1.setTransform(2.2,14.1);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("rgba(112,112,112,0.6)").s().p("AgbAmQgHgGAAgMQABgXAcgEIAYgEQAAgVgRAAQgOAAgOALIAAgPQANgIAQAAQAgAAAAAhIAAA2IgQAAIAAgNIAAAAQgJAPgRAAQgMAAgIgHgAAAADQgKABgFAEQgDAEAAAHQAAAGAEAEQAEAEAIAAQAIAAAHgHQAGgHAAgLIAAgIg");
	this.shape_2.setTransform(-6.2,14.1);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("rgba(112,112,112,0.6)").s().p("AgHA8IAAhqIgjAAIAAgNIBVAAIAAANIgjAAIAABqg");
	this.shape_3.setTransform(-15,12.4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#707070").s().p("AhdBVQA4gSALglIg2AAIAAgOIA4AAIACgUIAQAAIgCAUIBNAAIgDAxQgEAcgdAAIghgBIgEgQIAkACQARAAACgRIADgfIhBAAQgKAvg/AVIgJgNgAhigEQAxgIAkgOQgXgNgNgTQgPASgSAPIgLgKQAkgdARghIAPAGIgJAOIBpAAIAAANQgTAWgfARQAhAMAtADIgLAPQgzgGgigQQgmARg3AIIgIgMgAADghQAcgNASgSIhVAAQAPATAYAMg");
	this.shape_4.setTransform(10.6,-8.5);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#707070").s().p("AhDBgIAAhyQgLASgMAPIgGgQQAdgpASg1IAQAGQgJAXgJAUIAACOgAggBUIAAgOIA4AAIAAg+Ig8AAIAAgOIA8AAIAAg6IgyADIgDgPQBBgDAygGIADAPIgxAFIAAA7IA5AAIAAAOIg5AAIAAA+IA3AAIAAAOg");
	this.shape_5.setTransform(-9.4,-8.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.big_label_no_rgb, new cjs.Rectangle(-21.7,-24.6,44.5,49.3), null);


(lib.big_circle_hui = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#A2B6BC").s().de(-61.7,-61.7,123.5,123.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.big_circle_hui, new cjs.Rectangle(-61.7,-61.7,123.5,123.5), null);


(lib.big_circle = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#3F98D1").s().de(-61.7,-61.7,123.5,123.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.big_circle, new cjs.Rectangle(-61.7,-61.7,123.5,123.5), null);


(lib.b6_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = getMCSymbolPrototype(lib.b6_label, null, null);


(lib.b5_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AhOBDQAbgLARgSIgGAAIAAgsIALAAIAAAnQAKgLAGgOIALAHQgWApgtAVIgJgKgAgSBDQAagKAKgNQALgNAAgZIAAgWIALAAIAAAWQAAASgEANIArAcIgHALQgUgPgVgOIgCADQgLAQgcALIgIgKgAhOAoQAIgPAHgWIAMADQgIAXgJARIgKgGgAA7ArIAAhGIgwAAIAABGIgLAAIAAhQIAXAAIADgUIggAAIAAgLIBTAAIAAALIgmAAIgEAUIAjAAIAABQgAhNgJIAAgLIALAAIAAgqIALAAIAAAqIAQAAIAAg4IALAAIAAAYIAWAAIAAALIgWAAIAAAVIAYAAIAAALg");
	this.shape.setTransform(26,13.5);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgiBDQAvgTADgyIABgrIANAAQAAAsgDAKIAKAAIAAArQAAAJAJAAIAIAAQAJAAACgIIACgWIAMAFIgDAVQgCAPgRAAIgNAAQgTAAAAgSIAAgiQgKAogpASIgIgLgAg4BLIAAhDIgRASIgFgOQAZgUANgZIglAAIAAgLIAzAAIAAALQgIAOgKANIAAAIIAHgFIAUATIgJAIIgSgUIAABHgAAzAbIAAhUIg1AAIAABUIgMAAIAAhfIBNAAIAABfgAg9hHIAKgGIANAWIgMAGIgLgWg");
	this.shape_1.setTransform(10.1,13.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b5_label, new cjs.Rectangle(0,0,36.2,25.2), null);


(lib.b4_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AA3BIIAAgIIh5AAIAAhrIAMAAIAABgIBtAAIAAhgIAMAAIAABzgAglAoIAAhWIBLAAIAABUIgLAAIAAgIIg0AAIAAAKgAAGAUIAVAAIAAgWIgVAAgAgZAUIAUAAIAAgWIgUAAgAAGgMIAVAAIAAgXIgVAAgAgZgMIAUAAIAAgXIgUAAgAhKg8IAAgLICVAAIAAALg");
	this.shape.setTransform(26,13.8);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgOBCQAggdABg4IABgNIgWAAIAAgMIAWAAIAAggIANAAIgBAgIArAAQgBA6gDAkQgBAWgTAAIgVAAIgDgOIAUACQAMAAAAgNQACgnABgoIgeAAIAAANQgBA8gjAkIgKgLgAgOAyIg2AGIgGgNQAHgEAEgJQAIgPAIgXIgaAAIAAgLIBKAAIAAALIgjAAQgNAhgKASIAngEIgLgaIALgEQAKAVAIAVIgMAFIgCgGgAhDg0IAAgLIA/AAIAAALg");
	this.shape_1.setTransform(9.8,13.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b4_label, new cjs.Rectangle(0,0,36.2,25.2), null);


(lib.b3_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AgHA9IAAhqIgjAAIAAgPIBVAAIAAAPIgjAAIAABqg");
	this.shape.setTransform(26.2,12.9);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AglA9IAAh5IAjAAQASAAALAKQALAJAAASQAAASgNAKQgMALgTAAIgPAAIAAAtgAgVACIAOAAQAOgBAHgFQAIgHgBgMQAAgXgbAAIgPAAg");
	this.shape_1.setTransform(17.2,12.9);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#1A7FB3").s().p("AglA9IAAh5IAjAAQASAAALAKQALAJAAASQAAASgNAKQgMALgTAAIgPAAIAAAtgAgVACIAOAAQAOgBAHgFQAIgHAAgMQAAgXgcAAIgPAAg");
	this.shape_2.setTransform(7.4,12.9);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b3_label, new cjs.Rectangle(0,0,33,25.2), null);


(lib.b2_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AhCBIIAAhvIAyAAIAHgVIhBAAIAAgLICVAAIAAALIhHAAIgIAVIBHAAIAABvIgNAAIAAgJIhsAAIAAAJgAAdA1IAZAAIAAhRIgZAAgAgRA1IAjAAIAAgVIgjAAgAg2A1IAZAAIAAhRIgZAAgAgRAWIAjAAIAAgVIgjAAgAgRgIIAjAAIAAgUIgjAAg");
	this.shape.setTransform(26,13.8);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgGBJIAAg5IhFAAIAAgMIBFAAIAAhAIg+AAIAAgMICJAAIAAAMIg/AAIAABAIBGAAIAAAMIhGAAIAAA5gAg8grIAKgHQARAUANATIgLAJQgMgUgRgVgAATgLQARgSANgWIAMAIQgRAYgQAQg");
	this.shape_1.setTransform(10,13.7);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b2_label, new cjs.Rectangle(0,0,36.2,25.2), null);


(lib.b1_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AgFBNIAAggIglAAIAAgLIAlAAIAAg9QgUAngpAlIgKgJQAsglAWgoIg/AAIAAgLIBEAAIAAgcIALAAIAAAcIBEAAIAAALIg/AAQAZAuApAbIgKALQgmgdgXgtIAAA9IAkAAIAAALIgkAAIAAAgg");
	this.shape.setTransform(26,13.3);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AAjBNIAAiNIAnAAIAABiQAAAMgHACQgFACgLAAQgBgHgCgGQAPADAAgGIAAhXIgSAAIAACCgAhJBEQAKgSAAghIAAhUIAmAAIAAB/QAAAPgNAAIgPgBIgCgLIANABQAHAAgBgGIAAglIgRAAQAAAjgLAVIgJgJgAg1AMIARAAIAAgdIgRAAgAg1gbIARAAIAAgeIgRAAgAARAxIgjAFIgDgKQAIgLALgjIgSAAIAAgKIATAAIAAgcIgRAAIAAgKIARAAIAAgaIALAAIAAAaIARAAIAAAKIgRAAIAAAcIATAAIAAAKIgVAAQgJAcgHAQIAXgCIgIgXIAKgDQAJAZAGATIgLAEIgEgNg");
	this.shape_1.setTransform(9.6,13.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b1_label, new cjs.Rectangle(0,0,36.2,25.2), null);


(lib.b0_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AANBKIgDgOQASACAPAAQAOAAABgPIADhUIg1AAQgIARgIANIgMgGQAUgdAJggIANADIgJAWIA9AAIgDBjQgCAZgZAAIgfgBgAhJA4IA/gKIAAAMQgaADgjAHgAhFAWQAMgMANgUIgaACIgDgLQARgWAPghIALAEQgOAdgPAVIAWAAIANgXIALAGQgUAigTAXIAogGIgCAMIg0AIgAABgFIAKgHQAPASAMARIgMAIQgKgSgPgSg");
	this.shape.setTransform(25.7,13.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgXA/QAbgLAMgPQAKgNAAgZIAAgWIALAAIAAAXQAAARgEANQAYARAUAPIgIAKQgTgQgWgPIgDAEQgNAQgaANIgJgLgAhKBHIgCgNIAQABQAJAAAAgKIAAg9IgZAAIAAgLIAeAAIgUgWIAIgGIALAKIAQgVIgsAAIAAgLIA7AAIAAALIgYAcIAGAGIgHAFIAdAAIAAALQgDAMgFAPIgLgEQAEgKADgNIgQAAIAABBQAAASgRAAgAA7AjIAAhBIg2AAIAABBIgKAAIAAhMIAZAAIAEgVIgjAAIAAgLIBYAAIAAALIgoAAIgFAVIAmAAIAABMg");
	this.shape_1.setTransform(10,13.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b0_label, new cjs.Rectangle(0,0,36.2,25.2), null);


(lib.b8 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.circle = new lib.circle_shape();
	this.circle.parent = this;
	this.circle.setTransform(0,0,0.641,0.641);
	this.circle.alpha = 0.48;

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("Ag0A1QgWgWAAgfQAAgeAWgWQAWgWAeAAQAfAAAWAWQAWAWAAAeQAAAfgWAWQgWAWgfAAQgeAAgWgWg");

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.b8, new cjs.Rectangle(-7.8,-7.8,15.6,15.6), null);


(lib.b7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.circle = new lib.circle_shape();
	this.circle.parent = this;
	this.circle.setTransform(0,0,0.641,0.641);
	this.circle.alpha = 0.48;

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("Ag0A1QgWgWAAgfQAAgeAWgWQAWgWAeAAQAfAAAWAWQAWAWAAAeQAAAfgWAWQgWAWgfAAQgeAAgWgWg");

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.b7, new cjs.Rectangle(-7.8,-7.8,15.6,15.6), null);


(lib.b6 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b6_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(-55.1,-3.4);
	this.name_txt.shadow = new cjs.Shadow("#FFFFFF",0,0,4);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;
	this.circle.setTransform(0,0,1.496,1.496);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(66,66,66,0.008)").s().p("AnAHBQi6i6AAkHQAAkGC6i6QC6i6EGAAQEHAAC6C6QC6C6AAEGQAAEHi6C6Qi6C6kHAAQkGAAi6i6g");
	this.shape.setTransform(-28.6,2.4);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.b6, new cjs.Rectangle(-92.1,-61.1,127,127), null);


(lib.b5 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b5_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(-47.9,-5.1);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b5, new cjs.Rectangle(-47.9,-12.2,60.1,32.2), null);


(lib.b4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b4_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(-47.9,-10.6);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b4, new cjs.Rectangle(-47.9,-12.2,60.1,26.7), null);


(lib.b3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b3_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(11.4,-5);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b3, new cjs.Rectangle(-12.2,-12.2,56.5,32.3), null);


(lib.b2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b2_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(11.7,-15.2);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b2, new cjs.Rectangle(-12.2,-15.2,60.1,27.4), null);


(lib.b1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b1_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(13.9,-14.3);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b1, new cjs.Rectangle(-12.2,-14.3,62.3,26.5), null);


(lib.b0 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b0_label();
	this.name_txt.parent = this;
	this.name_txt.setTransform(-51.1,-13);

	this.circle = new lib.circle_shape();
	this.circle.parent = this;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.b0, new cjs.Rectangle(-51.1,-13,63.3,25.2), null);


(lib.MainUI = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 上层按钮
	this.big_label_no_rgb = new lib.big_label_no_rgb();
	this.big_label_no_rgb.parent = this;
	this.big_label_no_rgb.setTransform(171.6,236.1);

	this.b6 = new lib.b6();
	this.b6.parent = this;
	this.b6.setTransform(201.1,149.1);

	this.b5 = new lib.b5();
	this.b5.parent = this;
	this.b5.setTransform(59.2,187.9);

	this.b4 = new lib.b4();
	this.b4.parent = this;
	this.b4.setTransform(124.7,261.5);

	this.b3 = new lib.b3();
	this.b3.parent = this;
	this.b3.setTransform(256.4,244.3);

	this.b2 = new lib.b2();
	this.b2.parent = this;
	this.b2.setTransform(280.5,114.8);

	this.b1 = new lib.b1();
	this.b1.parent = this;
	this.b1.setTransform(216.4,36.7);

	this.b0 = new lib.b0();
	this.b0.parent = this;
	this.b0.setTransform(92.7,51.9);

	this.big_label_rgb = new lib.big_label_rgb();
	this.big_label_rgb.parent = this;
	this.big_label_rgb.setTransform(171.6,236.1);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.big_label_rgb},{t:this.b0},{t:this.b1},{t:this.b2},{t:this.b3},{t:this.b4},{t:this.b5},{t:this.b6},{t:this.big_label_no_rgb}]}).wait(1));

	// line_top
	this.line_top = new lib.line();
	this.line_top.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.line_top).wait(1));

	// icon
	this.icon = new lib.icon();
	this.icon.parent = this;
	this.icon.setTransform(173,149.5);

	this.timeline.addTween(cjs.Tween.get(this.icon).wait(1));

	// 中间圆
	this.big_circle_hui = new lib.big_circle_hui();
	this.big_circle_hui.parent = this;
	this.big_circle_hui.setTransform(172.8,149.8);

	this.big_circle = new lib.big_circle();
	this.big_circle.parent = this;
	this.big_circle.setTransform(172.8,149.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.big_circle},{t:this.big_circle_hui}]}).wait(1));

	// 下层按钮
	this.b8 = new lib.b8();
	this.b8.parent = this;
	this.b8.setTransform(246.4,190.5);

	this.b7 = new lib.b7();
	this.b7.parent = this;
	this.b7.setTransform(91.9,136.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.b7},{t:this.b8}]}).wait(1));

	// line_bottom
	this.line_bottom = new lib.line();
	this.line_bottom.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.line_bottom).wait(1));

}).prototype = getMCSymbolPrototype(lib.MainUI, new cjs.Rectangle(11.3,22.4,317.1,253.6), null);


// stage content:
(lib.netbuttonskin = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(23,165.2,487,374.9);
// library properties:
lib.properties = {
	width: 340,
	height: 340,
	fps: 60,
	color: "#FFFFFF",
	opacity: 1.00,
	manifest: [
		{src:"images/course_dev_icon.png?1513837037955", id:"course_dev_icon"}
	],
	preloads: []
};




})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{}, AdobeAn = AdobeAn||{});
var lib, images, createjs, ss, AdobeAn;