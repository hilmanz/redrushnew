<?php /* Smarty version 2.6.13, created on 2012-04-12 07:04:28
         compiled from Social/login.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SBA</title>
<link href="css/sba_login.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="js/pngfix.js"></script>
<![endif]-->

</head>

<body id="landing">
	<div id="wrapper-login">
		<?php if ($this->_tpl_vars['login_error']): ?>
		<script>
			alert("Maaf, username dan/atau password salah !");
		</script>
		<?php endif; ?>
    	<form class="login-form" method="post" enctype="application/x-www-form-urlencoded">
        	<label class="login-here">&nbsp;</label>
        	
            <input name="username" id="username" type="text" value="Email" />
            <input name="password" id="password" type="password" value="" />
            <input name="login" id=""login"" type="hidden" value="1" />
            <input type="submit" value="&nbsp;" class="btn-login" />
            
        </form>
        <div id="footer-login">
             <p>Informasi dalam website ini ditujukan untuk perokok berusia 18 tahun atau lebih dan tinggal di wilayah Indonesia<br />
            Jika kamu tidak ingin dihubungi oleh PT HM Sampoerna Tbk melalui email, kamu bisa hapus kontak kamu dengan mengklik link hapus saya dibawah ini.</p>
    		<div class="link">
            <a href="index.php">Halaman Utama </a>|
            <a href="syarat.html" target="_blank">Syarat dan Ketentuan</a>|
            <a href="hapus_saya.php" target="_blank">Hapus saya</a>|
            <a href="contact.html" target="_blank">Kontak Kami</a>|
            <!-- <a href="#">PMI Corporate</a>|-->
            <a href="http://www.pmi.com/id/smokingandhealth" target="_blank">Perihal Merokok</a>
            </div>
        </div>
    </div>
    <?php echo '
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