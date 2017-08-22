<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:28
         compiled from Social/widgets/berita_terbaru.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Social/widgets/berita_terbaru.html', 9, false),)), $this); ?>
				<div class="title">
                    <h1>News Update</h1>
                    <a href="?news=1" class="view-all">Semua Berita</a>
                </div>
                <div class="content">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['img'] == ''): ?>
						<a href="index.php?news=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="list download" style="height:auto;">
							<span class="date"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span>
							<span class="info"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span>
						</a>
						<br style="clear:both" />
						<?php else: ?>
						<img class="img-left" src="contents/news/<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['img']; ?>
" />
						<div class="text">
							<span class="date"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span>
							<p><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</p>
							<a href="index.php?news=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">Lebih Lanjut &raquo;</a>
						</div>
						<br style="clear:both" />
						<?php endif; ?>
                    <?php endfor; endif; ?>
                </div>