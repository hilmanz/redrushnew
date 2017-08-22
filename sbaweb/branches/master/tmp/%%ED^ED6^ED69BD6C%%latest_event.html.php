<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Social/widgets/latest_event.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Social/widgets/latest_event.html', 12, false),array('modifier', 'strip_tags', 'Social/widgets/latest_event.html', 12, false),)), $this); ?>
<div>

	<div class="title">
            	<h1>UPCOMING EVENTS</h1>
                <a href="?events=1" class="view-all">Lihat Semua</a>
    </div>
	<div class="bg1">
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
		<div>
			<div style="color:#f00;"><b><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['tanggal_event']; ?>
</b></div>
			<div style="color:#f00;"><b><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['nama_event']; ?>
</b></div>
			<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['summary'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</div>
			<div><a href="index.php?events=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">Detail</a></div>
		</div>
		<?php endfor; endif; ?>
		<!--
		<div style="margin-bottom:10px;">
			<div style="color:#f00;"><b>15 Maret 2011</b></div>
			<div>Kumpul-kumpul bareng temen kampus di Hall A</div>
			<div><a href="index.php?events=1&id=23">Detail</a></div>
		</div>
		<div style="margin-bottom:10px;">
			<div style="color:#f00;"><b>15 Maret 2011</b></div>
			<div>Kumpul-kumpul bareng temen kampus di Hall A</div>
			<div><a href="index.php?events=1&id=23">Detail</a></div>
		</div>
		-->
	</div>
</div>