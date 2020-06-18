var l = $("#l");
var lo = $("#lo");
var _t = 30;
var _l = 0;

$(function(){
	$(window).resize();
	AnimateCircles();
	OnResize();
});

function OnResize(){
	setPos();
	$('.global').height($('#fullpage').outerHeight());

	ww = $(window).width();
	wh = $(window).height();
	_50ww = (ww/2);
	_75wh = (wh/4 *3);

	var w = $(window).width(), h = $(window).height();
	w /= 2;
	w *= .75;
	var ddiag = 1/Q_rsqrt(w*w + h*h);
	$('.circles svg').removeClass('deactivated');
	$('.circles svg').each(function(){
		if($(this).width()/2 > ddiag)
			$(this).addClass('deactivated');
	});
}

var ww = 1920;
var wh = 1080;
var _50ww = (ww/2);
var _75wh = (wh/4 *3);

$(function(){
	$(window).bind('resize orientationchange', OnResize);
});

var stopAnimate = false;
$('#l').on('error', function(){
	stopAnimate = true;
	console.log("Err:Logo");
	$('#l').hide();
	$('#lo').hide();
});

$('#lo').on('error', function(){
	stopAnimate = true;
	console.log("Err:O-Logo");
	$('#l').hide();
	$('#lo').hide();
});

var cX, cY, len, _30len, cos, sin;

const bytes = new ArrayBuffer(Float32Array.BYTES_PER_ELEMENT);
const floatView = new Float32Array(bytes);
const intView = new Uint32Array(bytes);
const threehalfs = 1.5;

function Q_rsqrt(number) {
	const x2 = number * 0.5;
	floatView[0] = number;
	intView[0] = 0x5f3759df - ( intView[0] >> 1 );
	let y = floatView[0];
	y = y * ( threehalfs - ( x2 * y * y ) );

	return y;
}

$(document).mousemove(function(e) {
	if(document.getElementsByTagName("body")[0].offsetWidth > 359 && !stopAnimate) {
		cX = e.pageX - _50ww;
		cY = e.pageY - _75wh;

		// len = Math.sqrt(Math.pow(cX, 2) + Math.pow(cY, 2));
		len = Q_rsqrt(Math.pow(cX, 2) + Math.pow(cY, 2));

		_30len = len/_75wh;
		if(_30len > 1)
			_30len = 1;

		cos = (cX/len);
		sin = (cY/len);

		_t = (30*_30len)*sin;
		_l = (30*_30len)*cos;

		setPos();
	}
});

function setPos() {
	l.css("top", _t);
	l.css("left", _l);
	lo.css("top", -_t);
	lo.css("left", -_l);
}
$('#menu_bg').click(function(){
	$(this).hide(300);
});
$('#menu_btn').click(function(){
	$('#menu_bg').show(300);
});

function AnimateCircles() {
	setTimeout(function(){
		var p = Math.random() * .1 + .9, mx = $('.circles svg:first').width(), mn = $('.circles svg:first').width();
		$('.circles svg').each(function(){
			if($(this).width() > mx)
				mx = $(this).width();
			if($(this).width() < mn)
				mn = $(this).width();
		});
		$('.circles svg:not(.deactivated)').each(function(){
			p += Math.random() * .1 - .05;
			$(this).css('opacity', 1-($(this).width()-mn)/(mx-mn));
			$(this).css('transform', 'translate(-50%, -50%) scale('+p+')');
			$(this).css('transform', 'translate(-50%, -50%) scale('+p+')');
		});
	},0);
	setTimeout(AnimateCircles, 1500);
}

$('.button').mousedown(function(e){
	var parentOffset = $(this).parent().offset(); 
	var relX = e.pageX - parentOffset.left;
	var relY = e.pageY - parentOffset.top;
	var w = $('<div></div>');
	w.css('top', relY);
	w.css('left', relX);
	$(this).append(w);
	w.addClass('wave');
	w.fadeOut(1000);
	setTimeout(function(){w.remove();}, 1000);
	w.addClass('a');
	return false;
});

// $('#fullpage').fullpage({
// 	anchors: ['main', /* 'structure', 'plans', 'achivements', 'vacancies', */ 'works'],
// 	navigationTooltips: ['Main', /* 'Structure', 'Plans', 'Achivements', 'Vacancies', */ 'Works'],
// 	scrollingSpeed: 1000,
// 	verticalCentered: true,
// });

var closeDD = function() {
    $('.dd').addClass('dd-hidden');
    $('.dd-toggle').removeClass('active');
};

$(function(){
	/* Drop Down close */
	$(document).on('click', function(e){
		var v = $(e.target);
		if (v.prop('nodeName').toLowerCase() == "a" && v.attr("href") != "#") {
			return;
		}

		e.preventDefault();
		var isDD = v.hasClass('dd') || v.hasClass('dd-toggle') || v.parents('.dd').length || v.parents('.dd-toggle').length;
		if(!isDD) {
			closeDD();
		}
	});

	$(document).on('click', '.dd-toggle', function(e){
		console.log($(this).attr('dd-target'));
		e.preventDefault();
		$($(this).attr('dd-target')).toggleClass('dd-hidden');
		$(this).toggleClass('active');
	});
});

$("a:not(.dd-toggle):not(.js-lang)").click(function(){
	if($(this).attr("href")=="#") return false;
});

$(document).on('click', '.js-lang', function(e) {
	e.preventDefault();
	Cookies.set('lang', $(this).data('lang'));
	location.reload();
});