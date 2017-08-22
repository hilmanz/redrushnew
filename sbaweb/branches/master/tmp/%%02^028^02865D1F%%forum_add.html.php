<?php /* Smarty version 2.6.13, created on 2012-04-12 07:11:53
         compiled from Forum/forum_add.html */ ?>
<script type="text/javascript" src="admin/jscripts/tiny_mce/tiny_mce.js"></script>
<?php echo '
<script type="text/javascript">
	tinyMCE.init({
		    theme : "advanced",
		    mode : "exact",
			elements : "teditor",
		    plugins : "bbcode,emotions",
		    theme_advanced_buttons1 : "bold,italic,underline,undo,redo,image,removeformat,cleanup,emotions",
		    theme_advanced_buttons2 : "",
		    theme_advanced_buttons3 : "",
		    theme_advanced_toolbar_location : "top",
		    theme_advanced_toolbar_align : "center",
		    theme_advanced_styles : "Code=codeStyle;Quote=quoteStyle",
		    content_css : "css/bbcode.css",
		    entity_encoding : "raw",
		    add_unload_trigger : false,
		    remove_linebreaks : false,
		    inline_styles : false,
		    convert_fonts_to_spans : false
	});
	tinyMCE.execCommand(\'mceToggleEditor\',false,\'content\');
</script>
'; ?>

<div class="f_title">
<h1>Forum: Add Topic</h1>
</div><!-- end div Title Bar -->


	<div class="f_padding">
		<form action="index.php?forum=1&act=add&cmd=save" method="post" name="forum-new" id="forum-new" enctype="multipart/form-data">
		<div style="margin:0px 0px 20px;">
			<b>Title:</b><br />
			<input type="text" style="width:887px;" name="title" maxlength="50" />
		</div>
		<div style="margin:0px 0px 20px;">
			<b>Upload image:</b>&nbsp;&nbsp;
			<input type="file" name="img" id="img" />
		</div>
		<div>
			<textarea id="teditor" name="content" style="width:890px; height:200px;resize:none;"></textarea>
		</div>
		<br /><br />
			<!--<a href="#"><img src="images/f_reply-button.jpg"></a>-->
			<input type="submit" value="" style="border:none;background:url('images/f_reply-button.jpg') no-repeat left top;width:108px;height:34px;cursor:pointer;" />
		</form>
	</div>