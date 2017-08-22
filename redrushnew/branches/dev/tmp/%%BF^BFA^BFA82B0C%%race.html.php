<?php /* Smarty version 2.6.13, created on 2012-05-09 17:30:28
         compiled from RedRushWeb//race.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'RedRushWeb//race.html', 22, false),array('modifier', 'stripslashes', 'RedRushWeb//race.html', 25, false),array('modifier', 'htmlentities', 'RedRushWeb//race.html', 25, false),array('modifier', 'intval', 'RedRushWeb//race.html', 26, false),)), $this); ?>

<div id="main-container">
	<div class="wrapper">
    	<div id="containers">
            <div class="welcome">
            	<h1>Lets GO...!!! <br />
				Choose Your Opponent!</h1>
            </div>
            <div class="logo">
            	<a href="index.php">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel">
            	<div class="entry">
                    <div class="title">
                        <h1>Random Users</h1>
                    </div><!-- .title -->
                    <div class="scrollbar">
                    	<div class="box racer">
							<?php if ($this->_tpl_vars['ultimateCar'] != ''): ?>
                          	  <div class="row" style="border-bottom:1px solid #000;">
                                <div class="thumb">
                                    <a href="index.php?page=garage&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['ultimateCar']->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
"><img src="<?php if ($this->_tpl_vars['ultimateCar']->small_img == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['ultimateCar']->small_img; ?>
 <?php endif; ?>" /></a>
                                </div><!-- .thumb -->
                                <div class="caption">
                                    <span class="username"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ultimateCar']->name)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>
</span>
                                    <span class="level">Level <?php echo ((is_array($_tmp=$this->_tpl_vars['ultimateCar']->level)) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
</span>
                                    <!--<span class="car-type">Ferrari</span>-->
                                    <span class="reputation"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['ultimateCar']->level) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <div class="action-challenge">
								<a class="challenge icon_race2" href="index.php?page=race&act=challenge_ultimate&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['ultimateCar']->racing_token)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
">Challenge</a>
									
								</div><!-- .action -->
                            </div><!-- .row -->
							<?php endif; ?>
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
								<?php if ($this->_tpl_vars['racer'][$this->_sections['i']['index']]['verified'] != '0'): ?>
							<?php if (( $this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'] >= $this->_tpl_vars['level']-1 ) && ( $this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'] <= $this->_tpl_vars['level']+1 )): ?>	
                            <div class="row" style="border-bottom:1px solid #000;">
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
                                    <!--<span class="car-type">Ferrari</span>-->
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
                                <div class="action-challenge">
                                    								
                                       <?php if ($this->_tpl_vars['racer'][$this->_sections['i']['index']]['level'] >= $this->_tpl_vars['level']-1): ?> <a class="challenge icon_race2" href="index.php?page=race&act=challenge&rtoken=<?php echo ((is_array($_tmp=$this->_tpl_vars['racer'][$this->_sections['i']['index']]['racing_token'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
">Challenge</a><?php endif; ?>
									
								</div><!-- .action -->
                            </div><!-- .row -->
								<?php endif;  endif; ?>
                            <?php endfor; endif; ?>
                          
                        </div><!-- .box -->
                    </div><!-- .scrollbar -->
                </div><!-- .entry -->
            </div><!-- .panel -->
            <div id="sidebar">
            	<div class="entry">
                	<div class="box my-statistic">
                        <div class="titles">
                            <h1>My Statistic</h1>
                        </div><!-- .title -->  
                        <div class="red-box">
                        	<div class="row">
                            	<p>
                                <span class="total-race"><?php echo $this->_tpl_vars['races']; ?>
 Races</span>
                                <span class="total-win"><?php echo $this->_tpl_vars['wins']; ?>
  Wins</span>
                                </p>
                                <p>
                                <span class="total-point"><?php echo $this->_tpl_vars['points']; ?>
 Points</span>
                                <span class="total-rank">Rank <?php echo $this->_tpl_vars['rank']; ?>
</span>
                                </p>
                            </div><!-- .row -->
                        </div><!-- .red-box -->
                    </div><!-- .my-statistic -->  
                	                </div><!-- .entry -->
            </div><!-- #sidebar -->
					<?php if ($this->_tpl_vars['event']): ?>  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['event']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->