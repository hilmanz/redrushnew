<?php /* Smarty version 2.6.13, created on 2012-05-02 10:02:25
         compiled from RedRushWeb/popup-event-27jun.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:20px 0 0 0">Joint our final lap RedRush Night party in Samarinda and Medan.<br />
				Book the date. <span class="red">Saturday, 30 June!</span></h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">Let's talk serious now. Come on... The clock is ticking.<br />
			 This is it! Pile up the points now or never!</h2>
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