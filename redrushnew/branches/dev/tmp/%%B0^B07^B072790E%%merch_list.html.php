<?php /* Smarty version 2.6.13, created on 2012-04-13 10:45:02
         compiled from RedRushWeb/admin/merchandise/merch_list.html */ ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>MERCHANDISE LIST</h1>
    </td>
    <td align="right">
    	<form>Search: <input name="q" type="text" <?php if ($this->_tpl_vars['q']): ?>value="<?php echo $this->_tpl_vars['q']; ?>
"<?php endif; ?>><input name="s" type="hidden" value="merchandise"><input name="go" type="submit" value="Go"></form>
    </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
  <tr>
    <td width="10" align=" "><strong>ID</strong></td>
	<td align=" "><strong>Level</strong></td>
    <td align=" "><strong>Merchandise</strong></td>
    <td align=" "><strong>Amount</strong></td>
    <td align=" "><strong>Prizes</strong></td>
    <td align=" "><strong>Input Date</strong></td>
    <td align=" "><strong>Status</strong></td>
    <td align=" "><strong>Action</strong></td>
  </tr>
  <?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?>
  <tr>
  	
    <td><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
</td>
	 <td><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['level']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['item_name']; ?>
 <?php if ($this->_tpl_vars['list'][$this->_sections['n']['index']]['img'] != null): ?><br><img src="../public_html/img/merchandise/thumb_<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['img']; ?>
" width="64px"><?php endif; ?></td>
    <td align=" "><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['amount']; ?>
</td>
    <td align=" "><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['prize']; ?>
</td>
    <td align=" "><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['created_date']; ?>
</td>
    <td align=" "><?php if ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 1): ?>Publish<?php else: ?>Unpublish<?php endif; ?></td>
    <td align=" ">
    <a href="index.php?s=merchandise&act=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
"><strong>Edit</strong></a> | 
    <a href="index.php?s=merchandise&act=change-status&id=<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
&status=<?php if ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 0): ?>1<?php else: ?>0<?php endif; ?>"><strong>Change Status</strong></a><strong> | 
    <a href="index.php?s=merchandise&act=delete&id=<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" onclick="return confirm('Are you sure you want to delete?')">Delete</a></strong></td>
  </tr>
  <?php endfor; endif; ?>
</table>
<div class="paging"><?php echo $this->_tpl_vars['paging']; ?>
</div>