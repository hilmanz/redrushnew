<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:32
         compiled from Social/widgets/my_wall.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'Social/widgets/my_wall.html', 6, false),array('modifier', 'stripslashes', 'Social/widgets/my_wall.html', 7, false),)), $this); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['feeds']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<div class="list wall">
	<a class="small-thumb" href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['user_id']; ?>
">
	<?php if ($this->_tpl_vars['feeds'][$this->_sections['i']['index']]['small_img']): ?><img src="contents/images/tiny_<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['user_id']; ?>
.jpg"/><?php else: ?><img src="images/no_photo_small.gif"/><?php endif; ?></a>
    <div class="text">
        <span class="post-comment"><a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['user_id']; ?>
" class="username"><?php echo ((is_array($_tmp=$this->_tpl_vars['feeds'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a>
        <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

         </span>
        <p class="comment-act">
        <span class="time inline"><?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_time']; ?>
</span> - <a class="likethiscomment" href="javascript:;" id="like<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" onclick="likeit(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
)">Like <?php if ($this->_tpl_vars['feeds'][$this->_sections['i']['index']]['like'] > 0): ?>(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['like']; ?>
)<?php endif; ?></a> - <a class="comment-link" href="javascript:;" onclick="comment_box(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
);return false;">Comment</a> - <span id="replies<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" class="span-11 prepend-1 last box" style="display:none;"><a href="javascript:;" onclick="view_comment(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
);return false;">View Comments (<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['reply']; ?>
)</a></span>
        
        </p>
       
    </div>
	<div id="comment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" style="display:none;"><input type="text" name="txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" id="txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" size="40" value=""/></div>
	<span id="rs<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
" class="info" style="display:none;">saving comment ...</span>
	
	<script>
	$('#txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
').click(function()<?php echo '{
		'; ?>

			var el = 'txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
';
			<?php echo '
			$(\'#\'+el).val("");
			
	});'; ?>

	$('#txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
').keypress(function(event)<?php echo '{
		'; ?>

			var el = 'txtcomment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
';
			var el1 = 'comment<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
';
			var el2 = 'rs<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
';
			var pid = <?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
;
			<?php echo '
		if(event.which==13){
			//enter
			$(\'#\'+el1).hide();
			$(\'#\'+el2).show();
			reply(pid,$(\'#\'+el).val());
		}
	});'; ?>

	</script>
	<script>
	has_comment(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['post_id']; ?>
);
	</script>
</div>
<?php endfor; endif; ?>