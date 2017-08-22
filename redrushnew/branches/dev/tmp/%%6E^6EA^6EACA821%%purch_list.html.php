<?php /* Smarty version 2.6.13, created on 2012-04-05 11:44:20
         compiled from RedRushWeb/admin/merchandise/purch_list.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>MERCHANDISE PURCHASE LIST</h1>
    </td>
    <td align="right">
    	<form>Search: <input name="q" type="text" <?php if ($this->_tpl_vars['q']): ?>value="<?php echo $this->_tpl_vars['q']; ?>
"<?php endif; ?>>
<input name="s" type="hidden" value="merchandise">
<input name="act" type="hidden" value="purchase-list">
<input name="go" type="submit" value="Go"></form>
    </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
  <tr>
  	 <td width="10%" align="center"><strong>Purchase Date</strong></td>
    <td align="center"><strong>Name</strong></td>
    <td align="center"><strong>Merchandise</strong></td>
    <td align="center"><strong>Status</strong></td>
    <td align="center" width="15%"><strong>Action</strong></td>
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
  	<td align="center"><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['purchase_date']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['user_name']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['item_name']; ?>
</td>
    <td align="center"><div id="status<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
">
    	<?php if ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 1): ?>
			<font color="green">Approved</font>
		<?php elseif ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 0): ?>
			<font color="gray">Pending</font>
        <?php else: ?>
        	<font color="red">Rejected</font>
		<?php endif; ?></div></div></td>
    <td align="center">
    <a href="index.php?s=merchandise&act=del-purchase&id=<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" onclick="return confirm('Are you sure you want to delete?')">Delete</a></strong> | 
    <a href="#" id="changeStatus<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" onclick="return showChanger(<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
);">Change Status</a>
        <div id="changer<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" style="display:none;">
        <div id="loader<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" style="display:none;"><img src="images/loading.gif" /></div>
        <p id="msg<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
"></p>
        <form id="formChanger" class="formChanger">
        <input id="pid" class="pid" type="hidden" value="<?php echo $this->_tpl_vars['list'][$this->_sections['n']['index']]['id']; ?>
" />
        <select id="stat" class="stat">
          <?php if ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 1): ?>
          <option value="0">Pending</option>
          <option value="1" selected="selected">Approve</option>
          <option value="2">Reject</option>
          <?php elseif ($this->_tpl_vars['list'][$this->_sections['n']['index']]['n_status'] == 2): ?>
          <option value="0">Pending</option>
          <option value="1">Approve</option>
          <option value="2" selected="selected">Reject</option>
          <?php else: ?>
          <option value="0" selected="selected">Pending</option>
          <option value="1">Approve</option>
          <option value="2">Reject</option>
          <?php endif; ?>
        </select><input id="save" type="submit" value="Go" />
        </form>
        </div> 
    </td>
  </tr>
  <?php endfor; endif; ?>
</table>
<div class="paging"><?php echo $this->_tpl_vars['paging']; ?>
</div>

<script type="text/javascript">
   <?php echo '
    jQuery(document).ready(function($) {
	  
	  $(\'.formChanger\').submit(function(){
		  var id = $(this).find(".pid").val();
		  var stat = $(this).find(".stat").val();
		  $(\'#loader\'+id).show();
		  //alert(id);
		  var p = {\'id\':id,\'status\':stat};
		  $.post(\'index.php?s=merchandise&act=change-purchase\',p, function(data) {
			  $(\'#msg\'+id).html(data);
			  if(stat==0){
			  $(\'#status\'+id).html(\'<font color=gray>Pending</font>\');
			  }
			  else if(stat==1){
			  $(\'#status\'+id).html(\'<font color=green>Approved</font>\');  
			  }
			  else{
			  $(\'#status\'+id).html(\'<font color=red>Rejected</font>\');	  
			  }
			   $(\'#loader\'+id).hide();
		  });
		  return false;
	  });
    });
	function showChanger(id){
		$("#changer"+id).slideToggle();
		$(\'#msg\'+id).html(\'\');
		return false;
	}
	'; ?>

  </script>