<?php /* Smarty version 2.6.13, created on 2012-04-20 11:49:29
         compiled from RedRushWeb/popup-ultimate-car.html */ ?>
	<div id="popup-ultimate-car" class="popup carbons" style="display:block;">
		<a class="popup-close" href="#">[x] Close</a>
        <div class="inner-popup">
        	<img src="img/ultimate-car.png" align="middle" />
            <h1>You've been challenged! It's the only way to level up.<br />
			 Do you have what it takes?</h1>
			<p><a href="#" class="gradients">Bring it on!</a><a href="#" class="gradients">Maybe next time</a></p>
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