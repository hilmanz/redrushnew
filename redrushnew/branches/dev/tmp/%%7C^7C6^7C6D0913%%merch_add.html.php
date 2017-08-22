<?php /* Smarty version 2.6.13, created on 2012-04-13 11:07:14
         compiled from RedRushWeb/admin/merchandise/merch_add.html */ ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>ADD NEW MERCHANDISE</h1>
    </td>
</tr>
</table>
<?php if ($this->_tpl_vars['err']): ?><span style="color:#F00;"><?php echo $this->_tpl_vars['err']; ?>
</span><?php endif; ?>
<form enctype="multipart/form-data" name="insertmerch" id="insert" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
  <tr>
    <td width="11%" valign="top"><strong>Item Name</strong></td>
    <td width="89%"><label for="name"></label>
      <input type="text" name="name" id="name" <?php if ($this->_tpl_vars['form']): ?>value="<?php echo $this->_tpl_vars['form']['name']; ?>
"<?php endif; ?>> <span id="errname" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Amount</strong></td>
    <td><label for="amount"></label>
      <input type="text" name="amount" id="amount" <?php if ($this->_tpl_vars['form']): ?>value="<?php echo $this->_tpl_vars['form']['amount']; ?>
"<?php endif; ?>> <span id="erramount" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Prizes</strong></td>
    <td><label for="prizes"></label>
      <input type="text" name="prizes" id="prizes" <?php if ($this->_tpl_vars['form']): ?>value="<?php echo $this->_tpl_vars['form']['prizes']; ?>
"<?php endif; ?>>pts. <span id="errprizes" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Description</strong></td>
    <td><label for="desc"></label>
      <textarea name="desc" id="desc" cols="45" rows="5"></textarea> <span id="errdesc" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Level</strong></td>
    <td><label for="level"></label>
	<select name="level" id="level">
	<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
	<option value='<?php echo $this->_sections['foo']['iteration']; ?>
' ><?php echo $this->_sections['foo']['iteration']; ?>
</option>
    <?php endfor; endif; ?>
	</select>
   </td>
  </tr>
  <tr>
    <td valign="top"><strong>Primary Images</strong></td>
    <td><label for="images"></label>
      <input type="file" name="images" id="images"> <span id="errimg" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Thumbnail Images</strong></td>
    <td><label for="images"></label>
      <input type="file" name="timages" id="timages"> <span id="errtimg" style="color:#F00;"></span></td>
  </tr>
  <tr>
    <td valign="top"><strong>Status</strong></td>
    <td><label for="status"></label>
      <select name="status" id="status">
        <option value="1" selected>Publish</option>
        <option value="0">Unpublish</option>
      </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="save" type="hidden" value="1"><input type="submit" name="Save" id="Save" value="Save"></td>
  </tr>
</table>
</form>
<script type="text/javascript">
	<?php echo '
	$(document).ready(function(){
		$(\'#insert\').submit(function(){
			var name = $(\'#name\').val();
			var amount = $(\'#amount\').val();
			var prizes = $(\'#prizes\').val();
			var desc = $(\'#desc\').val();
			var img = $(\'#images\').val();
			var timg = $(\'#timages\').val();
			if(name==\'\'){$(\'#errname\').html(\'Please enter item name!\');$(\'#name\').focus(); return false;}else{$(\'#errname\').html(\'\');}
			if(amount==\'\'){$(\'#erramount\').html(\'Please enter item amount!\');$(\'#amount\').focus(); return false;}else{$(\'#erramount\').html(\'\');}
			if(prizes==\'\'){$(\'#errprizes\').html(\'Please enter item prizes!\');$(\'#prizes\').focus(); return false;}else{$(\'#errprizes\').html(\'\');}
			if(desc==\'\'){$(\'#errdesc\').html(\'Please enter item description!\');$(\'#desc\').focus(); return false;}else{$(\'#errdesc\').html(\'\');}
			if(img==\'\'){$(\'#errimg\').html(\'Please enter item images!\');$(\'#images\').focus(); return false;}else{$(\'#errimg\').html(\'\');}
			if(timg==\'\'){$(\'#errtimg\').html(\'Please enter item thumbnail images!\');$(\'#timages\').focus(); return false;}else{$(\'#errtimg\').html(\'\');}
		});
	});
	
'; ?>

</script>