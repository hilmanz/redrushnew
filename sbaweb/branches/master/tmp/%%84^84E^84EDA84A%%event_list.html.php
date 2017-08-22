<?php /* Smarty version 2.6.13, created on 2012-04-12 07:18:32
         compiled from Social/widgets/event_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Social/widgets/event_list.html', 47, false),array('modifier', 'strip_tags', 'Social/widgets/event_list.html', 47, false),array('modifier', 'date_format', 'Social/widgets/event_list.html', 60, false),)), $this); ?>
<?php echo '
<script type="text/javascript">
$(document).ready(function(){
	$(\'.suka\').click(function(){
		var pro = $(this).attr(\'pro\');
		var ids = $(this).attr(\'id\');
		var id = ids.split(\'-\');
		id = id[1];
		
		if( pro == \'like\' ){		
			$.post(\'?events=1&act=like&sid=\'+id, function(data) {
				if( data == 0 ){
					//$(\'#\'+ids).removeClass("like");
					//$(\'#\'+ids).addClass("unlike");
					//$(\'#\'+ids).attr( "value", " Unlike " );
					//$(\'#\'+ids).attr( "pro", "unlike" );
					alert( "Processing like failed, please try again" );
				}else if( data == 2 ){
					alert( "You\'re already like this event" );
				}else{
					$(\'#\'+ids).html("like ("+data+")");
				}
			});
		}else{
			$.post(\'?events=1&act=unlike&sid=\'+id, function(data) {
				if( data == 1 ){
					//$(\'#\'+ids).removeClass("unlike");
					//$(\'#\'+ids).addClass("like");
					$(\'#\'+ids).attr( "value", " Like " );
					$(\'#\'+ids).attr( "pro", "like" );
				}else{
					alert( "Processing unlike failed, please try again" );
				}
			});
		}
	});
	
	$(\'#createNewEvent\').click(function(){
		window.location.href = \'?events=1&act=add\';
	});
});
</script>
'; ?>


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
<div style="clear:both;margin-bottom:20px;width:410px;">
	<a href="index.php?events=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><h3><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['nama_event'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</h3></a>
    <div>
        <div style="float:left;width:110px;">
			<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['image_ext'] == ''): ?>
			<img src="contents/events/nothumb.jpg" width="110" height="120" />
			<?php else: ?>
			<img src="contents/events/<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id'];  echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['image_ext']; ?>
" width="110" height="120" />
			<?php endif; ?>
		</div>
		<div style="float:right;width:280px;text-align:justify;">
			<div>
            	<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['summary'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

                <p style="font-size:11px;">
                	Time: <?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['tanggal_event'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
 - <?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['tanggal_event'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M:%S") : smarty_modifier_date_format($_tmp, "%H:%M:%S")); ?>

                    <br />
                    Posted By: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

                </p>
            </div>
			<div style="margin-top:10px">
				<a href="index.php?events=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">comment (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['comments']; ?>
)</a>&nbsp;&nbsp;<a href="javascript:void(0);" id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="suka" pro="like"><?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']): ?>like (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']; ?>
)<?php else: ?>like<?php endif; ?></a>
				<!--
				<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka'] == 0): ?>
				<input type="button" value=" Like " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="like" style="display:inline;" />
				<?php else: ?>
				<input type="button" value=" Unlike " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="unlike" style="display:inline;" />
				<?php endif; ?>
				-->
			</div>
		</div>
		<div style="clear:both;"></div>
    </div>
	<div style="clear:both;"></div>
</div>
<?php endfor; endif; ?>
<p><?php echo $this->_tpl_vars['paging']; ?>
</p>
<br />
<?php if ($this->_tpl_vars['user_ba'] == 1): ?>
<span class="insert"><a href="index.php?events=1&act=add">Create Event</a></span>
<?php endif; ?>