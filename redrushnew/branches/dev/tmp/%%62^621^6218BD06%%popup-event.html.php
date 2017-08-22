<?php /* Smarty version 2.6.13, created on 2012-05-16 15:57:46
         compiled from RedRushWeb/popup-event.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:35px; margin:10px 0 0 0; color:#C00;">Time to take your <span style="color:#FF0;">race</span> experience to the next level and enjoy the party at full speed! <br />
<span style="color:#FF0;">RedRush Night</span> events are coming to <br />
<span style="color:#FF0;">Surabaya & Medan on May 12</span> . Mark the date!</h1>
            <h2 style="font-size:25px; margin:20px 0 25px 0; color:#fff;">See it with your own eyes! <span style="color:#FF0;">The F1 Car</span> is coming to <br />
Medan & Surabaya this week. Don't miss it!</h2>
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