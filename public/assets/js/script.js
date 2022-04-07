/*if($.browser.mozilla||$.browser.opera){
    document.removeEventListener("DOMContentLoaded",$.ready,false);
    document.addEventListener("DOMContentLoaded",function(){$.ready()},false)}$.event.remove(window,"load",$.ready);
$.event.add( window,"load",function(){$.ready()});$.extend({includeStates:{},include:function(url,callback,dependency){if(typeof callback!='function'&&!dependency){dependency=callback;callback=null}url=url.replace('\n','');$.includeStates[url]=false;var script=document.createElement('script');script.type='text/javascript';script.onload=function(){$.includeStates[url]=true;if(callback)callback.call(script)};script.onreadystatechange=function(){if(this.readyState!="complete"&&this.readyState!="loaded")return;$.includeStates[url]=true;if(callback)callback.call(script)};script.src=url;if(dependency){if(dependency.constructor!=Array)dependency=[dependency];setTimeout(function(){var valid=true;$.each(dependency,function(k,v){if(!v()){valid=false;return false}});if(valid)document.getElementsByTagName('head')[0].appendChild(script);else setTimeout(arguments.callee,10)},10)}else document.getElementsByTagName('head')[0].appendChild(script);return function(){return $.includeStates[url]}},readyOld:$.ready,ready:function(){if($.isReady) return;imReady=true;$.each($.includeStates,function(url,state){if(!state)return imReady=false});if(imReady){$.readyOld.apply($,arguments)}else{setTimeout(arguments.callee,10)}}});
*/
var assetsURL = "";
if (typeof Tecnotek != "undefined" && typeof Tecnotek.assetsURL != "undefined"){
    assetsURL = Tecnotek.assetsURL;
}
$.getScript(assetsURL + 'js/superfish.js')
$.getScript(assetsURL + 'js/FF-cash.js')
$.getScript(assetsURL + 'js/tms-0.4.x.js')
$.getScript(assetsURL + 'js/uCarausel.js')
$.getScript(assetsURL + 'js/jquery.easing.1.3.js')
$.getScript(assetsURL + 'js/jquery.tools.min.js')
$.getScript(assetsURL + 'js/jquery.jqtransform.js')
$.getScript(assetsURL + 'js/jquery.quicksand.js')
$.getScript(assetsURL + 'js/jquery.snippet.min.js')
$.getScript(assetsURL + 'js/jquery-ui-1.8.17.custom.min.js')
$.getScript(assetsURL + 'js/jquery.cycle.all.min.js')
$.getScript(assetsURL+ 'js/jquery.cookie.js')
$(function(){
	if($('.tweet').length)$.include('js/jquery.tweet.js');
	if($('.lightbox-image').length)$.include('js/jquery.prettyPhoto.js');
	if($('#contact-form').length||$('#contact-form2').length)$.include('js/forms.js');
	if($('.kwicks').length)$.include('js/kwicks-1.5.1.pack.js');
	if($('#counter').length)$.include('js/jquery.countdown.js');
	if($('.fixedtip').length||$('.clicktip').length||$('.normaltip').length)$.include('js/jquery.atooltip.pack.js')

// Code
	//$("pre.htmlCode2").snippet("html",{style:"print",showNum:false,menu:false/*,clipboard:"js/ZeroClipboard.swf"*/});
	//$("pre.jsCode2").snippet("javascript",{style:"print",showNum:false,menu:false/*,clipboard:"js/ZeroClipboard.swf"*/});
// SlideDown
		$(".description-box dd").show()
	//$("pre.htmlCode").snippet("html",{style:"print"/*,clipboard:"js/ZeroClipboard.swf"*/});
	//$("pre.cssCode").snippet("css",{style:"print"/*,clipboard:"js/ZeroClipboard.swf"*/});
	//$("pre.jsCode").snippet("javascript",{style:"print"/*,clipboard:"js/ZeroClipboard.swf"*/});
	$(".description-box dd").hide()	
	$(".description-box dt").click(function(){
		$(this).toggleClass("active").parent(".description-box").find("dd").slideToggle(400);					
	});
	$(".slide-down-box dt").click(function(){$(this).toggleClass("active").parent(".slide-down-box").find("dd").slideToggle(200);});
	$(".slide-down-box2 dt").click(function(){$(this).toggleClass("active").parent(".slide-down-box2").find("dd").slideToggle(200);});
// Tabs
	$(".tabs1 ul").tabs(".tabs1 .tab-content");
	$(".tabs2 ul").tabs(".tabs2 .tab-content");
	$(".tabs3 ul").tabs(".tabs3 .tab-content");
	$(".tabs4 ul").tabs(".tabs4 .tab-content");
	$(".tabs5 ul").tabs(".tabs5 .tab-content");
	$(".tabs-horz-top ul.tabs-nav").tabs(".tabs-horz-top .tab-content");
	$(".tabs-horz-bottom ul.tabs-nav").tabs(".tabs-horz-bottom .tab-content");
	$(".tabs-horz-top2 ul.tabs-nav").tabs(".tabs-horz-top2 .tab-content");
	$(".tabs-horz-bottom2 ul.tabs-nav").tabs(".tabs-horz-bottom2 .tab-content");
	$(".tabs-vert-left ul.tabs-nav").tabs(".tabs-vert-left .tab-content");
	$(".tabs-vert-right ul.tabs-nav").tabs(".tabs-vert-right .tab-content");	
// Forms
	//$('#form2').jqTransform({imgPath:'images/'});
// Carausel
	//$('.list-car').uCarausel({show:4,buttonClass:'btn'})
	//$('.carousel').uCarausel({show:4,buttonClass:'btn'})
// Ranges	
	$("#font-size-slider").change(function(e) {
		$(".icons.basic li a").css("font-size", $(this).val() + "px");	
	});
	$(".color-slider").change(function(e) {
		$(".icons.basic li a").css("color", "hsla(" + $("#color-slider-1").val() + ", " + $("#color-slider-2").val() + "%, " + $("#color-slider-3").val() + "%, 1)");	
	});
	$(".shadow-slider").change(function(e) {	
		$(".icons.basic li a").css("text-shadow", $("#shadow-slider-1").val() + "px " + $("#shadow-slider-2").val() + "px " + $("#shadow-slider-3").val() + "px black");	 
	});

// Buttons
	$(".notClicked").click(function(event) {
	  event.preventDefault();
	});
})
// Panel
$(function(){
		$(window).scroll(function(){
			if ($(this).scrollTop() > 0) {
				$("#advanced").css({position:'fixed'});
			} else {
				$("#advanced").css({position:'relative'});
			}
		});

});
function onAfter(curr, next, opts, fwd){var $ht=$(this).height();$(this).parent().animate({height:$ht})}
