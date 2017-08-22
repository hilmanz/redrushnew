<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:33
         compiled from Social/widgets/berita_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Social/widgets/berita_list.html', 42, false),array('modifier', 'strip_tags', 'Social/widgets/berita_list.html', 42, false),)), $this); ?>
				<div class="title">
                    <h1>News update</h1>
                </div>
                

<?php echo '
<script type="text/javascript">
$(document).ready(function(){
	$(\'.suka\').click(function(){
		var pro = $(this).attr(\'pro\');
		var ids = $(this).attr(\'id\');
		var id = ids.split(\'-\');
		id = id[1];
		
		if( pro == \'like\' ){		
			$.post(\'?news=1&act=like&sid=\'+id, function(data) {
				if( data == 1 ){
					$(\'#\'+ids).attr( "value", " Unlike " );
					$(\'#\'+ids).attr( "pro", "unlike" );
				}else{
					alert( "Processing like failed, please try again" );
				}
			});
		}else{
			$.post(\'?news=1&act=unlike&sid=\'+id, function(data) {
				if( data == 1 ){
					$(\'#\'+ids).attr( "value", " Like " );
					$(\'#\'+ids).attr( "pro", "like" );
				}else{
					alert( "Processing unlike failed, please try again" );
				}
			});
		}
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
<div style="clear:both;margin-bottom:20px;width:400px;">
	<a href="index.php?news=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><h3><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</h3></a>
    <div>
		<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['img'] != ''): ?>
        <div style="float:left;width:110px;">
			<img src="contents/news/<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['img']; ?>
" />
		</div>
		<div style="float:right;width:280px;text-align:justify;">
			<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</div>
			<div style="margin-top:10px;width:280px;">
				<a href="index.php?news=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">comment (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['comments']; ?>
)</a>&nbsp;
				<a href="javascript:void(0);" id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="suka" pro="like"><?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']): ?>like (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']; ?>
)<?php else: ?>like<?php endif; ?></a>
				<!--
				<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka'] == 0): ?>
				<input type="button" style="display:inline;" value=" Like " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="like" />
				<?php else: ?>
				<input type="button" style="display:inline;" value=" Unlike " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="unlike" />
				<?php endif; ?>
				-->
			</div>
		</div>
		<?php else: ?>
		<div style="float:right;width:400px;text-align:justify;">
			<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['brief'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</div>
			<div style="margin-top:10px;width:400px;">
				<a href="index.php?news=1&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">comment (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['comments']; ?>
)</a>&nbsp;
				<a href="javascript:void(0);" id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="suka" pro="like"><?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']): ?>like (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['suka']; ?>
)<?php else: ?>like<?php endif; ?></a>
				<!--
				<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['suka'] == 0): ?>
				<input type="button" style="display:inline;" value=" Like " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="like" />
				<?php else: ?>
				<input type="button" style="display:inline;" value=" Unlike " id="like-<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
" class="like" pro="unlike" />
				<?php endif; ?>
				-->
			</div>
		</div>
		<?php endif; ?>
		<div style="clear:both;"></div>
    </div>
	<div style="clear:both;"></div>
</div>
<?php endfor; endif; ?>
<p><?php echo $this->_tpl_vars['paging']; ?>
</p>