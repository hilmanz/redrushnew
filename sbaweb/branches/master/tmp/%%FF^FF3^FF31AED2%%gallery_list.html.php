<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Social/widgets/gallery_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'md5', 'Social/widgets/gallery_list.html', 9, false),)), $this); ?>
<div class="title">
            	<h1>Photo Gallery</h1>
                <a href="?gallery=1" class="view-all">Lihat Semua</a>
                </div>
                <div class="content">
                	<div class="gallery-small-2" style="text-align: center;">
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
                	
                		<a href="?view=gallery&album=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['album_id']; ?>
"><img src="contents/gallery/<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['owner_id'])) ? $this->_run_mod_handler('md5', true, $_tmp) : md5($_tmp)); ?>
/small_<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['img']; ?>
" /></a>
                		
                	<?php endfor; endif; ?>
                    </div>
                </div>