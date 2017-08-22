<?php /* Smarty version 2.6.13, created on 2012-04-12 07:18:31
         compiled from common/paging.html */ ?>
<div id="paging">
<?php if ($this->_tpl_vars['isPrev']): ?><a href="<?php if ($this->_tpl_vars['base_url'] <> ""):  echo $this->_tpl_vars['base_url']; ?>
&st=<?php echo $this->_tpl_vars['begin'];  else: ?>?st=<?php echo $this->_tpl_vars['begin'];  endif; ?>">&lt;&lt;</a> <?php endif; ?>&nbsp;<?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['page']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
 if ($this->_tpl_vars['curr_page'] == $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']): ?><strong><?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']; ?>
</strong><?php else: ?><a href="<?php if ($this->_tpl_vars['base_url'] <> ""):  echo $this->_tpl_vars['base_url']; ?>
&st=<?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['start'];  else: ?>?st=<?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['start'];  endif; ?>"><?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']; ?>
</a><?php endif; ?>&nbsp;<?php endfor; endif;  if ($this->_tpl_vars['isNext']): ?><a href="<?php if ($this->_tpl_vars['base_url'] <> ""):  echo $this->_tpl_vars['base_url']; ?>
&st=<?php echo $this->_tpl_vars['last'];  else: ?>?st=<?php echo $this->_tpl_vars['last'];  endif; ?>">&gt;&gt;</a><?php endif; ?>
</div>