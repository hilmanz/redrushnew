<?php /* Smarty version 2.6.13, created on 2012-06-25 17:53:38
         compiled from RedRushWeb/master.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/meta.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
		
	<div id="wrap">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
			<?php echo $this->_tpl_vars['mainContent']; ?>

			
		
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
    <?php echo '
	
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