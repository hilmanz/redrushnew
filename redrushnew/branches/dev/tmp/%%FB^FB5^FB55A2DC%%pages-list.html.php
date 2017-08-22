<?php /* Smarty version 2.6.13, created on 2012-04-05 11:45:55
         compiled from common/admin/pages-list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', 'common/admin/pages-list.html', 12, false),array('modifier', 'strip_tags', 'common/admin/pages-list.html', 13, false),array('modifier', 'stripslashes', 'common/admin/pages-list.html', 13, false),)), $this); ?>
<h1>PAGE LIST</h1>
<form method="post" action="?s=pages&act=delete">
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="head">
    <td width="10"></td>
	<td><strong>Name</strong></td>
    <td><strong>Request</strong></td>
	<td><strong>Status</strong></td>
  </tr>
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
  <tr>
	<td><input type="checkbox" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['page_id'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" name="page_id[]"></td>
    <td><a href="index.php?s=pages&act=edit&id=<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['page_id'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['page_name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</a></td>
    <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['page_request'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['page_status'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
  </tr>
  <?php endfor; endif; ?>
</table>
<p>
<input type="button" onclick="javascript:document.location.href='?s=pages&act=add';" value="Create New Page" />
<input type="submit" value="Delete Selected Page" />
</p>
</form>