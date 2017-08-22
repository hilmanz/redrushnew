<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Social/widgets/berita_detail.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Social/widgets/berita_detail.html', 65, false),array('modifier', 'strip_tags', 'Social/widgets/berita_detail.html', 65, false),array('modifier', 'html_entity_decode', 'Social/widgets/berita_detail.html', 73, false),)), $this); ?>
<script language="javascript" type="text/javascript">
var eventid = <?php echo $this->_tpl_vars['news_id']; ?>
;
var jumlah_comment = <?php echo $this->_tpl_vars['num_com']; ?>
;
<?php echo '
function textCounter( field, countfield, maxlimit ) {
  if ( field.value.length > maxlimit )
  {
    field.value = field.value.substring( 0, maxlimit );
    //alert( \'Textarea value can only be 255 characters in length.\' );
    return false;
  }
  else
  {
    countfield.value = maxlimit - field.value.length;
  }
}

var change_comment = true;
var new_id = 0;
$(document).ready(function(){
	$(\'#btn-comment\').click(function(){
		addComment();
	});
	$(\'#form-comment\').submit(function(){
		addComment();
	});
	$(\'#ocoms\').click(function(){
		$(\'#coms\').toggle();
	});
});

function addComment(){
	if( change_comment ){
		change_comment = false;
		
		$.post(\'?news=1&act=comment&sid=\'+eventid+\'&text=\'+$(\'#comment\').val(), function(data) {
			if( data == 1 ){
				
				
				change_comment = true;
				//alert(\'Your Comment Has Been Sent For Moderation. Thank You For Your Post\');
				window.location.href = "index.php?news=1&id="+eventid;
			}else{
				alert( "Processing comment failed, please try again" );
			}
		});
		
	}else{
		return false;
	}
}

function hideComment(id){
		$.post(\'?events=1&act=hide&sid=\'+id, function(data) {
			if( data == 1 ){
				$(\'#licom-\'+id).fadeOut(1300);	
			}else{
				alert( "Processing hide comment failed, please try again" );
			}
		});
}
</script>
'; ?>
 
				<div style="margin-bottom: 20px;">
					<h1 style="color:#f00;clear:both;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['judul'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</h1>
					<br style="clear:both;"/>
					
                    <div style="text-align:justify;">
                    	<?php if ($this->_tpl_vars['takeOut']): ?>
						<?php if ($this->_tpl_vars['img'] != ''): ?>
						<img src="contents/news/<?php echo $this->_tpl_vars['img']; ?>
" style="float:left;margin-right:10px;margin-bottom:5px;" />
						<?php endif;  endif; ?>
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['isi'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

						<div style="clear:both;"></div>
					</div>
                </div>
				<p style="color:#f00;cursor:pointer;" id="ocoms">Comments (<span id="numer-com"><?php echo $this->_tpl_vars['num_com']; ?>
</span>)</p>
				<div id="coms" style="display:none;">
				<ul id="list-comments" style="padding:0px;margin:0px;list-style-type:none;width:400px">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['com']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<li style="margin-bottom: 20px;" id="licom-<?php echo $this->_tpl_vars['com'][$this->_sections['i']['index']]['id']; ?>
">
						<div>
							<div style="float:left;width:75px;margin-right:20px;">
							
							<?php if ($this->_tpl_vars['com'][$this->_sections['i']['index']]['small_img']): ?><img src="<?php echo $this->_tpl_vars['com'][$this->_sections['i']['index']]['small_img']; ?>
" width="75" heigth="75" /><?php else: ?><img src="images/no_photo_small.gif" width="75" heigth="75" /><?php endif; ?>
							
							</div>
							<div style="float:right;width:305px">
							<a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['com'][$this->_sections['i']['index']]['user_id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['com'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a><br/>
								<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['com'][$this->_sections['i']['index']]['comments'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

								<?php if ($this->_tpl_vars['userid'] == $this->_tpl_vars['event_userid']): ?>
									<?php if ($this->_tpl_vars['com'][$this->_sections['i']['index']]['user_id'] != $this->_tpl_vars['userid']): ?>
									<p><a href="javascript:void(0);" onclick="javascript:hideComment(<?php echo $this->_tpl_vars['com'][$this->_sections['i']['index']]['id']; ?>
);">hide this comment</a></p>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</li>
					<?php endfor; endif; ?>
				</ul>
				</div>
				<div style="width:400px;">
					<p style="color:#f00"><b>Comment</b></p>
					<?php echo '
					<form id="form-comment">
					<textarea id="comment" style="width:400px;height:80px" onkeypress="textCounter(this,this.form.counter,140);"></textarea>
					<div style="width:400px;">
						<div style="float:left;"><input type="button" id="btn-comment" value="comment"  /></div>
						<div style="float:right;"><input type="text" id="counter" style="width:30px" name="counter" maxlength="3" value="140" onblur="textCounter(this.form.counter,this,140);" ></div>
					</div>
					</form>
					'; ?>

				</div>