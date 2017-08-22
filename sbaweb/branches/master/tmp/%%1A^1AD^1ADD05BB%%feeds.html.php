<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:32
         compiled from Social/feeds.html */ ?>
<!-- feed bar -->

			<div>
			<h2>What's New ?</h2>
			<hr style="border"/>
			<form method="post" enctype="application/x-www-form-urlencoded" onsubmit="post_me(<?php echo $this->_tpl_vars['user_id']; ?>
,'<?php echo $this->_tpl_vars['signed_request']; ?>
');return false;">
			<input type="text" name="post" id="post" value="What's in your mind now ?" class="span-12" style="height:40px;" onclick="clear_post();return false;"/>
			</form>
			</div>
			<div id="timeline">
			
			</div>
			<!-- Button More -->
			<input type="button" value="MORE" class="span-12 box"></input>
			<script>
			var uid = <?php echo $this->_tpl_vars['user_id']; ?>
;
			var sig = '<?php echo $this->_tpl_vars['signed_request']; ?>
';
			getFeeds(<?php echo $this->_tpl_vars['user_id']; ?>
);
			<?php echo '
			$(document).everyTime(5000,function(){
				
				getFeeds(uid);
				
			});
			function likeit(p){
				post_like(uid,p,sig);
			}
			function reply(p,t){
				post_comment(uid,p,t,sig);
			}
			'; ?>

			</script>