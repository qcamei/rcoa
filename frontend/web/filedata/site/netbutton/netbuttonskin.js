(function (lib, img, cjs, ss) {

var p; // shortcut to reference prototypes

// library properties:
lib.properties = {
	width: 340,
	height: 370,
	fps: 60,
	color: "#FFFFFF",
	manifest: [
		{src:"images/course_dev_icon.png?1491893283683", id:"course_dev_icon"}
	]
};



// symbols:



(lib.course_dev_icon = function() {
	this.initialize(img.course_dev_icon);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,126,127);


(lib.line = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = null;


(lib.icon = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.course_dev_icon();
	this.instance.setTransform(-63,-63.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-63,-63.5,126,127);


(lib.circle_shape = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f().s("#FFFFFF").ss(1,1,1).p("AB1AAQAAAwgjAiQgiAjgwAAQgvAAgjgjQgigiAAgwQAAgvAigjQAjgiAvAAQAwAAAiAiQAjAjAAAvg");

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AhRBSQgjgiAAgwQAAgvAjgiQAigjAvAAQAwAAAiAjQAiAiAAAvQAAAwgiAiQgiAigwAAQgvAAgigig");

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12.7,-12.7,25.4,25.4);


(lib.big_label_rgb = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#93BCDA").s().p("AgJAeIAAgvIgPAAIAAgNIAPAAIAAgVIANgEIAAAZIAVAAIAAANIgVAAIAAAtQAAAIADAEQADAEAGAAQAGAAADgDIAAANQgFACgIAAQgVAAAAgag");
	this.shape.setTransform(46,13);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#93BCDA").s().p("AAVAsIAAgvQAAgbgTAAQgIAAgHAHQgGAIgBALIAAAwIgPAAIAAhVIAPAAIAAAPIABAAQAJgRARAAQAPAAAHAJQAHAKAAARIAAAzg");
	this.shape_1.setTransform(38.2,14);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#93BCDA").s().p("AgaAhQgKgMAAgVQAAgSALgNQAMgNAOAAQARAAAKALQAJALAAAUIAAAFIg6AAQABAOAGAIQAIAHALAAQAPAAALgJIAAAOQgLAIgTAAQgRAAgKgMgAgNgZQgGAHgCALIArAAQAAgMgFgGQgGgGgKAAQgHAAgHAGg");
	this.shape_2.setTransform(28.7,14.1);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#93BCDA").s().p("AAuAsIAAgwQAAgOgEgGQgEgGgKAAQgJAAgGAIQgGAIAAALIAAAvIgNAAIAAgxQAAgZgTAAQgJAAgFAHQgGAIAAAMIAAAvIgQAAIAAhVIAQAAIAAAOIAAAAQAJgQASAAQAJAAAGAFQAFAFADAIQAJgSATAAQAdAAAAAjIAAA0g");
	this.shape_3.setTransform(16.7,14);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#93BCDA").s().p("AgnBAIAAh9IAPAAIAAAPIABAAQAKgRARAAQARAAAJAMQAKAMAAATQAAAUgLANQgLANgRAAQgPAAgJgOIgBAAIAAA0gAgQgqQgIAIAAANIAAALQABAKAGAGQAHAHAKAAQAKAAAIgJQAGgIAAgQQAAgOgGgIQgGgIgLAAQgKAAgHAIg");
	this.shape_4.setTransform(4.3,16);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#93BCDA").s().p("AgeAhQgLgNAAgUQgBgTAMgMQANgNASAAQATAAALAMQALAMAAAUQAAAUgMAMQgLANgTAAQgSAAgMgMgAgTgWQgHAJAAANQAAAPAHAJQAIAIALAAQANAAAHgIQAHgJAAgPQAAgOgHgJQgHgIgNAAQgLAAgIAJg");
	this.shape_5.setTransform(-6.1,14.1);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#93BCDA").s().p("AgGA/IAAh9IANAAIAAB9g");
	this.shape_6.setTransform(-13.4,12.1);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#93BCDA").s().p("AgaAhQgLgMABgVQgBgSAMgNQAMgNAOAAQASAAAJALQAJALAAAUIAAAFIg6AAQABAOAGAIQAIAHALAAQAOAAAMgJIAAAOQgLAIgTAAQgQAAgLgMgAgNgZQgGAHgCALIArAAQAAgMgFgGQgGgGgKAAQgHAAgHAGg");
	this.shape_7.setTransform(-20,14.1);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#93BCDA").s().p("AgHArIghhVIARAAIAVA8IACAMIAAAAQAAgGACgGIAWg8IARAAIgjBVg");
	this.shape_8.setTransform(-28.8,14.1);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#93BCDA").s().p("AgaAhQgLgMAAgVQAAgSAMgNQALgNAQAAQAQAAAKALQAKALAAAUIAAAFIg7AAQAAAOAIAIQAHAHALAAQAPAAAMgJIAAAOQgMAIgTAAQgQAAgLgMgAgMgZQgHAHgCALIAsAAQgBgMgGgGQgFgGgJAAQgIAAgGAGg");
	this.shape_9.setTransform(-37.5,14.1);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#93BCDA").s().p("AgwA8IAAh3IAjAAQAZAAATAQQASAQAAAbQAAAagSARQgTARgaAAgAggAuIASAAQAVAAANgMQAMgNAAgVQAAgWgNgLQgNgMgUAAIgSAAg");
	this.shape_10.setTransform(-47.8,12.4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#1A7FB3").s().p("Ag+BSQAwgNAWgOQgYgWgLghQgWA1glAjIgJgOQArgnAVhHIg2AAIAAgPIAPglIAPAEIgNAhIApAAQAEgUAEgYIAQACIgIAqIBqAAIAAAPIhtAAIgIAYIBfAAIAAAMQgNAggcAXQAcARAkAGIgNARQgfgLgggUQgXARgvAPQgGgIgGgGgAAUAuQAZgUALgaIhFAAQAJAbAYATgAAahSIAIgLQATAMASANIgLANQgRgOgRgNg");
	this.shape_11.setTransform(10.5,-8.4);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#1A7FB3").s().p("AhdBMQAegPAKgSQAJgMAAggIgzAAIAAgPIAzAAIAAg5IgqAAIAAgPICtAAIAAAPIgoAAIAAA5IAxAAIAAAPIgxAAIAABXIgPAAIAAhXIg7AAQgBAigKASQgKATgiATIgLgNgAgbgQIA7AAIAAg5Ig7AAg");
	this.shape_12.setTransform(-9.4,-7.7);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-56.3,-24.6,107.5,49.3);


(lib.big_label_no_rgb = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(112,112,112,0.6)").s().p("AgJAeIAAgvIgPAAIAAgNIAPAAIAAgVIANgEIAAAZIAVAAIAAANIgVAAIAAAtQAAAIADAEQADAEAGAAQAGAAADgDIAAANQgFACgIAAQgVAAAAgag");
	this.shape.setTransform(46,13);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("rgba(112,112,112,0.6)").s().p("AAVAsIAAgvQAAgbgTAAQgIAAgHAHQgGAIgBALIAAAwIgPAAIAAhVIAPAAIAAAPIABAAQAJgRARAAQAPAAAHAJQAHAKAAARIAAAzg");
	this.shape_1.setTransform(38.2,14);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("rgba(112,112,112,0.6)").s().p("AgaAhQgKgMAAgVQAAgSALgNQAMgNAOAAQARAAAKALQAJALAAAUIAAAFIg6AAQABAOAGAIQAIAHALAAQAPAAALgJIAAAOQgLAIgTAAQgRAAgKgMgAgNgZQgGAHgCALIArAAQAAgMgFgGQgGgGgKAAQgHAAgHAGg");
	this.shape_2.setTransform(28.7,14.1);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("rgba(112,112,112,0.6)").s().p("AAuAsIAAgwQAAgOgEgGQgEgGgKAAQgJAAgGAIQgGAIAAALIAAAvIgNAAIAAgxQAAgZgTAAQgJAAgFAHQgGAIAAAMIAAAvIgQAAIAAhVIAQAAIAAAOIAAAAQAJgQASAAQAJAAAGAFQAFAFADAIQAJgSATAAQAdAAAAAjIAAA0g");
	this.shape_3.setTransform(16.7,14);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("rgba(112,112,112,0.6)").s().p("AgnBAIAAh9IAPAAIAAAPIABAAQAKgRARAAQARAAAJAMQAKAMAAATQAAAUgLANQgLANgRAAQgPAAgJgOIgBAAIAAA0gAgQgqQgIAIAAANIAAALQABAKAGAGQAHAHAKAAQAKAAAIgJQAGgIAAgQQAAgOgGgIQgGgIgLAAQgKAAgHAIg");
	this.shape_4.setTransform(4.3,16);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("rgba(112,112,112,0.6)").s().p("AgeAhQgLgNAAgUQgBgTAMgMQANgNASAAQATAAALAMQALAMAAAUQAAAUgMAMQgLANgTAAQgSAAgMgMgAgTgWQgHAJAAANQAAAPAHAJQAIAIALAAQANAAAHgIQAHgJAAgPQAAgOgHgJQgHgIgNAAQgLAAgIAJg");
	this.shape_5.setTransform(-6.1,14.1);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("rgba(112,112,112,0.6)").s().p("AgGA/IAAh9IANAAIAAB9g");
	this.shape_6.setTransform(-13.4,12.1);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("rgba(112,112,112,0.6)").s().p("AgaAhQgLgMABgVQgBgSAMgNQAMgNAOAAQASAAAJALQAJALAAAUIAAAFIg6AAQABAOAGAIQAIAHALAAQAOAAAMgJIAAAOQgLAIgTAAQgQAAgLgMgAgNgZQgGAHgCALIArAAQAAgMgFgGQgGgGgKAAQgHAAgHAGg");
	this.shape_7.setTransform(-20,14.1);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("rgba(112,112,112,0.6)").s().p("AgHArIghhVIARAAIAVA8IACAMIAAAAQAAgGACgGIAWg8IARAAIgjBVg");
	this.shape_8.setTransform(-28.8,14.1);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("rgba(112,112,112,0.6)").s().p("AgaAhQgLgMAAgVQAAgSAMgNQALgNAQAAQAQAAAKALQAKALAAAUIAAAFIg7AAQAAAOAIAIQAHAHALAAQAPAAAMgJIAAAOQgMAIgTAAQgQAAgLgMgAgMgZQgHAHgCALIAsAAQgBgMgGgGQgFgGgJAAQgIAAgGAGg");
	this.shape_9.setTransform(-37.5,14.1);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("rgba(112,112,112,0.6)").s().p("AgwA8IAAh3IAjAAQAZAAATAQQASAQAAAbQAAAagSARQgTARgaAAgAggAuIASAAQAVAAANgMQAMgNAAgVQAAgWgNgLQgNgMgUAAIgSAAg");
	this.shape_10.setTransform(-47.8,12.4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#707070").s().p("Ag+BSQAwgNAWgOQgYgWgLghQgWA1glAjIgJgOQArgnAVhHIg2AAIAAgPIAPglIAPAEIgNAhIApAAQAEgUAEgYIAQACIgIAqIBqAAIAAAPIhtAAIgIAYIBfAAIAAAMQgNAggcAXQAcARAkAGIgNARQgfgLgggUQgXARgvAPQgGgIgGgGgAAUAuQAZgUALgaIhFAAQAJAbAYATgAAahSIAIgLQATAMASANIgLANQgRgOgRgNg");
	this.shape_11.setTransform(10.5,-8.4);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#707070").s().p("AhdBMQAegPAKgSQAJgMAAggIgzAAIAAgPIAzAAIAAg5IgqAAIAAgPICtAAIAAAPIgoAAIAAA5IAxAAIAAAPIgxAAIAABXIgPAAIAAhXIg7AAQgBAigKASQgKATgiATIgLgNgAgbgQIA7AAIAAg5Ig7AAg");
	this.shape_12.setTransform(-9.4,-7.7);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-56.3,-24.6,107.5,49.3);


(lib.big_circle_hui = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#A2B6BC").s().de(-61.7,-61.7,123.5,123.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-61.7,-61.7,123.5,123.5);


(lib.big_circle = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#3F98D1").s().de(-61.7,-61.7,123.5,123.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-61.7,-61.7,123.5,123.5);


(lib.b6_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AhMBDQARgVAAguIAAg6IA/AAIgGgNIAKgGIAKATIA6AAIAAALIh7AAIAAAvQAAAzgUAbIgJgLgAg0BCQAfgEAVgKQgPgLgLgPIgMAAIAAgKIBfAAIAAAKQgOAOgTAMQAXAJAeADIgGAMQgjgFgYgNQgYANgiAGIgGgLgAAMAuQARgIAMgMIg2AAQAMAMANAIgAAiAIIAAgGIgqAAIAAAGIgMAAIAAgcIgXAAIAAgLIAXAAIAAgMIAMAAIAAAMIAqAAIAAgNIAMAAIAAANIAdAAIAAALIgdAAIAAAcgAgIgGIAqAAIAAgOIgqAAg");
	this.shape.setTransform(25.9,13.3);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AhMA+IAUgTIAAgzIgTAAIAAgLIAfAAIAABAQAJAQAWAAIAjABIA3gBIgEAMIg0AAIgkgBQgZAAgLgSQgHAGgMAPgAAiA3IAAgwIggAAQgDAggWAQIgJgKQATgMAEgaIgZAAIAAgJIAaAAIABghIgUAAIAAgLIAUAAIAAgcIAKAAIAAAcIAfAAIAAgcIANAAIAAAcIAXAAIAAALIgXAAIAAAhIAdAAIAAAJIgdAAIAAAwgAADgCIAfAAIAAghIgfAAIAAAhgAhEhBIAKgHIAWAdIgMAIQgIgPgMgPg");
	this.shape_1.setTransform(10,13.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b5_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AhNBDQAagMASgRIgHAAIAAgrIALAAIAAAmQAKgLAHgOIALAGQgXApgtAWIgIgKgAgSBCQAZgKALgNQAKgMAAgaIAAgVIAMAAIAAAWQAAASgEAMIArAcIgIALQgTgPgVgNIgDADQgLAPgbALIgIgKgAhOAoQAJgQAHgWIALAEQgIAXgIAQIgLgFgAA6AqIAAhEIgvAAIAABEIgLAAIAAhPIAWAAIAEgUIgfAAIAAgKIBSAAIAAAKIgmAAIgFAUIAkAAIAABPgAhMgJIAAgLIAKAAIAAgpIALAAIAAApIAQAAIAAg4IAMAAIAAAZIAVAAIAAAKIgVAAIAAAVIAXAAIAAALg");
	this.shape.setTransform(26,13.5);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgiBDQAvgTADgyIABgrIAMAAQAAAsgCAJIAKAAIAAArQAAAKAIgBIAJAAQAJABACgJIABgVIAMAEIgCAWQgCAOgRAAIgOAAQgSAAAAgSIAAghQgLAngoASIgIgKgAg3BLIAAhEIgSASIgEgNQAYgTAOgZIglAAIAAgMIAzAAIAAAMQgIAOgKANIAAAHIAGgEIAVARIgJAIIgSgTIAABHgAAzAbIAAhTIg0AAIAABTIgMAAIAAheIBMAAIAABegAg9hHIALgFIAMAVIgLAHIgMgXg");
	this.shape_1.setTransform(10.1,13.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b4_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AA2BHIAAgIIh3AAIAAhpIAMAAIAABeIBrAAIAAheIAMAAIAABxgAglAnIAAhUIBLAAIAABTIgMAAIAAgIIgzAAIAAAJgAAFATIAVAAIAAgVIgVAAgAgZATIAVAAIAAgVIgVAAgAAFgMIAVAAIAAgXIgVAAgAgZgMIAVAAIAAgXIgVAAgAhKg8IAAgKICVAAIAAAKg");
	this.shape.setTransform(26,13.8);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgNBCQAfgdABg4IAAgNIgVAAIAAgMIAVAAIABggIAMAAIAAAgIAqAAQgBA5gCAkQgCAWgSABIgWgBIgCgOIAUACQALAAABgNQACgnAAgnIgdAAIgBANQAAA8gjAkIgJgLgAgOAyIg2AFIgFgNQAHgDAEgJQAHgQAJgVIgbAAIAAgMIBKAAIAAAMIgiAAQgNAfgKASIAmgDIgLgaIALgFQAKAVAIAVIgLAFIgDgFgAhCgzIAAgMIA/AAIAAAMg");
	this.shape_1.setTransform(9.8,13.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b3_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AgHA8IAAhpIgiAAIAAgOIBTAAIAAAOIgjAAIAABpg");
	this.shape.setTransform(26.2,12.9);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AglA8IAAh3IAjAAQARAAAMAKQAKAJABASQgBARgNAKQgMALgRgBIgQAAIAAAtgAgVABIAPAAQAMAAAHgEQAIgHAAgMQAAgYgaAAIgQAAg");
	this.shape_1.setTransform(17.2,12.9);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#1A7FB3").s().p("AglA8IAAh3IAjAAQARAAAMAKQAKAJABASQgBARgNAKQgMALgRgBIgQAAIAAAtgAgVABIAPAAQAMAAAHgEQAIgHAAgMQAAgYgaAAIgQAAg");
	this.shape_2.setTransform(7.4,12.9);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,33,25.2);


(lib.b2_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AhBBHIAAhtIAxAAIAIgVIhCAAIAAgMICVAAIAAAMIhHAAIgHAVIBFAAIAABtIgMAAIAAgJIhrAAIAAAJgAAdA0IAZAAIAAhQIgZAAgAgRA0IAjAAIAAgUIgjAAgAg1A0IAZAAIAAhQIgZAAgAgRAWIAjAAIAAgWIgjAAgAgRgIIAjAAIAAgUIgjAAg");
	this.shape.setTransform(26,13.8);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgFBJIAAg6IhGAAIAAgMIBGAAIAAg/Ig+AAIAAgMICIAAIAAAMIg/AAIAAA/IBGAAIAAAMIhGAAIAAA6gAg8grIAKgHQASAVAMATIgLAIQgMgTgRgWgAASgKQARgSAOgWIAMAHQgSAYgPARg");
	this.shape_1.setTransform(10,13.7);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b1_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AgEBNIAAggIglAAIAAgLIAlAAIAAg8QgUAmgpAkIgLgJQAtgkAWgnIg/AAIAAgMIBEAAIAAgcIAKAAIAAAcIBEAAIAAAMIhAAAQAZAtAqAbIgLALQgmgegWgrIAAA8IAkAAIAAALIgkAAIAAAgg");
	this.shape.setTransform(26,13.3);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AAiBMIAAiLIAoAAIAABgQgBANgHACQgEABgMAAQAAgGgDgGQAQADAAgHIAAhWIgSAAIAACBgAhJBEQALgSAAghIAAhTIAmAAIAAB+QAAAOgOAAIgOAAIgCgLIANABQAGAAAAgGIAAglIgRAAQAAAjgMAUIgJgIgAg0ALIARAAIAAgcIgRAAgAg0gaIARAAIAAgeIgRAAgAARAwIgjAFIgDgKQAJgLAKghIgSAAIAAgKIAUAAIAAgcIgRAAIAAgKIARAAIAAgaIAKAAIAAAaIARAAIAAAKIgRAAIAAAcIATAAIAAAKIgVAAQgIAbgHAQIAVgDIgIgXIAKgDQAKAaAFATIgLADIgDgNg");
	this.shape_1.setTransform(9.6,13.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b0_label = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1A7FB3").s().p("AAMBKIgDgOQASACAPAAQAOAAABgPIADhUIg1AAQgHASgIANIgLgGQATgdAJghIANAEIgJAWIA9AAIgEBiQgBAZgZAAIgggBgAhJA3IBAgJIgBAMQgZADgjAHgAhEAWQAMgMANgTIgbABIgDgKQASgXAOghIAMAFQgPAcgPAWIAWgBIANgWIAMAGQgUAhgTAWIAngFIgBALIg0AIgAABgFIAKgGQAPARALARIgLAHQgLgRgOgSg");
	this.shape.setTransform(25.7,13.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#1A7FB3").s().p("AgWA/QAagMALgPQAKgNAAgXIAAgXIAMAAIAAAXQAAARgFANQAZAQAUAQIgJAKQgTgQgWgQIgDAFQgMAQgZAMIgJgKgAhJBGIgCgMIAPABQAJAAAAgKIAAg8IgZAAIAAgLIAfAAIgUgWIAHgHIALAKIARgUIgsAAIAAgLIA6AAIAAALIgXAcIAFAFIgGAGIAcAAIAAAKQgCAMgGAOIgKgDQAEgKADgMIgQAAIAABAQAAARgSAAgAA6AjIAAhAIg1AAIAABAIgKAAIAAhLIAZAAIAEgWIgiAAIAAgKIBXAAIAAAKIgpAAIgEAWIAlAAIAABLg");
	this.shape_1.setTransform(10,13.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,36.2,25.2);


(lib.b8 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.circle = new lib.circle_shape();
	this.circle.setTransform(0,0,0.641,0.641);
	this.circle.alpha = 0.48;

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("Ag0A1QgWgXABgeQgBgdAWgXQAXgWAdABQAegBAXAWQAWAXgBAdQABAegWAXQgXAWgegBQgdABgXgWg");

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-7.8,-7.8,15.6,15.6);


(lib.b7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.circle = new lib.circle_shape();
	this.circle.setTransform(0,0,0.641,0.641);
	this.circle.alpha = 0.48;

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("Ag0A1QgWgXABgeQgBgdAWgXQAXgWAdABQAegBAXAWQAWAXgBAdQABAegWAXQgXAWgegBQgdABgXgWg");

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-7.8,-7.8,15.6,15.6);


(lib.b6 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b6_label();
	this.name_txt.setTransform(-55.1,-3.4);
	this.name_txt.shadow = new cjs.Shadow("#FFFFFF",0,0,4);

	this.circle = new lib.circle_shape();
	this.circle.setTransform(0,0,1.496,1.496);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-60.1,-18.2,78.4,47.9);


(lib.b5 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b5_label();
	this.name_txt.setTransform(-47.9,-5.1);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-47.9,-12.2,60.1,32.2);


(lib.b4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b4_label();
	this.name_txt.setTransform(-47.9,-10.6);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-47.9,-12.2,60.1,26.7);


(lib.b3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b3_label();
	this.name_txt.setTransform(11.4,-5);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12.2,-12.2,56.5,32.3);


(lib.b2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b2_label();
	this.name_txt.setTransform(11.7,-15.2);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12.2,-15.2,60.1,27.4);


(lib.b1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b1_label();
	this.name_txt.setTransform(13.9,-14.3);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12.2,-14.3,62.3,26.5);


(lib.b0 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.name_txt = new lib.b0_label();
	this.name_txt.setTransform(-51.1,-13);

	this.circle = new lib.circle_shape();

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.circle},{t:this.name_txt}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-51.1,-13,63.3,25.2);


(lib.MainUI = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 上层按钮
	this.big_label_no_rgb = new lib.big_label_no_rgb();
	this.big_label_no_rgb.setTransform(171.6,236.1);

	this.b6 = new lib.b6();
	this.b6.setTransform(201.1,149.1);

	this.b5 = new lib.b5();
	this.b5.setTransform(59.2,187.9);

	this.b4 = new lib.b4();
	this.b4.setTransform(124.7,261.5);

	this.b3 = new lib.b3();
	this.b3.setTransform(256.4,244.3);

	this.b2 = new lib.b2();
	this.b2.setTransform(280.5,114.8);

	this.b1 = new lib.b1();
	this.b1.setTransform(216.4,36.7);

	this.b0 = new lib.b0();
	this.b0.setTransform(92.7,51.9);

	this.big_label_rgb = new lib.big_label_rgb();
	this.big_label_rgb.setTransform(171.6,236.1);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.big_label_rgb},{t:this.b0},{t:this.b1},{t:this.b2},{t:this.b3},{t:this.b4},{t:this.b5},{t:this.b6},{t:this.big_label_no_rgb}]}).wait(1));

	// line_top
	this.line_top = new lib.line();

	this.timeline.addTween(cjs.Tween.get(this.line_top).wait(1));

	// icon
	this.icon = new lib.icon();
	this.icon.setTransform(173,149.5);

	this.timeline.addTween(cjs.Tween.get(this.icon).wait(1));

	// 中间圆
	this.big_circle_hui = new lib.big_circle_hui();
	this.big_circle_hui.setTransform(172.8,149.8);

	this.big_circle = new lib.big_circle();
	this.big_circle.setTransform(172.8,149.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.big_circle},{t:this.big_circle_hui}]}).wait(1));

	// 下层按钮
	this.b8 = new lib.b8();
	this.b8.setTransform(246.4,190.5);

	this.b7 = new lib.b7();
	this.b7.setTransform(91.9,136.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.b7},{t:this.b8}]}).wait(1));

	// line_bottom
	this.line_bottom = new lib.line();

	this.timeline.addTween(cjs.Tween.get(this.line_bottom).wait(1));

	// back
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(255,255,255,0.008)").s().p("A6jYDMAAAgwFMA1HAAAMAAAAwFg");
	this.shape.setTransform(170,153.9);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,340,307.9);


// stage content:
(lib.netbuttonskin = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(23,180.2,487,312.7);

})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{});
var lib, images, createjs, ss;