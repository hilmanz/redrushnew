<?php /* Smarty version 2.6.13, created on 2012-06-29 20:46:55
         compiled from RedRushWeb/header.html */ ?>
<meta http-equiv="refresh" content="900;url=logout.php">	
	<div id="header" align="center" >
        <div id="main-menu" class="verified">
        	
				<a href="?page=home&act=main" class="logo-head">&nbsp;</a>
        	<ul>
            	<li class="m1"><a href="?page=about" <?php if ($this->_tpl_vars['page'] == 'about'): ?>class="current"<?php endif; ?>>About Redrush</a></li>
            	<li class="m2"><a href="?page=news" <?php if ($this->_tpl_vars['page'] == 'newsfeed'): ?>class="current"<?php endif; ?>>Newsfeed</a></li>
            	<li class="m3"><a href="?page=garage&act=loading" <?php if ($this->_tpl_vars['page'] == 'garage'): ?>class="current"<?php endif; ?>>My Garage</a></li>
            	<li class="m4a"><a href="?page=race" <?php if ($this->_tpl_vars['page'] == 'race'): ?>class="current"<?php endif; ?>>Race </a></li>
            	<li class="m4"><a href="?page=getpoints" <?php if ($this->_tpl_vars['page'] == 'getpoints'): ?>class="current"<?php endif; ?>>Get Points</a></li>
            	<li class="m5"><a href="?page=merchandise" <?php if ($this->_tpl_vars['page'] == 'merchandise'): ?>class="current"<?php endif; ?>>Redeem Merchandise</a></li>
            	<li class="m6"><a href="?page=topuser" <?php if ($this->_tpl_vars['page'] == 'topuser'): ?>class="current"<?php endif; ?>>Top Users</a></li>
            </ul>
        </div> 
    	<!--#main-menu -->
        <div id="navigation">
        	<div class="wrapper">
            	<div id="user-info">
                	<h1>Welcome <?php echo $this->_tpl_vars['user_name']; ?>
, <span style="color:yellow"> <?php echo $this->_tpl_vars['verified']; ?>
 </span></h1>
                </div>
                <ul id="menu" class="menu main-nav" >
                    <li>
                    	<form action="?page=user&act=find_player" method="post"> 
                        <input type="text"  name="search" id='txtsearchplayer' />
                        <input type="submit" class="find" value="Find User" autocomplete="off" onClick="javascript:if(document.getElementById('txtsearchplayer').value!='')return true; return false"/>
                    	</form>
                    </li>
                    <li><a href="?page=user&act=promo_ref&id=<?php echo $this->_tpl_vars['register_id']; ?>
&type=refer_friend">Refer a Friend</a></li>
                    <li><a href="https://login.marlboro.co.id/Templates/updateprofilestart.aspx">Update Profile</a></li>
                    <li><a href="?page=user&act=notification" style="width:85px; text-align:center;">Inbox <span class="count-message"></span></a></li>
                    <li><a href="logout.php">Logout</a></li>
          		</ul>
              <script type="text/javascript">var menu=new menu.dd("menu");menu.init("menu","menuhover");</script> 
           </div>
        </div><!-- #navigation -->
	</div> <!-- #header -->
    
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-update-profile.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>