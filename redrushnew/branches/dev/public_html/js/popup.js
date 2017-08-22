/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopupBrake(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-brake").fadeIn("slow");
		popupStatus = 1;
	}
}

function loadPopupKnalpot(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-knalpot").fadeIn("slow");
		popupStatus = 1;
	}
}
function loadPopupVisual(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-visual").fadeIn("slow");
		popupStatus = 1;
	}
}

function loadPopupWheel(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-wheel").fadeIn("slow");
		popupStatus = 1;
	}
}


function loadPopupKit(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-kit").fadeIn("slow");
		popupStatus = 1;
	}
}


function loadPopupSuspension(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-suspension").fadeIn("slow");
		popupStatus = 1;
	}
}


function loadPopupEngine(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-engine").fadeIn("slow");
		popupStatus = 1;
	}
}

function loadPopupMerchandise(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.2"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-merchandise").fadeIn("slow");
		popupStatus = 1;
	}
}



function loadPopupUpdateProfile(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$(".backgroundPopup").css({
			"opacity": "0.7"
		});
		$(".backgroundPopup").fadeIn("slow");
		$("#popup-update-profile").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$(".backgroundPopup").fadeOut("slow");
		$(".popup").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $(".popup").height();
	var popupWidth = $(".popup").width();
	
	$(".backgroundPopup").css({
		"height": windowHeight
	});
	
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#btn-brake").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupBrake();
	});
	$("#btn-knalpot").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupKnalpot();
	});
	$("#btn-update-profile").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupUpdateProfile();
	});
	$("#btn-edit-profile").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupUpdateProfile();
	});
	$("#btn-visual").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupVisual();
	});
	$("#btn-wheel").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupWheel();
	});
	$("#btn-kit").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupKit();
	});
	$("#btn-suspension").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupSuspension();
	});
	$("#btn-engine").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupEngine();
	});
		
	$("#btn-merchandise").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopupMerchandise();
	});

	
	//CLOSING POPUP
	//Click the x event!
	$(".popup-close").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});

});