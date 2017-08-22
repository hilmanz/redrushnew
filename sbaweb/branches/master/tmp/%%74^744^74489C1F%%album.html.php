<?php /* Smarty version 2.6.13, created on 2012-04-12 07:18:31
         compiled from Gallery/album.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'md5', 'Gallery/album.html', 4, false),array('modifier', 'stripslashes', 'Gallery/album.html', 5, false),array('modifier', 'strip_tags', 'Gallery/album.html', 5, false),array('modifier', 'lower', 'Gallery/album.html', 6, false),array('modifier', 'ucwords', 'Gallery/album.html', 6, false),)), $this); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['show_albumgallery']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<div align="center" style="text-align:left;class="blockContent">
	<div class="galery-list">
		<a href="?view=gallery&owner_id=<?php echo $this->_tpl_vars['show_albumgallery'][$this->_sections['i']['index']]['owner_id']; ?>
&album=<?php echo $this->_tpl_vars['show_albumgallery'][$this->_sections['i']['index']]['id']; ?>
"><img src="contents/gallery/<?php echo ((is_array($_tmp=$this->_tpl_vars['show_albumgallery'][$this->_sections['i']['index']]['owner_id'])) ? $this->_run_mod_handler('md5', true, $_tmp) : md5($_tmp)); ?>
/<?php echo $this->_tpl_vars['show_albumgallery'][$this->_sections['i']['index']]['thumb']; ?>
"></a>
    	<span class="desc"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['show_albumgallery'][$this->_sections['i']['index']]['album_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span>
    	<span class="posted"> Posted By <br><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['posted_by'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('ucwords', true, $_tmp) : ucwords($_tmp)); ?>
</span>
	</div>
</div>
<?php endfor; endif; ?>

<div id="paging" class="blockContent" >
	<?php echo $this->_tpl_vars['paging']; ?>

</div>
<br>
<span class="insert">
	<a href="?gallery=1&insertAlbum=1">Insert Album</a>
</span>

<span class="insert">
	<a href="?gallery=1&myalbum=1">My Album</a>
</span>