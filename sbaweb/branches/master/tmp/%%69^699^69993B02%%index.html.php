<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Social/index.html */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="UTF-8"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SBA</title>
<link href="css/sba.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jqbanner.js" type="text/javascript"></script>
<script src="js/jqbanner2.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="js/jqbanner.css" />
<?php echo '
<script type="text/javascript" src="js/jquery.tools.min.js"></script>	
<script src="js/jquery.timers.js"></script>


<!-- Jquery Pop up Gallery ni -->
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script src="js/social.js"></script>
'; ?>

<script>
api_url = "<?php echo $this->_tpl_vars['API_URL']; ?>
";
</script>

</head>

<body>
<div id="body">
<div id="wrapper">
	<div id="header">
    </div>
    <div id="container">
    <div id="nav">
        	<ul class="nav-left">
        	<li><a class="home" href="index.php"></a></li>
        	<li><a class="profile" href="index.php?profile=1"></a></li>
        	<li><a class="news" href="index.php?news=1"></a></li>
            <li><a class="events" href="index.php?events=1"></a></li>
            <li><a class="gallery" href="index.php?gallery=1"></a></li>
            <li><a class="forum" href="index.php?forum=1"></a></li>
            <li><a class="download" href="index.php?download=1"></a></li>
        	</ul>
        	<ul class="nav-right">
                <li><a class="logout" href="logout.php"></a></li>
            	<?php if ($this->_tpl_vars['leader'] == 'yes'): ?>
            	<li><a class="konfirmasi" href="index.php?konfirmasi_ba=1"></a></li>
				<?php endif; ?>
            </ul>
     </div>
    <?php if ($this->_tpl_vars['takeOut']): ?>
    	<div id="nav">
        	<ul class="nav-left">
            	<li><a href="index.php">Home</a>|</li>
                <li><a href="index.php?profile=1">My Profile</a>|</li>
                <li><a href="index.php?news=1">News Update</a>|</li>
                <li><a href="index.php?events=1">Events</a>|</li>
                <li><a href="index.php?gallery=1">Gallery</a>|</li>
                <li><a href="index.php?forum=1">Forum</a>|</li>
                <li><a href="index.php?download=1">Download</a></li>
            </ul>
            <ul class="nav-right">
                <li><a href="logout.php">Logout</a></li>
				<?php if ($this->_tpl_vars['leader'] == 'yes'): ?>
            	<li><a href="index.php?konfirmasi_ba=1">Konfirmasi BA</a>|</li>
				<?php endif; ?>
			</ul>
        </div>
        <?php endif; ?>	
        <div id="banner">
        	<?php echo $this->_tpl_vars['HEADER_BANNER']; ?>

        </div>
        <div id="main-content">
        	<?php echo $this->_tpl_vars['mainContent']; ?>

        </div><!-- end div main Content -->
    </div>
    <div id="footer">
    		 <p>Informasi dalam website ini ditujukan untuk perokok berusia 18 tahun atau lebih dan tinggal di wilayah Indonesia<br />
            Jika kamu tidak ingin dihubungi oleh PT HM Sampoerna Tbk melalui email, kamu bisa hapus kontak kamu dengan mengklik link hapus saya dibawah ini.</p>
    		
            <a href="index.php">Halaman Utama </a>|
            <a href="syarat.html" target="_blank">Syarat dan Ketentuan</a>|
            <a href="hapus_saya.php" target="_blank">Hapus saya</a>|
            <a href="contact.html" target="_blank">Kontak Kami</a>|
            <!-- <a href="#">PMI Corporate</a>|-->
            <a href="http://www.pmi.com/id/smokingandhealth" target="_blank">Perihal Merokok</a>
    </div>
</div>
</div>
    <div id="footpanel">
        <div id="hw">
        </div>
	</div>

	<?php echo '
    <script>
      //stick the footer at the bottom of the page if we\'re on an iPad/iPhone due to viewport/page bugs in mobile webkit
	  if(navigator.platform == \'iPad\' || navigator.platform == \'iPhone\' || navigator.platform == \'iPod\')
	  {
		  $("#footpanel").css("position", "static");
		};
	</script>
	<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push([\'_setAccount\', \'UA-867847-29\']);
_gaq.push([\'_trackPageview\']);
(function() {
var ga = document.createElement(\'script\'); ga.type = \'text/javascript\';
ga.async = true;

ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' :
\'http://www\') + \'.google-analytics.com/ga.js\';

var s = document.getElementsByTagName(\'script\')[0];
s.parentNode.insertBefore(ga, s);

})();
</script>
	'; ?>

</body>
</html>