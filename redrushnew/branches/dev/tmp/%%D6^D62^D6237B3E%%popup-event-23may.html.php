<?php /* Smarty version 2.6.13, created on 2012-04-30 13:54:13
         compiled from RedRushWeb/popup-event-23may.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:20px 0 0 0">Feel it! Hear it! Move it! Be part of RedRush Night party in Jakarta & Samarinda.<br />
		Next Saturday, 26 May!</h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">We bring the F1 Car for you in to Jakarta & Samarinda this week. Don't missed this opportunity.</h2>
        </div>
	</div>
	<div class="backgroundPopup" style="display:block;"></div>
    <?php echo '
    <script type="text/javascript">
      $(document).ready(function() {
	          $(".backgroundPopup").fadeIn("slow");
              $("#popup-ultimate-car").fadeIn("slow");
            $("a.popup-close").click(function() {
                $(".backgroundPopup").fadeOut("slow");
                $("#popup-ultimate-car").fadeOut("slow");
            });
			
			 $(".gradients").click(function() {
                $(".backgroundPopup").fadeOut("slow");
                $("#popup-ultimate-car").fadeOut("slow");
            });
        });
    </script>
    '; ?>