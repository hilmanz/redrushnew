<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:32
         compiled from Social/widgets/my_network.html */ ?>
<div class="title">
            		<h1><?php echo $this->_tpl_vars['name']; ?>
 Network</h1>
                </div>
                <div class="my-network">
                	<div class="tab">	
                   	 <a class="tab-button active" href="index.php?friends=1&user=<?php echo $this->_tpl_vars['sid']; ?>
">Friends</a>
                   	 <!--<a class="tab-button" href="#">Ontourage</a> -->
                    </div>
                </div>
                <div class="content">
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['friends']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    	<a href="?profile=1&profile_id=<?php echo $this->_tpl_vars['friends'][$this->_sections['i']['index']]['id']; ?>
"><img src="<?php echo $this->_tpl_vars['friends'][$this->_sections['i']['index']]['small_img']; ?>
"/></a>
                        <div class="text">
                        	<a href="?profile=1&profile_id=<?php echo $this->_tpl_vars['friends'][$this->_sections['i']['index']]['id']; ?>
" class="username"><?php echo $this->_tpl_vars['friends'][$this->_sections['i']['index']]['name']; ?>
</a>
                        </div>
                    </div>
                <?php endfor; endif; ?>
                </div>