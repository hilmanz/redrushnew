<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Social/widgets/ba_charts.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'Social/widgets/ba_charts.html', 10, false),array('modifier', 'number_format', 'Social/widgets/ba_charts.html', 11, false),)), $this); ?>
<div class="title">
<h1>TOP PERFORMERS</h1>
	<a href="index.php?top_performers=1" class="view-all">Lihat Semua</a>
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
	<div class="list">
	     	<a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><img width="75" height="75"  src="<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['small_img']):  echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['small_img'];  else: ?>images/no_photo_small.gif<?php endif; ?>"/></a>
	<div class="text">
		<a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="username"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a>
		<span class="percent"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['progress'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
%</span>
		<span class="daily">of daily Target</span>
	</div>
 </div>
 <?php endfor; endif; ?>
          </div>