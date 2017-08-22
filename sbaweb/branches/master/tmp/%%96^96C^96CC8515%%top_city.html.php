<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:28
         compiled from Social/widgets/top_city.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'Social/widgets/top_city.html', 11, false),)), $this); ?>
<div class="title">
            	<h1>Top City</h1>
                <!--<a href="#" class="view-all">Lihat Semua</a>-->
                </div>
                <div class="content">
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
                	<div class="list">
                    	<a href="#" style='float:left;'><img src="images/<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['nama_kota']; ?>
.jpg" /></a>
                        <div class="text" style='float:right;width:135px;'>
                        	<a href="javascript:;" class="title" style="cursor:default;"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['nama_kota']; ?>
</a>
                        	<br/><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['percent'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 / <?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 (<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['percent_txt']; ?>
%)
                        </div>
                    </div>
                    <?php endfor; endif; ?>
                </div>