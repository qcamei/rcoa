(function (lib, img, cjs, ss) {

var p; // shortcut to reference prototypes

// library properties:
lib.properties = {
	width: 250,
	height: 250,
	fps: 60,
	color: "#FFFFFF",
	manifest: [
		{src:"images/dev.png?1484634359958", id:"dev"},
		{src:"images/movie.png?1484634359958", id:"movie"},
		{src:"images/movie_name.png?1484634359958", id:"movie_name"},
		{src:"images/pro.png?1484634359958", id:"pro"},
		{src:"images/pro_name.png?1484634359958", id:"pro_name"},
		{src:"images/shoot_name.png?1484634359958", id:"shoot_name"},
		{src:"images/shootpng.png?1484634359958", id:"shootpng"}
	]
};



// symbols:



(lib.dev = function() {
	this.initialize(img.dev);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,78,105);


(lib.movie = function() {
	this.initialize(img.movie);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,41,44);


(lib.movie_name = function() {
	this.initialize(img.movie_name);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,37,44);


(lib.pro = function() {
	this.initialize(img.pro);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,44,44);


(lib.pro_name = function() {
	this.initialize(img.pro_name);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,47,21);


(lib.shoot_name = function() {
	this.initialize(img.shoot_name);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,39,45);


(lib.shootpng = function() {
	this.initialize(img.shootpng);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,39,43);


(lib.tw_shpae = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.pro();
	this.instance.setTransform(-22,-22);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-22,-22,44,44);


(lib.shoot_shape = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.shootpng();
	this.instance.setTransform(-19.5,-21.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-19.5,-21.5,39,43);


(lib.rayshape = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f().s("rgba(255,255,255,0.498)").ss(1,1,1).p("AABCfIAArMAABCsIKQGCAABCnIqRGH");
	this.shape.setTransform(0,-16.8);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-66.7,-73.6,133.5,113.6);


(lib.name_min2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.pro_name();
	this.instance.setTransform(-23.5,-10.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-23.5,-10.5,47,21);


(lib.name_min1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.movie_name();
	this.instance.setTransform(-18.5,-22);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-18.5,-22,37,44);


(lib.name_min0 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.shoot_name();
	this.instance.setTransform(-19.5,-22.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-19.5,-22.5,39,45);


(lib.name_big = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#3F99CE").s().p("AgJAeIAAgvIgPAAIAAgNIAPAAIAAgUIANgGIAAAaIAVAAIAAANIgVAAIAAAtQAAAIADAEQADAEAGAAQAGAAADgDIAAANQgFADgIAAQgVAAAAgbg");
	this.shape.setTransform(102.4,37.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#3F99CE").s().p("AAVAsIAAgvQAAgbgUAAQgHAAgHAHQgGAIAAALIAAAwIgQAAIAAhVIAQAAIAAAPIAAAAQAKgRAQAAQAPAAAHAJQAHAKAAARIAAAzg");
	this.shape_1.setTransform(94.6,38.1);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#3F99CE").s().p("AgaAhQgLgMAAgVQAAgSAMgNQAMgNAPAAQARAAAJALQAKALAAAUIAAAFIg7AAQAAAOAIAIQAHAHALAAQAOAAAMgJIAAAOQgLAIgTAAQgQAAgLgMgAgNgZQgGAHgCALIArAAQAAgMgGgGQgFgGgJAAQgIAAgHAGg");
	this.shape_2.setTransform(85.1,38.2);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#3F99CE").s().p("AAuAsIAAgwQAAgOgEgGQgEgGgKAAQgJAAgGAIQgGAIAAALIAAAvIgNAAIAAgxQAAgZgTAAQgJAAgFAHQgGAIAAAMIAAAvIgQAAIAAhVIAQAAIAAAOIAAAAQAJgQASAAQAJAAAGAFQAFAFADAIQAJgSATAAQAdAAAAAjIAAA0g");
	this.shape_3.setTransform(73.1,38.1);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#3F99CE").s().p("AgnBAIAAh9IAQAAIAAAPIAAAAQAKgRASAAQAQAAAKAMQAJAMAAATQAAAUgKANQgMANgRAAQgPAAgJgOIAAAAIAAA0gAgRgqQgGAIAAANIAAALQgBAKAHAGQAHAHAKAAQALAAAGgJQAHgIAAgQQAAgOgGgIQgHgIgKAAQgKAAgIAIg");
	this.shape_4.setTransform(60.7,40.1);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#3F99CE").s().p("AgeAhQgMgNABgUQAAgTAMgMQALgNATAAQATAAAMAMQALAMgBAUQABAUgMAMQgNANgSAAQgTAAgLgMgAgTgWQgHAJAAANQAAAPAHAJQAIAIALAAQANAAAHgIQAHgJgBgPQABgOgHgJQgHgIgNAAQgLAAgIAJg");
	this.shape_5.setTransform(50.3,38.2);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#3F99CE").s().p("AgGBAIAAh+IANAAIAAB+g");
	this.shape_6.setTransform(43,36.2);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#3F99CE").s().p("AgaAhQgLgMAAgVQAAgSAMgNQALgNAQAAQAQAAAKALQAKALAAAUIAAAFIg7AAQAAAOAIAIQAHAHALAAQAPAAALgJIAAAOQgLAIgTAAQgQAAgLgMgAgMgZQgHAHgCALIAsAAQgBgMgGgGQgFgGgJAAQgIAAgGAGg");
	this.shape_7.setTransform(36.4,38.2);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#3F99CE").s().p("AgHArIghhVIARAAIAVA8IACAMIAAAAQAAgGACgGIAWg8IARAAIgjBVg");
	this.shape_8.setTransform(27.6,38.2);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#3F99CE").s().p("AgaAhQgKgMAAgVQAAgSALgNQALgNAPAAQARAAAKALQAJALAAAUIAAAFIg6AAQABAOAGAIQAIAHALAAQAPAAAMgJIAAAOQgMAIgTAAQgRAAgKgMgAgNgZQgGAHgCALIAsAAQgBgMgFgGQgGgGgKAAQgHAAgHAGg");
	this.shape_9.setTransform(18.9,38.2);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#3F99CE").s().p("AgwA8IAAh3IAjAAQAZAAATAQQASAQAAAbQAAAagSARQgTARgaAAgAggAuIASAAQAVAAANgNQAMgMAAgVQAAgXgNgKQgNgMgUAAIgSAAg");
	this.shape_10.setTransform(8.6,36.5);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#3F99CE").s().p("Ag/BSQAxgNAVgOQgXgWgLghQgWA1gkAjIgLgOQAtgnAUhHIg2AAIAAgPIAOglIARAEIgPAhIAqAAQAEgUAEgYIARACIgIAqIBqAAIAAAPIhvAAIgHAYIBfAAIAAAMQgNAggbAXQAbARAkAGIgNARQgfgLgggUQgXARgwAPQgFgIgHgGgAAUAuQAZgUAKgaIhEAAQAJAbAYATgAAZhSIAJgLQATAMASANIgLANQgRgOgSgNg");
	this.shape_11.setTransform(66.4,16.2);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#3F99CE").s().p("AhdBLQAegPAKgQQAIgNACgfIgzAAIAAgQIAzAAIAAg4IgrAAIAAgQICtAAIAAAQIgnAAIAAA4IAvAAIAAAQIgvAAIAABWIgRAAIAAhWIg7AAQgBAhgJASQgKATgiATIgLgOgAgcgQIA7AAIAAg4Ig7AAg");
	this.shape_12.setTransform(46.4,16.9);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,107.5,48.8);


(lib.movie_shape = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.movie();
	this.instance.setTransform(-20.5,-22);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-20.5,-22,41,44);


(lib.icon_big = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.instance = new lib.dev();
	this.instance.setTransform(-39,-52.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-39,-52.5,78,105);


(lib.circle_bg = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#3F99CE").s().p("AhrLqQjyggi0i0QjbjcgBk3QABk1DbjbQDbjcE2AAQE2AADbDcQDdDbAAE1QAAE3jdDcQi0C0jyAgg");
	this.shape.setTransform(75,74.7);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,150,149.3);


(lib.icon_min2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.icon = new lib.tw_shpae();

	this.timeline.addTween(cjs.Tween.get(this.icon).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(63,153,206,0.02)").s().p("AJpAAYh/D9jqCej9ACYj8ADjsiZiDj7IJmmog");
	this.shape.setTransform(1.1,-1.9,1.046,0.906);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-63.5,-39.4,129.2,75.2);


(lib.icon_min1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.icon = new lib.movie_shape();

	this.timeline.addTween(cjs.Tween.get(this.icon).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(63,153,206,0.02)").s().p("AlBnuYDrAEDUB/B2DLYB1DJACD5hyDNIo0k6YADgGAAgIgDgGYgEgGgGgEgHAAg");
	this.shape.setTransform(0.4,4.3,1.124,1.124);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-37.1,-51.4,75,111.4);


(lib.icon_min0 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 1
	this.icon = new lib.shoot_shape();

	this.timeline.addTween(cjs.Tween.get(this.icon).wait(1));

	// 图层 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("rgba(63,153,206,0.02)").s().p("AkUIVYh3jfAGkNCAjXYCAjaDmiHD9gFIAMLNYgCAAgCABgCACYgBADAAACABACg");
	this.shape.setTransform(-2.7,3.5,1.033,1.033);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-40.2,-51.6,75.1,110.3);


(lib.容器 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 小图标
	this.icon_min2 = new lib.icon_min2();
	this.icon_min2.setTransform(121.3,143.4);

	this.icon_min1 = new lib.icon_min1();
	this.icon_min1.setTransform(158.8,81);

	this.icon_min0 = new lib.icon_min0();
	this.icon_min0.setTransform(86.3,81.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.icon_min0},{t:this.icon_min1},{t:this.icon_min2}]}).wait(1));

	// 射线
	this.ray = new lib.rayshape();
	this.ray.setTransform(122.2,103.3);

	this.timeline.addTween(cjs.Tween.get(this.ray).wait(1));

	// 大图标
	this.icon_big = new lib.icon_big();
	this.icon_big.setTransform(121.5,104.5);

	this.timeline.addTween(cjs.Tween.get(this.icon_big).wait(1));

	// 底
	this.circle = new lib.circle_bg();
	this.circle.setTransform(121.3,104.4,1,1,0,0,0,75,74.7);

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(46.1,29.6,150.6,149.6);


(lib.MainUI = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 小名称
	this.name_min2 = new lib.name_min2();
	this.name_min2.setTransform(122.8,197.6);

	this.name_min1 = new lib.name_min1();
	this.name_min1.setTransform(203.5,57.5);

	this.name_min0 = new lib.name_min0();
	this.name_min0.setTransform(47.3,52.2);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.name_min0},{t:this.name_min1},{t:this.name_min2}]}).wait(1));

	// container
	this.circle = new lib.容器();

	this.timeline.addTween(cjs.Tween.get(this.circle).wait(1));

	// 名称
	this.title = new lib.name_big();
	this.title.setTransform(66.8,190.1);

	this.timeline.addTween(cjs.Tween.get(this.title).wait(1));

	// 底色
	this.circle_1 = new lib.circle_bg();
	this.circle_1.setTransform(121.3,104.4,1,1,0,0,0,75,74.7);

	this.timeline.addTween(cjs.Tween.get(this.circle_1).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,250,250);


// stage content:
(lib.buuton = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层 8
	this.instance = new lib.MainUI();

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(152.8,154.6,194.3,209.3);

})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{});
var lib, images, createjs, ss;