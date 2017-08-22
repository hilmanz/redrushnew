<?php /* Smarty version 2.6.13, created on 2012-04-13 10:41:59
         compiled from common/admin/login.html */ ?>

<div id="login">
  <form name="form1" method="post" action="login.php">
    <input type="hidden" name="PHPSESSID" value="85a1fe34897ffd340ee39272d8a03b8c" />
      <input name="username" type="text" id="username" maxlength="20"> 
      <input name="password" type="password" id="password" maxlength="20" />
      
      <input name="f" type="hidden" id="f" value="1">
      <input id="button" type="submit" name="Submit" value="&nbsp;" />
      <div class="space5"></div>
	<?php if ($this->_tpl_vars['msg'] <> ""): ?>
      <span class="messageLogin"> <?php echo $this->_tpl_vars['msg']; ?>
 </span>
  	<?php endif; ?>
  </form>
</div>