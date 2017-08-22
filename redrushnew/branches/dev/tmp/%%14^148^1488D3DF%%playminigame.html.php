<?php /* Smarty version 2.6.13, created on 2012-05-31 16:29:33
         compiled from RedRushWeb//playminigame.html */ ?>
<?php echo '
<script type="text/javascript">
$(\'.logo a\').click(function() {
  $(\'.panel5\').blur();
});
</script>
<style>
	body{
		background:#000;
	}
</style>
'; ?>

<div id="main-container" class="home getpoint">
	<div class="wrapper">
    	<div id="containers" style="height:900px; z-index:10000; position:absolute; left:50%; margin:0 0 0 -400px; width:800px;">
		<div class="game-container">
        <a class="closegame" href="index.php?page=getpoints">CLOSE GAME</a>
        <div id="flashContent">
            <p>
                To view this page ensure that Adobe Flash Player version 
                10.2.0 or greater is installed. 
            </p>
  <?php echo '
            <script type="text/javascript"> 
                var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
                document.write("<a href=\'http://www.adobe.com/go/getflashplayer\'><img src=\'" 
                                + pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif\' alt=\'Get Adobe Flash player\' /></a>" ); 
            </script> 
  '; ?>

        </div>
        <div id="game" style="height:800px;">
        <noscript>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="600" id="RRPuzzle">
                <param name="movie" value="games/solvetherush/RRPuzzle.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#000000" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="true" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="games/solvetherush/RRPuzzle.swf" width="800" height="600">
                    <param name="quality" value="high" />
                    <param name="bgcolor" value="#000000" />
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="true" />
                <!--<![endif]-->
                <!--[if gte IE 6]>-->
                    <p> 
                        Either scripts and active content are not permitted to run or Adobe Flash Player version
                        10.2.0 or greater is not installed.
                    </p>
                <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflashplayer">
                        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                    </a>
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
        </noscript> 
		</div>

<?php echo '
        <script type="text/javascript" src="games/solvetherush/swfobject.js"></script>
        <script type="text/javascript">
            // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
            var swfVersionStr = "10.2.0";
            // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
            var xiSwfUrlStr = "games/solvetherush/playerProductInstall.swf";
            var flashvars = {};
			'; ?>

            flashvars.access_token = "<?php echo $this->_tpl_vars['access_token']; ?>
";
			<?php echo '
            var params = {};
            params.quality = "high";
            params.bgcolor = "#000000";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "RRPuzzle";
            attributes.name = "RRPuzzle";
            attributes.align = "middle";
            swfobject.embedSWF(
                "games/solvetherush/RRPuzzle.swf", "flashContent", 
                "800", "600", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
            // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
            swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        </script>
'; ?>
	
			</div>
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->

