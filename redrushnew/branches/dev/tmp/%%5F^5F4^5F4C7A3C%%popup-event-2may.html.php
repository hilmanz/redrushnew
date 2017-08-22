<?php /* Smarty version 2.6.13, created on 2012-04-30 13:52:03
         compiled from RedRushWeb/popup-event-2may.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:20px 0 0 0">Do you know that you'll get 10 point<br /> if you win challenge the same level racer? Try it!</h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">I heard they got best stuff! Go click that Redeem Merchandise menu</h2>
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