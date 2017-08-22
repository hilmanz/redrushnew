<?php /* Smarty version 2.6.13, created on 2012-04-30 14:02:17
         compiled from RedRushWeb/popup-event-20jun.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:40px 0 0 0">Be there at RedRush Night in Jakarta and Makassar. <span class="red">See you on Saturday, 23 June!</span></h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">Let's talk serious now. Come on... The clock is ticking.<br />
			 Rack up your points! Win the race.</h2>
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