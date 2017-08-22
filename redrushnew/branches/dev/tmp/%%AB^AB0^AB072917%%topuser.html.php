<?php /* Smarty version 2.6.13, created on 2012-06-05 10:24:55
         compiled from RedRushWeb//topuser.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'RedRushWeb//topuser.html', 18, false),)), $this); ?>

<div id="main-container" class="lights">
	<div class="wrapper">
    	<div id="containers">
            <div class="logo">
            	<a href="index.php">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel2">
            	<div class="entry" style="padding:0 30px 0 50px;">
                    <div class="title">
                        <h1>Top Users</h1>
                    </div><!-- .title -->
                        <div class="scrollbar" style="height:250px;">
                            <div class="box racer">
							<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['top_user']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <div class="row">
                                    <div class="thumb">
                                        <a href="index.php?page=garage&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
"><img src="<?php if ($this->_tpl_vars['top_user'][$this->_sections['i']['index']]->small_img == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['top_user'][$this->_sections['i']['index']]->small_img; ?>
 <?php endif; ?>" /></a>
                                    </div><!-- .thumb -->
                                    <div class="caption">
                                        <span class="username" style="font-size:22px;"><?php echo $this->_tpl_vars['top_user'][$this->_sections['i']['index']]->name; ?>
</span>
                                                                                                                    </div><!-- .caption -->
                                    <div class="action-challenge">
                                         <?php if ($this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token != ''): ?>
										 <a class="challenge icon_race2" href="index.php?page=race&act=challenge&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['top_user'][$this->_sections['i']['index']]->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
">Challenge</a>
										 <?php endif; ?>
                                    </div><!-- .action -->
                                </div><!-- .row -->
                            <?php endfor; endif; ?>
                            </div><!-- .box -->
                        </div><!-- .scrollbar -->
                </div><!-- .entry -->
            </div><!-- .panel -->
            <div id="sidebar">
            	<div class="entry">  
                    <div id="profile2">
                        <div class="thumb"><a href="index.php?page=garage"><img src="<?php if ($this->_tpl_vars['small_img'] == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['small_img']; ?>
 <?php endif; ?>" /></a></div>
                        <div class="box-user">
                            <span class="username"><?php echo $this->_tpl_vars['name']; ?>
</span>
                            <span class="reputation"><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><img src="img/star.png" alt="" /><?php endfor; endif; ?></span>
                            <span class="total-race"><?php echo $this->_tpl_vars['races']; ?>
 Race</span>
                            <span class="total-win"><?php echo $this->_tpl_vars['wins']; ?>
 Wins</span>
                            <span class="rank">Rank <?php echo $this->_tpl_vars['rank']; ?>
</span>
                            <span class="total-point"><?php echo $this->_tpl_vars['points']; ?>
 PTS</span>
                        </div>
                    </div><!-- #profile --> 
                </div><!-- .entry -->
            </div><!-- #sidebar -->
            <div class="lights-img"></div>
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->
