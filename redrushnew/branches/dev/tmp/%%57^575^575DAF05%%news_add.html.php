<?php /* Smarty version 2.6.13, created on 2012-04-05 11:40:12
         compiled from RedRushWeb/admin/news_add.html */ ?>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../js/jquery-1.4.min.js"></script>
<?php echo '
<script type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
        mode : "exact",
        elements : "teditor",
		plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		paste_remove_styles: true,
		paste_auto_cleanup_on_paste : true,
		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

		file_browser_callback : "ajaxfilemanager",
		paste_use_dialog : true,
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
	//alert( \'sadsadsa\' );
	//return false;
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
    	<h1>ADD NEWS</h1>
    </td>
</tr>
</table>
<?php if ($this->_tpl_vars['msg']): ?><div style="color:#cc0000;"><?php echo $this->_tpl_vars['msg']; ?>
</div><?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="addlist zebra">
  <form method="post" action="" enctype="multipart/form-data" onsubmit="javascript: return validator();">
  <tr class="head">
    <td><strong>Title</strong></td>
  </tr>
  <tr>
    <td><input type="text" id="title" name="title" style="width:800px;" /></td>
  </tr>
  <tr class="head">
    <td><strong>Category</strong></td>
  </tr>
  <tr>
    <td>
    	<select name="category" style="width:800px;">
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
            <option value="<?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_id']; ?>
"><?php echo $this->_tpl_vars['cat'][$this->_sections['i']['index']]['category_name']; ?>
</option>
            <?php endfor; endif; ?>
        </select>
    </td>
  </tr>
  <tr class="head">
    <td><strong>Brief</strong></td>
  </tr>
  <tr>
    <td><textarea name="brief" id="brief" style="width:800px"></textarea></td>
  </tr>
    <tr class="head">
    <td><strong>Detail</strong></td>
  </tr>
  <tr>
    <td><textarea id="teditor" name="detail" style="width:800px;height:400px;"></textarea></td>
  </tr>
  <tr class="head">
    <td><strong>Status</strong></td>
  </tr>
  <tr>
    <td>
		<select name="status" style="width:800px;">
        	<option value="0">Draft</option>
            <option value="1">Publish</option>
        </select>
	</td>
  </tr>
  <tr>
    <td><input type="submit" value=" Save "></td>
  </tr>
  <input type="hidden" name="cmd" value="add" />
  </form>
</table>