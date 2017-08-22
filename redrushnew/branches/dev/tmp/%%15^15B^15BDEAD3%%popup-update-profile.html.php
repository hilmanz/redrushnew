<?php /* Smarty version 2.6.13, created on 2012-06-29 20:46:55
         compiled from RedRushWeb/popup-update-profile.html */ ?>


	<div id="popup-update-profile" class="popup">
		<a class="popup-close" href="#">[x] Close</a>
        <div class="inner-popup">
            <div class="logo">
            	<a href="index.php">&nbsp;</a>
            </div><!-- .logo -->
            <form class="content" action="?page=user&act=change_profile" method="POST" id="fprofile">
                <div id="current-photo">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top"><div class="thumb"><img id="thumb_avatar" src="<?php if ($this->_tpl_vars['avatar'] == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['avatar']; ?>
 <?php endif; ?>" /></a></div></td>
                        <td>        
                            <div class="rows">
                                <input id="file_upload" type="file" name="file_upload" > 
                                <div class="small-text">
                                <small>*max.  file size 250kb</small>
                                </div>
                            </div>
                            <div class="rows">
                                <label>Nickname</label>
                                <input type="text" value="<?php echo $this->_tpl_vars['user_name']; ?>
" name="nickname" id="profile_name" autocomplete="off" />
                                <input type="hidden" value="" name="profile_avatar" id="profile_avatar" />
                                <input type="hidden" value="" name="profile_avatar_ext" id="profile_avatar_ext" />
                                <p id="errmsg" style="color:#F00;"></p>
                                <input type="submit" value="Update Profile" class="updateprofilebtn" id="submit_btn" />
                            </div>
                        </td>
                      </tr>
                    </table>
				</div>
            </form>
        </div>
	</div>
	<div class="backgroundPopup"></div>
	<?php echo '
	<style>
	.uploadifyQueueItem{
	background-color: white;
    position: absolute;
	 padding: 0 5px;
	}
	.cancel{    
	float: right;
    position: relative;
    top: 4px;
    width: 31px;}
	</style>
	<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
    <script type="text/javascript">
    // <![CDATA[
    $(document).ready(function() {
	'; ?>

	var userid=<?php echo $this->_tpl_vars['user_id']; ?>
;
	<?php echo '
      $(\'#file_upload\').uploadify({
        \'uploader\'  : \'js/uploadify/uploadify.swf\',
        \'script\'    : \'js/uploadify/uploadify.php?user_id=\'+userid,
        \'cancelImg\' : \'js/uploadify/cancel.png\',
        \'folder\'    : \'contents/avatar/small\',
        \'auto\'      : true,
		\'buttonText\': \'change\',
		\'multi\'     : false,
		\'fileExt\'     : \'*.jpg;*.gif;*.png\',
		\'fileDesc\'    : \'Image Files\',
		\'sizeLimit\'   : 2500000,
		\'removeCompleted\' : false,
		\'onComplete\'  : function(event, ID, fileObj, response, data) {
		$(\'#profile_avatar\').val(fileObj.name);
		$(\'#profile_avatar_ext\').val(fileObj.type);
			$.post(\'?page=user&act=get_profile_pict\', 
			{ ori_avatar: fileObj.name, ori_avatar_ext: fileObj.type},
			function(data) {
			$(\'#thumb_avatar\').attr(\'src\',\'contents/avatar/small/\'+data);
			});
		}
      });
    });
    // ]]>
    </script>
    <script type="text/javascript">
	$(document).ready(function(){
		//$(\'#fprofile\').submit(function(){return false;});
		$(\'#errmsg\').html(\'\');
		$(\'#profile_name\').click(function(){$(\'#errmsg\').html(\'\')});
		$("#profile_name").keypress(function(event) {
		  if ( event.which == 13 ) {
			 return false;
		   }
		});
		$(\'#submit_btn\').click(function(){
			var text = $(\'#profile_name\').val();
			if(text==\'\'){
				$(\'#errmsg\').html(\'Please complete form!\'); 
				return false; 
			}
			$.post(\'index.php?page=user&act=cek_nickname&nickname=\'+text, function(data) {
			  if(data==1){
				  $(\'#errmsg\').html(\'Sorry, the nickname is already taken!\'); 
				  return false; 
			  }
			  if(data==3){
				  $(\'#errmsg\').html(\'Sorry, the nickname is already taken!\'); 
				  return false; 
			  }
			  if(data==2){
				  $(\'#fprofile\').submit();
			  }
			});
			return false;
		});
	});
	</script>
'; ?>