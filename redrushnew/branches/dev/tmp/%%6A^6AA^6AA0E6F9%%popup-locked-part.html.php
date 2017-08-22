<?php /* Smarty version 2.6.13, created on 2012-04-20 11:49:46
         compiled from RedRushWeb/popup-locked-part.html */ ?>
	<div id="popup-locked-part" class="popup carbons" style="display:block;">
		<a class="popup-close" href="#">[x] Close</a>
        <div class="inner-popup">
        	<img src="img/lock-part.png" align="middle" />
            <h1><span style="font-size:30px;">Level up by upgrading your parts with your points.</span><br />
Can you hear that sound of revving engine? The challenge is right around the corner.<br />
Collect more points. Upgrade your car. Win Races.<br /> </h1>
        </div>
	</div>
	<div class="backgroundPopup" style="display:block;"></div>
    <?php echo '
    <script type="text/javascript">
      $(document).ready(function() {
            $("a.popup-close").click(function() {
                $("#backgroundPopup").fadeOut("slow");
                $("#popup-ultimate-car").fadeOut("slow");
            });
        });
    </script>
    '; ?>