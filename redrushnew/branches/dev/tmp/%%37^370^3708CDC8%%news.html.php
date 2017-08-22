<?php /* Smarty version 2.6.13, created on 2012-06-25 17:51:16
         compiled from RedRushWeb//news.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'RedRushWeb//news.html', 44, false),array('modifier', 'strip_tags', 'RedRushWeb//news.html', 44, false),)), $this); ?>

<div id="popupInfo" style="left: 0">
    <div id="popupInfo-content" >
    
    	<div class="popupborder " style="overflow:hidden; height:320px;">
        		<div style="display:block;">
                    <a class="popup-close" href="index.php?page=garage" style="right:18px; top:142px;">[x] Close</a>
                        <h1 style="font-size:40px; color:#fff; margin:0 0 0 0"><a style="color:#fff;" href="index.php?page=garage">Check your Rush Points now!</a> </h1>
<h2 style="font-size:30px; margin:15px 0 25px 0;"><span>We'll reach our finish line on June 30, 2012. <br />
Now is time to redeem your points (points redemption still open till July 7, 2012). <br />
Go ahead and get those exclusive merchandises home!</span></h2>
</span></h2>

<h2 style="font-size:24px; width:500px; margin:0 auto;" class="yellow"> 
Thank you for participating in Marlboro RedRush. <br />
Get ready for another exciting program from Marlboro! Ciao!</h2>
                </div>
        </div>
    </div>
</div>    
<?php echo '   
<style>
#popupInfo-content .jScrollPaneTrack{
left: 890px;
}
</style>
'; ?>

<div id="main-container">
	<div class="wrapper">
    	<div id="containers">
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/newsfeedSubmenu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="logo">
            	<a href="index.php">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel">
            	<div class="entry">
                    <div class="title">
                        <h1>NEWS AND UPDATES</h1>
                    </div><!-- .title -->
                    <div class="scrollbar">
                    		<?php if ($this->_tpl_vars['news']): ?>
                            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                            <div class="list-news">
							<h1 class="title-news"> <a href="index.php?page=news&act=view&nid=<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['news'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a> </h1>
                            <p class="brief-news">
                            <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['news'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

                            </p>
                            <a href="index.php?page=news&act=view&nid=<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
">Details here &raquo;</a>
                            </div>
                            <?php endfor; endif; ?>
                            <?php else: ?>
                            No news and update available.
                            <?php endif; ?>
                    </div><!-- .scrollbar -->
                    <?php echo $this->_tpl_vars['paging']; ?>

                </div><!-- .entry -->
            </div><!-- .panel -->
            <div id="sidebar">
            	<div class="entry">
                	<div class="box">
                        <div class="title-box">
                            <h1>Featured News</h1>
                        </div><!-- .title -->  
                        <div class="box-red" id="feartured-news">
						    <div class="content">
                                    <?php if ($this->_tpl_vars['featured']): ?>
                                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['featured']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                                    <div class="list">
                                    <p>
                                    <h1 class="title-news"><a href="index.php?page=news&act=view&nid=<?php echo $this->_tpl_vars['featured'][$this->_sections['i']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['featured'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a></h1>
                                    <span class="brief-news">
                                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['featured'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

                                    </span>
                                    <br />
                                    <a href="index.php?page=news&act=view&nid=<?php echo $this->_tpl_vars['featured'][$this->_sections['i']['index']]['id']; ?>
">Details here &raquo;</a>
                                    </p>
                                    </div><!-- .list -->
                                    <?php endfor; endif; ?>
                                    <?php else: ?>
                                    No featured news available.
                                    <?php endif; ?>
                            </div><!-- .content -->   
                        </div><!-- .box-red -->   
                    </div><!-- .box -->   
                </div><!-- .entry -->
            </div><!-- #sidebar -->
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->