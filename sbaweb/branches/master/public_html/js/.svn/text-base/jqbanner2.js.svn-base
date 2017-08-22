// Simple JavaScript Rotating Banner Using jQuery
// www.mclelun.com
var jqb2_vCurrent = 0;
var jqb2_vTotal = 0;
var jqb2_vDuration = 6000;
var jqb2_intInterval = 0;
var jqb2_vGo = 1;
var jqb2_vIsPause = false;
var jqb2_tmp = 20;
var jqb2_title;

jQuery(document).ready(function() {	
	jqb2_vTotal = $(".jqb2_slides").children().size() -1;
	$(".jqb2_info").text($(".jqb2_slide").attr("title"));	
	jqb2_intInterval = setInterval(jqb2_fnLoop, jqb2_vDuration);
			
	$("#jqb2_object").find(".jqb2_slide").each(function(i) { 
		//jqb2_tmp = ((i - 1)*240) - ((jqb2_vCurrent -1)*240);
		//$(this).animate({"left": jqb2_tmp+"px"}, 500);
	});
	
	$("#btn_pauseplay").click(function() {
		if(jqb2_vIsPause){
			jqb2_fnChange();
			jqb2_vIsPause = false;
			$("#btn_pauseplay").removeClass("jqb2_btn_play");
			$("#btn_pauseplay").addClass("jqb2_btn_pause");
		} else {
			clearInterval(jqb2_intInterval);
			jqb2_vIsPause = true;
			$("#btn_pauseplay").removeClass("jqb2_btn_pause");
			$("#btn_pauseplay").addClass("jqb2_btn_play");
		}
	});
	$("#btn_prev").click(function() {
		jqb2_vGo = -1;
		jqb2_fnChange();
	});
		
	$("#btn_next").click(function() {
		jqb2_vGo = 1;
		jqb2_fnChange();
	});
});

function jqb2_fnChange(){
	clearInterval(jqb2_intInterval);
	jqb2_intInterval = setInterval(jqb2_fnLoop, jqb2_vDuration);
	jqb2_fnLoop();
}

function jqb2_fnLoop(){
	if(jqb2_vGo == 1){
		jqb2_vCurrent == jqb2_vTotal ? jqb2_vCurrent = 0 : jqb2_vCurrent++;
	} else {
		jqb2_vCurrent == 0 ? jqb2_vCurrent = jqb2_vTotal : jqb2_vCurrent--;
	}
	
	$("#jqb2_object").find(".jqb2_slide").each(function(i) { 
		
		if(i == jqb2_vCurrent){
			jqb2_title = $(this).attr("title");
			/*
			$(".jqb2_info").animate({ opacity: 'hide', "left": "-50px"}, 250,function(){
				$(".jqb2_info").text(jqb2_title).animate({ opacity: 'show', "left": "0px"}, 500);
			});
			*/
		} 
		

		//Horizontal Scrolling
		//jqb2_tmp = ((i - 1)*240) - ((jqb2_vCurrent -1)*240);
		//$(this).animate({"left": jqb2_tmp+"px"}, 500);
		
		
		//Fade In & Fade Out
		if(i == jqb_vCurrent){
			$(".jqb_info").text($(this).attr("title"));
			$(this).animate({ opacity: 'show'}, 500);
		} else {
			$(this).animate({ opacity: 'hide' }, 500);
		}
		
		
	});


}






