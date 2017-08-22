<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:32
         compiled from Social/my_profile.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'Social/my_profile.html', 2, false),array('modifier', 'stripslashes', 'Social/my_profile.html', 2, false),array('modifier', 'number_format', 'Social/my_profile.html', 18, false),array('modifier', 'intval', 'Social/my_profile.html', 26, false),)), $this); ?>
	<div id="title-bar">
            	<h1><?php if ($this->_tpl_vars['isMySelf']): ?>My Profile<?php else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user']['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp));  endif; ?></h1>
            </div><!-- end div Title Bar -->
        	<div id="col-left">
            	<div class="profile-pic">
                	<img src="<?php if ($this->_tpl_vars['user']['img']):  echo $this->_tpl_vars['user']['img'];  else: ?>images/no_photo_small.gif<?php endif; ?>" width="160" height="160" />
                </div>
                <div class="info-profile">
                    <span class="username"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user']['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</span>					
					
					<?php echo $this->_tpl_vars['last_post']; ?>

					
					<?php if ($this->_tpl_vars['isMySelf'] == 1): ?>
					<span class="notification"><a href="index.php?profile_pic=1">Change Picture</a></span>
					<?php endif; ?>
                    <span class="percent"><?php echo $this->_tpl_vars['ba']['progress']; ?>
% Archivement</span>
                	<span class="ba-level">BA Level : <?php echo $this->_tpl_vars['ba']['level']; ?>
</span>
                    <span class="registrants">Total Registrants : <?php echo ((is_array($_tmp=$this->_tpl_vars['ba']['registrants'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</span>
                    <span class="total-event">Total Events : <?php echo ((is_array($_tmp=$this->_tpl_vars['ba']['events'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</span>
					<?php if ($this->_tpl_vars['isMySelf'] == 1): ?>
                    <span class="notification"><a href="index.php?notification=1">Notifications</a></span>
                    <span class="notification"><a href="index.php?inbox=1">INBOX</a></span>
                   
					<?php else: ?>
						<?php if ($this->_tpl_vars['user']['is_friend'] == 0): ?>
						<span class="add-friend"><a href="index.php?add=1&u=<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['id'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
">Add as a Friend</a></span>
						<?php endif; ?>
						<span class="notification"><a href="index.php?message=1">Send a Message</a></span>
					<?php endif; ?>
					 <span class="notification"><a href="index.php?performance=1&u=<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['id'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
">Performance</a></span>
                </div>
            	<?php echo $this->_tpl_vars['my_network']; ?>

            </div><!-- end div col left -->
            <div id="col-center">
				<?php if ($this->_tpl_vars['onnotif'] != 1): ?>
				<form class="share" method="post" enctype="application/x-www-form-urlencoded">
                	<textarea name="post" id="post" class="text-share"></textarea>
                    <input class="submit-share" type="button" value="SHARE" onclick="post_me(<?php echo $this->_tpl_vars['user_id']; ?>
,'<?php echo $this->_tpl_vars['signed_request']; ?>
');clear_post();return false;"/>
                </form>
            	<div class="title">
					<?php if ($this->_tpl_vars['isMySelf'] == 1): ?>
                    <h1>My Wall</h1>
					<?php else: ?>
					<h1><?php echo $this->_tpl_vars['user']['name']; ?>
 Wall</h1>
					<?php endif; ?>
                </div>
				<?php else: ?>
				<div class="title">
                    <h1>Notifications</h1>
                </div>
				<?php endif; ?>
                <div class="content" id="timeline">
                	<?php echo $this->_tpl_vars['my_wall']; ?>

                </div>
                    <?php if ($this->_tpl_vars['onnotif'] != 1): ?><a href="javascript:void(0);" class="view-all" id="view-all">Semua Update</a><?php endif; ?>
            </div><!-- end div col center -->
        	<div id="col-right">
            	<?php echo $this->_tpl_vars['acara_terkini']; ?>

            	<?php echo $this->_tpl_vars['photo_gallery']; ?>

            	<?php echo $this->_tpl_vars['my_performance']; ?>

            	<?php echo $this->_tpl_vars['banner']; ?>

            </div><!-- end div col right -->
            
            
            <!-- the scripts -->
            <script type="text/javascript">
			var uid = <?php echo $this->_tpl_vars['user_id']; ?>
;
			var sig = '<?php echo $this->_tpl_vars['signed_request']; ?>
';
			//getFeeds(<?php echo $this->_tpl_vars['user_id']; ?>
);
			<?php echo '
			/*$(document).everyTime(5000,function(){
				getFeeds(uid);
				
			});*/
			function likeit(p){
				post_like(uid,p,sig);
			}
			function reply(p,t){
				post_comment(uid,p,t,sig);
			}
			'; ?>

			</script>