<?php /* Smarty version 2.6.13, created on 2012-06-29 20:46:54
         compiled from RedRushWeb/landing.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MARLBORO RED RUSH</title>
<?php echo '
<link href="css/redrush.css" rel="stylesheet" type="text/css" />
<link href="css/parallax.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.5.js"></script>
<script type="text/javascript" src="js/parallax.js"></script>
<script type="text/javascript" src="js/jquery-animate-css-rotate-scale.js"></script>
<script type="text/javascript" src="js/jqueryResize.js"></script>
<script type="text/javascript" src="js/jquery-css-transform.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<link rel="stylesheet" href="css/queryLoader.css" type="text/css" />
<script type=\'text/javascript\' src=\'js/queryLoader.js\'></script>
<script type="text/javascript" src="video/flowplayer-3.2.8.min.js"></script>

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie7.css">
<![endif]-->
<!--[if gt IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie8.css">
<![endif]-->
<style type="text/css">
	@-moz-document url-prefix()
	{
		.wstep4
		{
			position:absolute;
			top:50%;
			margin:200px 0 0 0;
		}
		
	}
</style>
'; ?>

</head>
<!--[if !IE]><!-->


<body id="landing" style="overflow: auto;" data-rendering="true" onload="document.getElementById('loading').style.display = 'none'; document.body.style.overflow = 'auto'; setrightheight();">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
<div  id="container" style="height:9000px;">
  <div id="red">
    <div class="w1000">
      <div style="opacity: 1;" id="intro">
        <h1>this is<br />
          what you<br />
          signed up for</h1>
      </div><!-- END #intro -->
      <div style="position: fixed; top: 0px;" id="thecars">
        <div style="opacity: 1;" id="mobil1"></div>
        <div style="opacity: 0;" id="mobil2"></div>
        <div style="opacity: 0;" id="mobil3"></div>
      </div><!-- END #thecars -->
    </div><!-- END .w1000 -->
  </div><!-- END #red -->
  
  <div class="w1000">
        <div id="step2" style="position:fixed">
          <div id="step2bg">
            <h1 id="text1">THRILL YOUR<br />SENSES.</h1>
            <h2 id="text2" style="opacity: 0;">YOUR BEST EXPERIENCE YET.</h2>
            <div id="talent1" style="opacity: 0;"></div>
            <div id="talent2" style="opacity: 0;"></div>
          </div> <!-- END #step2bg -->
        </div> <!-- END #step2 -->
       
        <div style="position: fixed;  opacity: 0;" id="step3">
              <div id="peoples2"></div>
              <div id="car3" style="opacity: 0;"></div>
              <div id="car4" style="opacity: 0;"></div>
        </div><!-- #step3 -->
        <div class="wstep4">
        <div style="opacity: 0;" id="step4">
          <div class="wrapper">
            <div class="logo"> <a href="#">&nbsp;</a> </div>
            <!-- .logo -->
            <div class="panel" style="margin:120px 0 0 -300px;">
              <div class="entry">
                <div class="welcome-box">
                  <h1>Welcome, racers!</h1>
                  <p>RedRush invites you to join your peers in an immersive journey of high-charged fun, designed around the exciting world of Motorsport. Experience the thrills and get a chance to experience an unforgettable week in Italy! </p>
                </div>
                <!-- .welcome-box -->
                <div class="video-home" style="position:absolute; display:none;">
                        <a  
                             href="https://www.marlboro.co.id/video/Marlbor_Redrush_90s320x240.flv"
                             style="display:block;width:320px;height:240px; position:absolute;"  
                             id="player"> 
                        </a> 
                    <?php echo '
                        <!-- this will install flowplayer inside previous A- tag. -->
                        <script>
                            flowplayer("player", "video/flowplayer-3.2.8.swf",{
								  clip:  {
									  autoPlay: false,
									  autoBuffering: true
								  }
							  });
                        </script>
                      '; ?>

                </div>
                <!-- .video-home -->
              </div>
              <!-- .entry -->
            </div>
            <!-- .panel -->
            <div class="peoples" style="margin:120px 0 0 -680px;"></div>
          </div>
          <!-- .wrapper -->
        </div>
        </div>
  </div><!-- .w1000 -->
  <a  id="scrolldown"></a>
 
</div> <!-- END #container -->


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<!--<![endif]-->    
    <div id="iepar">
    	
<!--[if IE]><!-->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
  <div id="red-ie">
    <div class="w1000">
      <div style="opacity: 1; z-index:1;" id="intro">
        <h1 style="font-size:100px;">this is<br />
          what you<br />
          signed up for</h1>
      </div>
      <!-- END #intro -->
        <div id="mobil2-ie"></div>
    </div>
  </div>
  <div id="step2-ie">
        <h1 id="text1" style="margin:40px 0 0 -500px;">THRILL YOUR<br />
          SENSES.</h1>
        <h2 id="text2" style="opacity: 1; margin:380px 0 0 -490px;">YOUR BEST EXPERIENCE YET.</h2>
        <div id="talent1" style="opacity: 1;"></div>
        <div id="talent2" style="opacity: 1; margin:220px 0 0 -500px;"></div>
  </div>
  <div id="step3-ie">
      <div id="peoples2" style="top:500px;"></div>
      <div id="car4" style="opacity: 1;"></div>
        <div id="talent1" style="opacity: 1;"></div>
        <div id="talent2" style="opacity: 1; margin:220px 0 0 -500px;"></div>
  </div>
      
        <div style="opacity: 1;" id="step4-ie">
          <div class="wrapper">
            <div class="logo"> <a href="#">&nbsp;</a> </div>
            <!-- .logo -->
            <div class="panel" style="margin:20px 0 0 -300px;">
              <div class="entry">
                <div class="welcome-box">
                  <h1>Welcome, racers!</h1>
                  <p>RedRush invites you to join your peers in an immersive journey of high-charged fun, designed around the exciting world of Motorsport. Experience the thrills and get a chance to experience an unforgettable week in Italy! </p>
                </div>
                <!-- .welcome-box -->
                <div class="video-home" style="position:absolute;">
                        <a  
                             href="https://www.marlboro.co.id/video/Marlbor_Redrush_90s320x240.flv"
                             style="display:block;width:320px;height:240px;"  
                             id="player2"> 
                        </a> 
                       <?php echo '
                        <!-- this will install flowplayer inside previous A- tag. -->
                                      <script>
                            flowplayer("player2",{
							src:"video/flowplayer-3.2.8.swf",
							wmode: "opaque" ,
							  clip:  {
							  autoPlay: false,
							  autoBuffering: true
							  }
							  });
                        </script>
                        '; ?>

                </div>
                <!-- .video-home -->
              </div>
              <!-- .entry -->
            </div>
            <!-- .panel -->
            <div class="peoples" style="margin:20px 0 0 -680px;"></div>
          </div>
          <!-- .wrapper -->
        </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--<![endif]-->
    </div>
    
    <?php echo '
	<script>
		QueryLoader.selectorPreload = "body";
		QueryLoader.init();
	</script>
	<script type="text/javascript">
      var _gaq = _gaq || [];
    
      _gaq.push([\'_setAccount\', \'UA-867847-35\']);
    
      _gaq.push([\'_trackPageview\']);
    
      (function() {
    
        var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    
        ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    
        var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
    
      })();
    </script>
    '; ?>

</body>

</html>