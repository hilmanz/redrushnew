<?php /* Smarty version 2.6.13, created on 2012-04-12 07:18:30
         compiled from Forum/forum.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'Forum/forum.html', 45, false),array('modifier', 'strip_tags', 'Forum/forum.html', 45, false),array('modifier', 'date_format', 'Forum/forum.html', 46, false),array('modifier', 'intval', 'Forum/forum.html', 47, false),)), $this); ?>
<div class="f_title">
            	<h1>Forum</h1>
</div><!-- end div Title Bar -->
<div class="w970">
	<div class="f_header f_padding">
		<div class="f_topic">
			<a href="?forum=1&act=add"><img src="images/f_new-top.jpg"></a>
		</div>
		<div class="f_paging">
			<?php echo $this->_tpl_vars['paging']; ?>

			<!--
			<strong>1</strong>&nbsp;
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">&gt;&gt;</a>
			-->		
		</div>
	</div>
	<div class="f_header f_padding">
		<div class="f_topic">
			<span style="font-size:18px;">TOPICS</span>
		</div>
		<div class="f_action">
			<a href="#">
			<img src="images/f_last-post.jpg"><span style="display: block;font-size: 14px;font-weight: bold;margin-top: 4px;padding-left: 35px;width: 70px;">Last Post</span>
			</a>
			<a href="#">
			<img src="images/f_replies.jpg"><span style="display: block;font-size: 14px;font-weight: bold;margin-top: 4px;padding-left: 35px;width: 50px;">Replies</span>
			</a>
		</div>
	</div>
</div>
	
<!-- section di mulai dari sini, klo ganjil pake class "bg_e2e2", genap pake class "bg_white" -->
<?php $this->assign('css', 0);  unset($this->_sections['i']);
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
 if ($this->_tpl_vars['css'] > 0):  $this->assign('css', 0);  else:  $this->assign('css', 1);  endif; ?>
<div class="w970 <?php if ($this->_tpl_vars['css'] > 0): ?>bg_e2e2<?php else: ?>bg_white<?php endif; ?>">
	<div class="f_thread f_padding">
	<table>
	<tr>
		<td><div style="padding-right:20px;width:77px;"><a href="index.php?profile=1&profile_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['user_id']; ?>
"><img src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['small_img']; ?>
" width="77px" height="77px" ></a></div></td>
		<td><div style="padding-right:20px"><a href="index.php?forum=1&act=thread&tid=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><img src="images/f_thread.png" width="27px" height="33px" ></a></div></td>
		<td><span style="color: #666666;font-size: 18px;width: 530px;display:block;padding-right:20px"><a href="index.php?forum=1&act=thread&tid=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</a></span></td>
		<td><span style="width:120px;display:block;font-size:12px;color:#666666;"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['posted_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
<br />by <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span></td>
		<td><span style="width:20px;display:block;color: #666666;font-size: 18px;float:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['reply'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
</span></td>
		</tr>
	</table>
	</div>
</div>
<?php endfor; endif; ?>

<div class="w970 bg_white noBorder">
	<div class="f_header f_padding">
		<div class="f_topic">
			
		</div>
		<div class="f_paging">
			<?php echo $this->_tpl_vars['paging']; ?>

			<!--
			<strong>1</strong>&nbsp;
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">&gt;&gt;</a>
			-->
		</div>
	</div>
</div>