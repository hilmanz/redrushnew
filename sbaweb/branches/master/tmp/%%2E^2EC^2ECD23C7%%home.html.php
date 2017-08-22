<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:28
         compiled from Social/home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'Social/home.html', 4, false),)), $this); ?>
<div id="title-bar">
            	<h1>Home</h1>
                <div class="info">
               		<span class="welcome">Selamat datang,</span><a href="index.php?profile=1" class="username"><?php echo ((is_array($_tmp=$this->_tpl_vars['user']['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a>
                </div>
            </div><!-- end div Title Bar -->
        	<div id="col-left">
            	<?php echo $this->_tpl_vars['top_performers']; ?>

				<?php echo $this->_tpl_vars['top_city']; ?>

            	<!--
            	<?php echo $this->_tpl_vars['top_event']; ?>

				-->
			</div><!-- end div col left -->
            <div id="col-center">
            	<?php echo $this->_tpl_vars['berita_terbaru']; ?>

            	<?php echo $this->_tpl_vars['network_updates']; ?>

            </div><!-- end div col center -->
        	<div id="col-right">
            	<?php echo $this->_tpl_vars['acara_terkini']; ?>

                <?php echo $this->_tpl_vars['banner']; ?>

            </div><!-- end div col right -->
            <div id="footer-content">
            	<?php echo $this->_tpl_vars['gallery_updates']; ?>

            </div><!-- end div Footer Content -->