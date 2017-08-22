<?php /* Smarty version 2.6.13, created on 2012-04-05 11:36:21
         compiled from common/admin/pages-add.html */ ?>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.min.js"></script>
<?php echo '
<script type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
        mode : "exact",
        elements : "teditor",
		
		plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

		
		file_browser_callback : "ajaxfilemanager",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : true,
		apply_source_formatting : true,
		force_br_newlines : true,
		force_p_newlines : false,	
		relative_urls : true,
		
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false
		
	});
	function ajaxfilemanager(field_name, url, type, win) {
		var ajaxfilemanagerurl = "jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
		var view = \'detail\';
		switch (type) {
			case "image":
			view = \'thumbnail\';
				break;
			case "media":
				break;
			case "flash": 
				break;
			case "file":
				break;
			default:
				return false;
		}
		tinyMCE.activeEditor.windowManager.open({
		    url: "jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
		    width: 782,
		    height: 440,
		    inline : "yes",
		    close_previous : "no"
		},{window : win, input : field_name });
	}
	
function validator(){
	tinyMCE.triggerSave();
	if( $(\'#title\').val() == \'\' ){
		alert("Please fill title");
		return false;
	}else if( $(\'#brief\').val() == \'\' ){
		alert("Please fill brief");
		return false;
	}else if( $(\'#teditor\').val() == \'\' ){
		alert("Please fill desc");
		return false;
	}
}		
</script>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
    	<h1>PAGE CREATE NEW</h1>
    </td>
    <td align="right">
	 <h1><a href="?s=pages">BACK</a></h1>
    </td>
</tr>
</table>
<?php if ($this->_tpl_vars['err']): ?>
<p>
	Error Create Page
	<ul>
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['err']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<li><?php echo $this->_tpl_vars['err'][$this->_sections['i']['index']]; ?>
</li>
	<?php endfor; endif; ?>
	</ul>
</p>
<?php endif; ?>
<form method="POST" action="?s=pages&act=add">
<table class="addlist" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>Name</td><td><input type="text" name="name" value="<?php echo $this->_tpl_vars['name']; ?>
" /></td>
	</tr>
	<tr>
		<td>Request</td><td><input type="text" name="request" value="<?php echo $this->_tpl_vars['request']; ?>
" /></td>
	</tr>
	<tr>
		<td>Template</td><td><input type="text" name="template" value="<?php echo $this->_tpl_vars['template']; ?>
" />&nbsp;.HTML</td>
	</tr>
	<tr>
		<td>Content</td>
		<td>
			<select name="content">
				<option value="no" <?php if ($this->_tpl_vars['content'] == 'no'): ?>selected="selected"<?php endif; ?>>No</option>
				<option value="yes" <?php if ($this->_tpl_vars['content'] == 'yes'): ?>selected="selected"<?php endif; ?>>Yes</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Content Title</td><td><input type="text" name="content_title" value="<?php echo $this->_tpl_vars['content_title']; ?>
" /></td>
	</tr>
	<tr>
		<td>Content Text</td><td><textarea name="content_text" id="teditor" cols="70" rows="15"><?php echo $this->_tpl_vars['content_text']; ?>
</textarea></td>
	</tr>
	<tr>
		<td>Status</td>
		<td>
			<select name="status">
				<option value="deactive" <?php if ($this->_tpl_vars['status'] == 'deactive'): ?>selected="selected"<?php endif; ?>>Deactive</option>
				<option value="active" <?php if ($this->_tpl_vars['status'] == 'active'): ?>selected="selected"<?php endif; ?>>Active</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Login</td>
		<td>
			<select name="login">
				<option value="yes" <?php if ($this->_tpl_vars['login'] == 'yes'): ?>selected="selected"<?php endif; ?>>Yes</option>
				<option value="no" <?php if ($this->_tpl_vars['login'] == 'no'): ?>selected="selected"<?php endif; ?>>No</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Widgets</td>
		<td>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['widgets']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<span>
				<input type="checkbox" name="widgets[]" value="<?php echo $this->_tpl_vars['widgets'][$this->_sections['i']['index']]['name']; ?>
" />&nbsp;<?php echo $this->_tpl_vars['widgets'][$this->_sections['i']['index']]['name']; ?>

			</span>
			&nbsp;
			<?php endfor; endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="hidden" value="1" name="add" />
			<input type="submit" value="Create New" />
		</td>
	</tr>
</table>
</form>