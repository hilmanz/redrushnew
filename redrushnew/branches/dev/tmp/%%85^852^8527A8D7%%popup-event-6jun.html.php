<?php /* Smarty version 2.6.13, created on 2012-04-30 14:00:44
         compiled from RedRushWeb/popup-event-6jun.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:10px 0 0 0">Feel the beat...  RedRush Night come to Denpasar & Yogyakarta<br />
			 Mark the date <span class="red">Saturday, 9 June!</span></h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">Can't get enough of the F1 Car? Alert for Denpasar & Yogyakarta! It's coming to your town this week.</h2>
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