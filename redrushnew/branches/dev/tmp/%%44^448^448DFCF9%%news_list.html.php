<?php /* Smarty version 2.6.13, created on 2012-04-05 11:47:29
         compiled from RedRushWeb/admin/news_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'RedRushWeb/admin/news_list.html', 54, false),array('modifier', 'strip_tags', 'RedRushWeb/admin/news_list.html', 54, false),)), $this); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>NEWS MANAGEMENT</h1>
    </td>
</tr>
</table>
<?php if ($this->_tpl_vars['msg']): ?><div style="color:#cc0000;"><?php echo $this->_tpl_vars['msg']; ?>
</div><?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
  <tr>
	<td colspan="10">
		<span style="display:inline-block;">
		<form method="GET" action="index.php">
		Category&nbsp;&nbsp;
		<input type="hidden" name="s" value="news" />
		<select name="group">
			<option value="">All</option>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['cat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<?php if ($this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_id'] == $this->_tpl_vars['cat_id']): ?>
			<option value="<?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_id']; ?>
" selected="selected"><?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_name']; ?>
</option>
			<?php else: ?>
			<option value="<?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_id']; ?>
"><?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_name']; ?>
</option>
			<?php endif; ?>
			<?php endfor; endif; ?>
		</select>
		<input type="submit" value=" GO " />
		</form>
		</span>
		<span style="display:inline-block;margin-left:50px;">
		<form>
			<input type="text" name="kw" />
			<input type="hidden" name="s" value="news" />
			<input type="submit" value="Search" />
		</form>
		</span>
	</td>
  </tr>
  <tr class="head">
    <td><strong>ID</strong></td>
    <td><strong>Title</strong></td>
    <td><strong>Brief</strong></td>
    <td><strong>Category</strong></td>
    <td><strong>Date</strong></td>
        <td><strong>Comments</strong></td>
    <td><strong>Status</strong></td>
    <td><strong>Featured</strong></td>
    <td  width="160"><strong>Action</strong></td>
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
    <td valign="top"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
</td>
    <td valign="top"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
    <td valign="top"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
    <td valign="top"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['category'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
    <td valign="top"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['posted_date'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
         <td align="center" valign="top"><?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['comment'] > 0): ?><a href="index.php?s=news&act=comment&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['comment']; ?>
</a><?php else: ?>0<?php endif; ?></td>
    <td valign="top">
    	<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['status'] == 0): ?>
    	Tidak aktif
    	<?php else: ?>
    	Aktif
    	<?php endif; ?>
    </td>
    <td valign="top"><?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['featured'] == 0): ?>
    	No
    	<?php else: ?>
    	Yes
    	<?php endif; ?></td>
    <td valign="top">
    	<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['featured'] == 0): ?>
    	<a href="index.php?s=news&act=featured&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
&f=1">Add Featured</a> | 
        <?php else: ?>
        <a href="index.php?s=news&act=featured&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
&f=0">Remove Featured</a> | 
    	<?php endif; ?>
		<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['category_id'] == 4): ?>
		<a href="index.php?s=news&act=editcalendar&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">Edit</a>&nbsp;|&nbsp;
		<?php else: ?>
		<a href="index.php?s=news&act=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">Edit</a>&nbsp;|&nbsp;
		<?php endif; ?>
		<a href="index.php?s=news&act=delete&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">Delete</a></td>
  </tr>
  <?php endfor; endif; ?>
</table>
<p><?php echo $this->_tpl_vars['paging']; ?>
</p>