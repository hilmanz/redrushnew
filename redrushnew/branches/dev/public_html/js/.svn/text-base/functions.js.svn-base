$(function() {
 
    $('#thecars').scrollingParallax({
        staticSpeed : 4,
		staticScrollLimit : false
    });
	
    $('#titles').scrollingParallax({
        staticSpeed : .022,
		staticScrollLimit : false
    });
	
	$('#step3').scrollingParallax({
        staticSpeed : .06,
		staticScrollLimit : true
    });
	
	$('#step4').scrollingParallax({
        staticSpeed : .06,
		staticScrollLimit : true
    });
	
    
});


/* CHANGE PROPERTIES OF ELEMENTS BASED ON SCROLL POSITION */
$(function() {
  $(document).scroll(function() {

	 
   if($(window).scrollTop() >= 30)
   {
		$("#intro h1").css("font-size",($(window).scrollTop()/10)+60)
		$("#intro h1").css("margin-left",-($(window).scrollTop()/10)+60)
   }
   else
		$("#intro h1").css("font-size",60)
		$("#intro h1").css("margin-left",0)
		
	   
   if($(window).scrollTop() >= 100)
   {
		$("#mobil2").css("opacity",($(window).scrollTop()-100)/300)
		$("#mobil1").css("opacity",(1-($(window).scrollTop()-100)/300))
		$("#talent1").css("opacity",0)
		$("#talent2").css("opacity",0)
   }
   else
   {
  		$("#mobil2").css("opacity",0)
		$("#mobil1").css("opacity",1)
		$("#talent1").css("opacity",0)
		$("#talent2").css("opacity",0)
   }
		
	   
   if($(window).scrollTop() >= 300)
   {
		$("#mobil3").css("opacity",($(window).scrollTop()-300)/100)
		$("#mobil2").css("opacity",(1-($(window).scrollTop()-300)/50))
		$("#talent1").css("opacity",0)
		$("#talent2").css("opacity",0)
   }
   else
   {
  		$("#mobil3").css("opacity",0)
  		$("#intro h1").css("opacity",1)
		$("#talent1").css("opacity",0)
		$("#talent2").css("opacity",0)
   }
   
   if($(window).scrollTop() >= 500)
   {
		$("#mobil3").css("opacity",(1-($(window).scrollTop()-500)/400))
		$("#intro h1").css("opacity",(1-($(window).scrollTop()-500)/100))
		$("#talent1").css("opacity",0)
		$("#talent2").css("opacity",0)
   }
   else
  		$("#intro h1").css("opacity",1)
   
   
   if($(window).scrollTop() >= 800)
   {
		$("#talent1").css("top",($(window).scrollTop()-900)/150+1)
		$("#talent2").css("top",-($(window).scrollTop()-900)/80+1)
		$("#text1").css("top",($(window).scrollTop()-800)/150+120) 
		$("#text2").css("top",-($(window).scrollTop()-800)/30+1)
   }
		
   if($(window).scrollTop() >= 1000)
   {
		$("#step3").css("opacity",0)  
		$("#talent1").css("opacity",($(window).scrollTop()-1000)/500)
		$("#text2").css("opacity",0)  
   }
		
   if($(window).scrollTop() >= 1200)
   {
		$("#step3").css("opacity",0)  
		$("#text2").css("opacity",0)  
		$("#talent2").css("opacity",($(window).scrollTop()-1200)/700)
   }
		
   if($(window).scrollTop() >= 1500)
   {
		$("#step3").css("opacity",0)  
		$("#talent1").css("opacity",1)
		$("#talent2").css("opacity",1)
		$("#text2").css("opacity",($(window).scrollTop()-1500)/1000)
   }
   
   
   if($(window).scrollTop() >= 1800)
   {
		$("#step3").css("opacity",($(window).scrollTop()-1800)/2500)
		$("#peoples2").css("top",-($(window).scrollTop()-1800)/130+600)
   }
   
   else
		$("#car4").css("opacity",0)  
		$("#car3").css("opacity",1)  
		
   
   
   if($(window).scrollTop() >= 2000)
   {
		$("#car3").css("opacity",(1-($(window).scrollTop()-2000)/1000))
		$("#car4").css("opacity",($(window).scrollTop()-2000)/1000)
		$(".video-home").css("display","none")
   }
   if($(window).scrollTop() <= 4200)
   {
		$(".video-home").css("display","none")
   }
    if($(window).scrollTop() >= 7200)
   {
   $(".video-home").css("display","block")
   }
   if($(window).scrollTop() >= 4200)
   {
		$("#step4").css("opacity",($(window).scrollTop()-4200)/3050)
		$("#talent1").css("opacity",(1-($(window).scrollTop()-4200)/3050))
		$("#talent2").css("opacity",(1-($(window).scrollTop()-4200)/3050))
		
   }
   else
		$("#step4").css("opacity",0)  
		
	});
  
});


/*  HEIGHT SETTER - MAKES THE PAGE HAVE THE RIGHT LENGTH FOR THE VIEWPORT. GETS CALLED ONLOAD (IN BODY TAG) + ONRESIZE (BELOW) */
function setrightheight()
{
$("#container").css("height",($(window).height()+15000))
}

/* CHECK IF WINDOW IS RESIZED AND RE-RUN HEIGHT SETTER */
$(function() {
  $(window).resize(function() {
	setrightheight();
  });
});