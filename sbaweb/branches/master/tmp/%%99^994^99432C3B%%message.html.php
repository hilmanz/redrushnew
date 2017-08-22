<?php /* Smarty version 2.6.13, created on 2012-04-12 07:12:20
         compiled from common/message.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'common/message.html', 23, false),)), $this); ?>
<!-- challenge -->
<script type="text/javascript">
//alert('<?php echo $this->_tpl_vars['msg']; ?>
');
var msg = '<?php echo $this->_tpl_vars['msg']; ?>
';
<?php echo '
if(msg == \'Your code is invalid\' || msg == \'Your code is invalid.\' || msg == \'Submit code failed!\'){
	//alert(\'error\');
	bb1show = 1;
	bb2show = 1;
}else{
	//alert(\'engga\');
'; ?>

	bb1show = <?php if ($this->_tpl_vars['bonus']['bonus1'] == 0): ?>0<?php else: ?>1<?php endif; ?>;
	bb2show = <?php if ($this->_tpl_vars['bonus']['bonus2'] == 0): ?>0<?php else: ?>1<?php endif; ?>;
<?php echo '
}
'; ?>

</script>

<div class="popupShowMsg" align="center" style="padding:100px 0 0 0; min-height:355px;">
    <div class="popupDetails box_round box_shadow" align="center" class="suksesMessage">
      <p>
          <?php echo ((is_array($_tmp=$this->_tpl_vars['msg'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

      </p>
      <p><a class="continue" href="<?php echo $this->_tpl_vars['url']; ?>
">[ Continue ]</a></p>
    </div>
</div>