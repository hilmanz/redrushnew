<?php /* Smarty version 2.6.13, created on 2012-04-30 14:01:00
         compiled from RedRushWeb/popup-event-13jun.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:none;">
		<a class="popup-close" href="#" style="right:18px;">[x] Close</a>
        <div class="inner-popup">
            <h1 style="font-size:40px; color:#FF0; margin:10px 0 0 0">Bandung is lucky. <span class="red">Saturday 15 May,</span> <br />
			be part of the party RedRush Night.<br />
			 Also, The F1 car coming to you!</h1>
            <h2 style="font-size:30px; margin:25px 0 25px 0;">Watch out time is running out quick. Fasten up your seatbelt! You need more points! Race it! Win It!</h2>
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