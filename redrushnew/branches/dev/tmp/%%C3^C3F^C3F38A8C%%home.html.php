<?php /* Smarty version 2.6.13, created on 2012-06-25 10:11:05
         compiled from RedRushWeb//home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'RedRushWeb//home.html', 23, false),array('modifier', 'strip_tags', 'RedRushWeb//home.html', 23, false),array('modifier', 'htmlentities', 'RedRushWeb//home.html', 69, false),array('modifier', 'intval', 'RedRushWeb//home.html', 70, false),)), $this); ?>
<div id="main-container">
	<div class="wrapper">
    	<div id="containers">
            <div class="welcome">
            	<h1>Ciao, <?php echo $this->_tpl_vars['user_name']; ?>
 !<br /></h1>
<p>Welcome to RedRush, where you can experience the ultimate thrills of your life!
Get a chance for a week full of unforgettable experiences in Italy, and exclusive prizes throughout the program. Step into the race, start your engine, win races, and make your mark!
</p>
            </div>
            <div class="logo">
            	<a href="index.php">&nbsp;</a>
            </div><!-- .logo -->
            <div id="panel-home">
                <div class="panel-small">
                    <div class="entry">
                        <div class="title">
                            <h1><a href="index.php?page=news">NEWS AND UPDATES</a></h1>
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
</a></h1>
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
                    </div><!-- .entry -->
                </div><!-- .panel-small -->
                <div class="panel-small">
                    <div class="entry">
                        <div class="title">
                            <h1><a href="index.php?page=news&act=recent">RECENT ACTIVITY</a></h1>
                        </div><!-- .title -->
                        <div class="scrollbar" id="scrollpanel">
                            <ul class="newsfeed">
								<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['notification']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<li>
								 <span class="feeds"><?php echo $this->_tpl_vars['notification'][$this->_sections['i']['index']]->message; ?>
<span class="tip" style="display:none;"><img src="<?php if ($this->_tpl_vars['notification'][$this->_sections['i']['index']]->small_img != ''): ?>contents/avatar/small/<?php echo $this->_tpl_vars['notification'][$this->_sections['i']['index']]->small_img;  endif; ?>" /></span></span>
                                 <span class="date-feeds"><?php echo $this->_tpl_vars['notification'][$this->_sections['i']['index']]->date_time; ?>
</span>
								</li>
                                <?php endfor; endif; ?>         	  
                           </ul>
                        </div><!-- .scrollbar -->
                    </div><!-- .entry -->
                </div><!-- .panel-small -->
            </div><!-- #panel-home -->
            <div id="sidebar">
            	<div class="entry">
                	<div class="box random-user">
                        <div class="titles">
                            <h1>Random Users</h1>
                        </div><!-- .title -->  
                        <div class="red-box">
                        	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['racer']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
							<?php if (( $this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'] >= $this->_tpl_vars['level']-1 ) && ( $this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'] <= $this->_tpl_vars['level']+1 )): ?>
							<div class="row-race">
                            	<div class="thumb">
                                	<a href="index.php?page=garage&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['racing_token'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
"><img src="<?php if ($this->_tpl_vars['racer'][$this->_sections['i']['index']]['small_img'] == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['racer'][$this->_sections['i']['index']]['small_img']; ?>
 <?php endif; ?>" /></a>
                                </div><!-- .thumb -->
                            	<div class="caption">
                                	<span class="username"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>
</span>
                                	<span class="level">Level <?php echo ((is_array($_tmp=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
</span>
                                	<span class="reputation"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?><img src="img/star.png" alt="" /><?php endfor; endif; ?></span>
                                </div><!-- .caption -->
                                <div class="action-race">
                                	                                	<a class="challenge icon_race2" href="index.php?page=race&act=challenge&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['racing_token'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
">Challenge</a>
                                </div><!-- .action -->
                            </div><!-- .row -->
							<?php endif; ?>
                        	  <?php endfor; endif; ?>
							
                        </div><!-- .red-box -->
                    </div><!-- .random-user -->  
                	<div class="box top-user">
                        <div class="titles">
                            <h1>Top Users</h1>
                        </div><!-- .title -->  
                        <div class="red-box">
                        	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['top_user']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['max'] = (int)2;
$this->_sections['i']['show'] = true;
if ($this->_sections['i']['max'] < 0)
    $this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
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
							<div class="row-race">
                            	<div class="thumb">
                                	<a href="index.php?page=garage&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
"><img src="<?php if ($this->_tpl_vars['top_user'][$this->_sections['i']['index']]->small_img == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['top_user'][$this->_sections['i']['index']]->small_img; ?>
 <?php endif; ?>" /></a>
                                </div><!-- .thumb -->
                            	<div class="caption">
                                	<span class="username"><?php echo $this->_tpl_vars['top_user'][$this->_sections['i']['index']]->name; ?>
</span>
                                	<span class="level">Level <?php echo $this->_tpl_vars['top_user'][$this->_sections['i']['index']]->level; ?>
</span>
                                	<span class="reputation"><?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['top_user'][$this->_sections['i']['index']]->level) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?><img src="img/star.png" alt="" /><?php endfor; endif; ?></span>
                                </div><!-- .caption -->
                                <div class="action-race">
                                	                                	 <?php if ($this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token != ''): ?>
									 <a class="challenge icon_race2" href="index.php?page=race&act=challenge&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
">Challenge</a>
									 <?php endif; ?>
                                </div><!-- .action -->
							
                            </div><!-- .row -->
                        	<?php endfor; endif; ?>
								<div ><span><a href="?page=topuser" style="font-size: 12px;
    line-height: 1.4;color: #FBDB00; font-family: 'KlavikaLightCapsLight',Helvetica,sans-serif;outline: medium none;
    text-decoration: none;">Details here >></a></span></div>
						</div><!-- .red-box -->
                    </div><!-- .top-user -->    
                </div><!-- .entry -->
            </div><!-- #sidebar -->
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-info.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php if ($this->_tpl_vars['event']): ?>  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['event']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
			<?php if ($this->_tpl_vars['popup_info']): ?>  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['popup_info']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
			
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->