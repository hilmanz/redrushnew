<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:33
         compiled from Social/widgets/network_updates.html */ ?>
<div class="title">    <h1>Network Updates</h1>    <a href="?updates=1" class="view-all">Semua Update</a></div>	<div class="content"><div id="newsfeeds" class="span-4"></div></div><script>getNews(<?php echo $this->_tpl_vars['user_id']; ?>
);</script>