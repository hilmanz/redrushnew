<?php /* Smarty version 2.6.13, created on 2012-04-12 07:17:19
         compiled from Social/daftar_top_performer.html */ ?>
<div id="title-bar">
            	<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
            	<div class="cMenu">
            	<a class="cTopPerformers" href="index.php?top_performers=1">All</a>
            	<a class="cTopPerformers" href="index.php?top_performers=1&show=ba">Brand Ambasadors</a>           	
            	<a class="cTopPerformers" href="index.php?top_performers=1&show=pl">Project Leaders</a>
            	</div>
            </div><!-- end div Title Bar -->
        	<div id="w650">
            	<div class="galery-ba">
            	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['ba_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <div class="ba-list">
                        <a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['id']; ?>
"><img src="<?php if ($this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['img']):  echo $this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['img'];  else: ?>images/no_photo_small.gif<?php endif; ?>" width="160" height="160" /></a>
                        <span class="username"><?php echo $this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['name']; ?>
</span>
                        <?php if (! $this->_tpl_vars['is_pl']): ?><span class="percent"><?php echo $this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['progress']; ?>
 %</span><?php endif; ?>
						<?php if ($this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['ba'] == 0): ?>
						<?php if ($this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['friend'] == 0): ?>
						<span class="add-friend">
							<a href="index.php?add=1&u=<?php echo $this->_tpl_vars['ba_list'][$this->_sections['i']['index']]['id']; ?>
">Add as a Friend</a>
						</span>
						<?php endif;  endif; ?>
                    </div>
                 <?php endfor; endif; ?>
                </div>
                <div><?php echo $this->_tpl_vars['paging']; ?>
</div>
            </div><!-- end div w650 -->
        	<div id="col-right">
            	<?php echo $this->_tpl_vars['acara_terkini']; ?>

            	<?php echo $this->_tpl_vars['photo_gallery']; ?>

            	<?php echo $this->_tpl_vars['banner']; ?>

            </div><!-- end div col right -->