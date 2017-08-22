<?php /* Smarty version 2.6.13, created on 2012-04-05 11:40:10
         compiled from RedRushWeb/admin/news_comment_all.html */ ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>COMMENTS</h1>
    </td>
</tr>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
	<tr>
		<td colspan="6">
			<label>Status</label>&nbsp;
			<select id="status">
				<option value="" <?php if ($this->_tpl_vars['status'] == ''): ?>selected<?php endif; ?>>all</option>
				<option value="0" <?php if ($this->_tpl_vars['status'] == '0'): ?>selected<?php endif; ?>>pending</option>
				<option value="1" <?php if ($this->_tpl_vars['status'] == '1'): ?>selected<?php endif; ?>>approve</option>
			</select>
		</td>
	</tr>
	<tr class="head">
		<td>Created date</td>
		<td>Username</td>
		<td>Comment</td>
		<td>Article title</td>
		<td>Article category</td>
		<td>Approval</td>
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
		<td><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['posted_date']; ?>
</td>
		<td><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['name']; ?>
</td>
		<td><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['comments']; ?>
</td>
		<td><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['title']; ?>
</td>
		<td><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['category_name']; ?>
</td>
		<td>
		<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['n_status'] == 0): ?>
			<a href="index.php?s=news&act=setcom&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
&set=1&nid=<?php echo $this->_tpl_vars['nid']; ?>
" class="editPage"><font color="red">No</font></a>
		<?php else: ?>
			<a href="index.php?s=news&act=setcom&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
&set=0&nid=<?php echo $this->_tpl_vars['nid']; ?>
" class="editPage"><font color="green">Yes</font></a>
		<?php endif; ?>
		<a onclick="return confirm('Apakah anda yakin akan menghapus komentar ini ?');" href="index.php?s=news&act=delcom&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
&nid=<?php echo $this->_tpl_vars['nid']; ?>
" class="deletePage">Delete</a>
		</td>
	</tr>
	<?php endfor; endif; ?>
</table>
<p><?php echo $this->_tpl_vars['paging']; ?>
</p>

<?php echo '
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
$(\'document\').ready(function(){
	$(\'#status\').change(function(){
		var status = $(this).val();
		document.location.href = \'index.php?s=news&act=comment&status=\'+status;
	});
});
</script>
'; ?>
