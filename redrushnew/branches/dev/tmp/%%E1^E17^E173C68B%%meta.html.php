<?php /* Smarty version 2.6.13, created on 2012-06-25 17:53:38
         compiled from RedRushWeb/meta.html */ ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MARLBORO RED RUSH</title>
<link href="css/redrush.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/skin.css" />
<link rel="stylesheet" type="text/css" href="css/jScrollPane.css" />
<?php echo '
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="js/nbw-parallax.js"></script>
<script type="text/javascript" src="js/jquery.localscroll-1.2.7-min.js"></script>
<script type="text/javascript" src="js/jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript" src="js/jquery.inview.js"></script>
<script type="text/javascript" src="js/drop.js"></script>
<script type="text/javascript" src="js/popup.js"></script>
<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="js/jScrollPane.js"></script>
<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie7.css">
<![endif]-->
<script type="text/javascript">
jQuery(document).ready(function() {
								
	    $(".tip_trigger").hover(function(){
		tip = $(this).find(\'span.tip\');
		tip.show(); //Show tooltip
		
    }, function() {
        tip.hide(); //Hide tooltip
    }).mousemove(function(e) {
        var mousex = e.pageX - 50; //Get X coodrinates
        var mousey = e.pageY + 20; //Get Y coordinates
        var tipWidth = tip.width(); //Find width of tooltip
        var tipHeight = tip.height(); //Find height of tooltip

        //Distance of element from the right edge of viewport
        var tipVisX = $(window).width() - (mousex + tipWidth);
        //Distance of element from the bottom of viewport
        var tipVisY = $(window).height() - (mousey + tipHeight);

        if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
            mousex = e.pageX - tipWidth - 20;
        } if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
            mousey = e.pageY - tipHeight - 20;
        }
        //Absolute position the tooltip according to mouse position
        tip.css({  top: 20, left: 1 });
    })
		
    jQuery(\'.list-sparepart-carousel\').jcarousel({
        scroll: 2
    });
    jQuery(\'.list-merchandise-carousel\').jcarousel({
        scroll: 1
    });
	
});
</script>
<script type="text/javascript">
	
	$(function()
	{
		// this initialises the demo scollpanes on the page.
		$(\'.scrollbar\').jScrollPane();
	});
	
</script>
'; ?>