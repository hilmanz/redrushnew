<?php /* Smarty version 2.6.13, created on 2012-05-16 16:22:58
         compiled from RedRushWeb//mygarage.html */ ?>
<?php echo '
<style>
.partbar{visibility: hidden}
.confirm-buy{z-index:1000}
</style>
<script type="text/javascript">
$(document).ready(function(){
		$(\'#close\').click(function(){
			$(\'#popup-info\').hide();	
		});
		$(\'#agree\').change(function(){
		if($(\'#agree\').is(\':checked\')){
			/*alert(\'checked\');	*/
			$.post("?page=ajax&act=cg001");
		}
		});
});
</script>
'; ?>

<div id="main-container" class="garage-bg">
	<div class="wrapper">
    	<div id="containers">
        	<div id="garage">
            <?php if ($this->_tpl_vars['popup_notif']): ?>
            	<div id="popup-info">
                	<div class="content">
                        <h1>BUILD YOUR ULTIMATE RIDE</h1>
                        <h2>Exchange your Rush points for performance upgrade parts before you get in to the tracks. Win races and collect even more Rush Points!</h2>
                        <h2>Check out also items that upgrades the looks of your ride and stand out from the rest!</h2>
                        <div class="thick">
                        <input type="checkbox" id="agree" />
                        <label>Dont Show this message again</label>
                        <input type="button" value="CLOSE" id="close" />
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            	<div id="hint-container" style="display:none;">
                	<div id="hint-content">
                    	<h1>RED RUSH HINTS</h1>
                        <a class="close-hint" href="#">[X] CLOSE</a>
                        <div class="hint1">
                        	<p>Visit your garage to check out some free aftermarket parts and 
customizations. Make your car stand out from the crowd.</p>
                        </div>
                        <div class="hint2">
                        	<p>Exchange your Rush Points to get some premium performance upgrades in your garage before racing</p>
                        </div>
                        <div class="hint3">
                        	<p>Go to 'RACE' section to challenge other racers and show them what you got!</p>
                        </div>
                        <div class="hint4">
                        	<p>Get even more Rush Points by winning all the challenges in the 'GET POINTS' section. Complete all the mind-sharpening puzzles and other challenges. Also, don't miss all the point-worthy RedRush activities if you're aiming for more Rush Points.</p>
                        </div>
                        <div class="hint5">
                        	<p>You can redeem your Rush Points for exclusive authentic merchandises. </p>
                        </div>
                        <div class="hint6">
                        	<p>Keep an eye for updates in the 'NEWSFEED' section. Get to know more about what RedRush has to offer. </p>
                        </div>
                        <a href="#" id="nav_up">Back to Top</a>
                        <a href="#" id="close-hint">[X] CLOSE</a>
                    </div>
                </div>    
            	<div id="profile">
                	<div class="thumb"><a href="index.php?page=garage"><img src="<?php if ($this->_tpl_vars['small_img'] == ''): ?> img/thumb.jpg <?php else: ?> contents/avatar/small/<?php echo $this->_tpl_vars['small_img']; ?>
 <?php endif; ?>" /></a></div>
                    <div class="box-user">
                    	<span class="username"><?php echo $this->_tpl_vars['name']; ?>
</span>
					
                    	<span class="reputation"><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><img src="img/star.png" alt="" /><?php endfor; endif; ?></span>
                    	<span class="total-race"><?php echo $this->_tpl_vars['races']; ?>
 Races</span>
                    	<span class="total-win"><?php echo $this->_tpl_vars['wins']; ?>
 Wins</span>
                    </div>
                    <a href="#" id="btn-edit-profile" class="edit-profile">Edit Profile</a>
                </div><!-- #profile -->
            	<div id="score">
                	<h1 class="player-points" points="<?php echo $this->_tpl_vars['points']; ?>
"><?php echo $this->_tpl_vars['points']; ?>
 PTS.</h1>
                </div><!-- #score -->
                <div id="sparepart">
                      <ul class="itemsparepart">
                        <li><a href="#popup-visual"><img src="img/sparepart/visual.png" alt="" /></a></li>
                        <li><a href="#popup-wheel"><img src="img/sparepart/wheels.png" alt="" /></a></li>
                        <li><a href="#popup-kit"><img src="img/sparepart/body-kit.png" alt="" /></a></li>
                        <li><a href="#popup-suspension"><img src="img/sparepart/suspension.png" alt="" /></a></li>
                        <li><a href="#popup-engine"><img src="img/sparepart/engine.png" alt="" /></a></li>
                      </ul>
					  
                </div><!-- #sparepart -->
			
    		        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-visual.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    		        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-wheel.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    		        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-kit.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    		        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-suspension.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    		        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RedRushWeb/popup-engine.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
               	<div id="car">
                <?php echo '
                        <noscript><h1 style="font-size:36px; color:#FFF; position:absolute; top:40%; left:10%;">Please Enable Your Javascript!</h1></noscript>
                    '; ?>

                	<div class="rotate-car" style="display:none;">
                                <div class="colorList"><button class="carView rotateLeft leftArrow" value="carLeft" style="display:none;"></button></div>
                                <div class="colorList"><button class="carView rotateLeft rightArrow" value="carRight" ></button></div>
                    </div>
                	<img id="badan" src="contents/car/body/red.png" />
					<img id="tints" class="tintscarLeft" src="contents/car/tints/default.png" />
					<img id="wings" class="wingscarLeft" src="" />
					<img id="tire" class="tirecarLeft" src="contents/car/tire/default.png" />
					<img id="decals" class="decalscarLeft" src="" />
					<img id="hoods" class="hoodscarLeft" src="" />
                    <div class="carColor">
                                           </div>
                </div><!-- #car -->
                <div id="barcode">
                	<img src="<?php if ($this->_tpl_vars['qr_img'] == ''): ?>img/barcode.jpg<?php else: ?>contents/qr_code/<?php echo $this->_tpl_vars['qr_img'];  endif; ?>" />
					<span><?php echo $this->_tpl_vars['qr_code']; ?>
</span>
                </div><!-- #barcode -->
                <div id="statistik">
                	<div id="topspeed" class="gradient">
                    	<span>TOP SPEED</span>
                        <div class="progress"><img src="img/progress-bar-red.bmp" width="0%" height="10" id="top_speed_bar" /><img src="img/progress-bar.gif" width="<?php echo $this->_tpl_vars['attributes']->speed; ?>
%" height="10" /></div>
                  </div>
                	<div id="handling" class="gradient">
                    	<span>HANDLING</span>
                        <div class="progress"><img src="img/progress-bar-red.bmp" width="0%" height="10" id="handling_bar"  /><img src="img/progress-bar.gif" width="<?php echo $this->_tpl_vars['attributes']->handling; ?>
%" height="10" /></div>
                    </div>
                	<div id="accleration" class="gradient">
                    	<span>ACCELERATION</span>
                        <div class="progress"><img src="img/progress-bar-red.bmp" width="0%" height="10"  id="acceleration_bar" /><img src="img/progress-bar.gif" width="<?php echo $this->_tpl_vars['attributes']->acceleration; ?>
%" height="10" /></div>
                    </div>
                </div><!-- #statistik -->
                <div id="button-race">
                	<a href="index.php?page=race" class="race-now">&nbsp;</a>
                	<a href="#hint-container" class="hint">&nbsp;</a>
                </div><!-- #button-race -->
            </div><!-- #garage -->
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->
<form action="?page=garage&act=saveCar" method="POST" id="saveCar">
<input type="hidden" name="bodyType" id="bodyType" value="<?php echo $this->_tpl_vars['body']['type']; ?>
">
<input type="hidden" name="bodyColor" id="bodyColor" value="<?php echo $this->_tpl_vars['body']['color']; ?>
">
<input type="hidden" name="tireType" id="tireType" value="<?php echo $this->_tpl_vars['tire']['type']; ?>
">
<input type="hidden" name="tireColor" id="tireColor" value="<?php echo $this->_tpl_vars['tire']['color']; ?>
">

<input type="hidden" name="tintsType" id="tintsType" value="<?php echo $this->_tpl_vars['tints']['type']; ?>
">
<input type="hidden" name="tintsColor" id="tintsColor" value="<?php echo $this->_tpl_vars['tints']['color']; ?>
">

<input type="hidden" name="wingsType" id="wingsType" value="<?php echo $this->_tpl_vars['wings']['type']; ?>
">
<input type="hidden" name="wingsColor" id="wingsColor" value="<?php echo $this->_tpl_vars['wings']['color']; ?>
">
<input type="hidden" name="decalsType" id="decalsType" value="<?php echo $this->_tpl_vars['decals']['type']; ?>
">
<input type="hidden" name="decalsColor" id="decalsColor" value="<?php echo $this->_tpl_vars['decals']['color']; ?>
">
<input type="hidden" name="hoodsType" id="hoodsType" value="<?php echo $this->_tpl_vars['hoods']['type']; ?>
">
<input type="hidden" name="hoodsColor" id="hoodsColor" value="<?php echo $this->_tpl_vars['hoods']['color']; ?>
">

<input type="hidden" name="userID" id="userID" value="<?php echo $this->_tpl_vars['user_id']; ?>
">
<input type="hidden" name="carLayer" id="carLayer" value="carLeft">
</form>


<?php echo '
<script>
$(document).ready(function() {
	$(document).keydown(function(event) {
		  if ( event.ctrlKey==true || event.keyCode == 123) {
			 return false;
		   }
		});
$(\'.partbar\').attr(\'style\',\'visibility: visible\');		

$("#car").mouseover(function(){$(".rotate-car").show()});
$("#car").mouseout(function(){$(".rotate-car").hide()});

$(\'.leftArrow\').click(function(){
$(\'.leftArrow\').hide();
$(\'.rightArrow\').show();
});
$(\'.rightArrow\').click(function(){
$(\'.rightArrow\').hide();
$(\'.leftArrow\').show();});

jQuery("#sparepart a").click(function(){
	var targetID = jQuery(this).attr(\'href\');
	
	jQuery(".itemsparepart").hide();
	jQuery(targetID).animate({top: "42px"});
	
	return false;
});
						   
jQuery(".popup-close").click(function(){
	var targetID = jQuery(this).attr(\'href\');
	
	jQuery(".itemsparepart").fadeIn();
	jQuery(targetID).animate({top: "-100px"});
	
	return false;
});
var userID = $("#userID").val();
var carLayer = $("#carLayer").val();
'; ?>

var carBody = "<?php if ($this->_tpl_vars['body']['type'] == ''): ?>porche<?php else:  echo $this->_tpl_vars['body']['type'];  endif; ?>";
var bodyColor = "<?php if ($this->_tpl_vars['body']['color'] == ''): ?>default<?php else:  echo $this->_tpl_vars['body']['color'];  endif; ?>";
var tireColor = "<?php if ($this->_tpl_vars['tire']['color'] == ''): ?>default<?php else:  echo $this->_tpl_vars['tire']['color'];  endif; ?>";
var decalsColor = "<?php if ($this->_tpl_vars['decals']['color'] == 'default'):  else:  echo $this->_tpl_vars['decals']['color'];  endif; ?>";
var tintsColor = "<?php if ($this->_tpl_vars['tints']['color'] == 'default'):  else:  echo $this->_tpl_vars['tints']['color'];  endif; ?>";
var wingsColor = "<?php if ($this->_tpl_vars['wings']['color'] == 'default'):  else:  echo $this->_tpl_vars['wings']['color'];  endif; ?>";
var hoodsColor = "<?php if ($this->_tpl_vars['hoods']['color'] == 'default'):  else:  echo $this->_tpl_vars['hoods']['color'];  endif; ?>";
var handling = <?php echo $this->_tpl_vars['attributes']->handling; ?>
;
var top_speed = <?php echo $this->_tpl_vars['attributes']->speed; ?>
;
var acceleration = <?php echo $this->_tpl_vars['attributes']->acceleration; ?>
;
<?php echo '

$(\'#car img#badan\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/body/\'+bodyColor+\'.png\');
$(\'#car img#tire\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tire/\'+tireColor+\'.png\');
$(\'#car img#decals\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/decals/\'+decalsColor+\'.png\');
$(\'#car img#tints\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tints/\'+tintsColor+\'.png\');
$(\'#car img#wings\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/wings/\'+wingsColor+\'.png\');	
$(\'#car img#hoods\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/hoods/\'+hoodsColor+\'.png\');	

$(".carView").click(function(){

	$("#carLayer").val($(this).attr(\'value\'));
	carLayer = $("#carLayer").val();
	 
	$(\'#car img#badan\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/body/\'+bodyColor+\'.png\');
	$(\'#car img#tire\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tire/\'+tireColor+\'.png\');
	$(\'#car img#decals\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/decals/\'+decalsColor+\'.png\');
	$(\'#car img#tints\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tints/\'+tintsColor+\'.png\');	
	$(\'#car img#wings\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/wings/\'+wingsColor+\'.png\');	
	$(\'#car img#hoods\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/hoods/\'+hoodsColor+\'.png\');	
	
	$(\'#car img#tire\').removeClass();
	$(\'#car img#tire\').addClass(\'tire\'+$(this).attr(\'value\'));

	
});



$("a").click(function(){
if($(this).attr("category")){
var name=$(this).attr("category").split(\'_\');
//send value to form
$("#"+name[0]).val(name[1]);




if(name[0]==\'bodyColor\'){
bodyColor  = name[1];
$(\'#car img#badan\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/body/\'+bodyColor+\'.png\')

}
if(name[0]==\'tireColor\'){
tireColor  = name[1];

$(\'#car img#tire\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tire/\'+tireColor+\'.png\')

}
if(name[0]==\'decalsColor\'){
decalsColor  = name[1];

$(\'#car img#decals\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/decals/\'+decalsColor+\'.png\')

}

if(name[0]==\'tintsColor\'){
tintsColor  = name[1];

$(\'#car img#tints\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tints/\'+tintsColor+\'.png\')

}

if(name[0]==\'wingsColor\'){
wingsColor  = name[1];

$(\'#car img#wings\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/wings/\'+wingsColor+\'.png\')

}

if(name[0]==\'hoodsColor\'){
hoodsColor  = name[1];

$(\'#car img#hoods\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/hoods/\'+hoodsColor+\'.png\')

}
/*
if(name[0]==\'bodyType\'){
carBody = name[1];
	 
	$(\'#car img#body\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/body/\'+bodyColor+\'.png\');
	$(\'#car img#tire\').attr(\'src\',\'contents/\'+carLayer+\'/\'+carBody+\'/tire/\'+tireColor+\'.png\');

}*/
$.post(\'?page=garage&act=saveCar\', { 
userID 		: userID,
carLayer 	: carLayer,
carBody  	: carBody,
bodyColor	: bodyColor,
tireColor 	: tireColor,
decalsColor	: decalsColor,
tintsColor	: tintsColor,
wingsColor	: wingsColor,
hoodsColor	: hoodsColor,
items : $(this).attr("category")
});
}

});
var points_part = 0;
var points = parseInt($(\'.player-points\').attr(\'points\'));
$(\'.purchasePartFirst\').click(function(){
var typepart = $(this).attr(\'type-part\');
points_part = parseInt($(this).attr(\'part_point\'));
$(\'.\'+typepart).show();
$(\'.purchasePart\').attr(\'part_id\',$(this).attr(\'part_id\'));
$(\'.purchase_img\').attr(\'src\',$(this).attr(\'img_path\'));
$(\'.purchase_point\').html(points_part +\' PTS.\');
$(\'.purchasePart\').addClass(\'purchase\');
$(\'.purchasePart\').html(\'Yes\');


//add sum progress bar
$.post(\'?page=ajax&act=addProgressBar\', { part_id: $(this).attr(\'part_id\') },
			function(data) {
		
			$(\'#top_speed_bar\').width((top_speed+(data.speed/36*100))+\'%\');
			$(\'#handling_bar\').width((handling+(data.handling/36*100))+\'%\');
			$(\'#acceleration_bar\').width((acceleration+(data.acceleration/36*100))+\'%\');
	});



});

$(\'.purchasePart\').click(function(){
var part_id = $(this).attr(\'part_id\');
$.post(\'?page=garage&act=purchasePart\', { part_id: part_id },
			function(data) {
			if(points>=points_part) {
			points = points - points_part;
			$(\'.\'+part_id+\'_purchase\').removeClass(\'buy-item\');
			$(\'.purchasePart\').removeClass(\'purchase\');
			$(\'.purchasePart\').html(\'\');
			$(\'.\'+part_id+\'_class\').html(\'PURCHASED\');
			
			$(\'.player-points\').html(points+\' Pts.\');
			}
			$(\'.purchase_point\').html(data);
	});

//$(\'#purchasePart\').submit();
});


$(\'.cancel-purchase\').click(function(){
	$(".backgroundPopup").fadeOut("slow");
	$(".confirm-buy").fadeOut("slow");
	popupStatus = 0;
			$(\'#top_speed_bar\').width(\'0%\');
			$(\'#handling_bar\').width(\'0%\');
			$(\'#acceleration_bar\').width(\'0%\');
});

});

</script>
<script src="js/scroll-startstop.events.jquery.js" type="text/javascript"></script>
<script>
	$(function() {
		$(\'#nav_up\').click(
			function (e) {
				$(\'html, body\').animate({scrollTop: \'0px\'}, 800);
			}
		);
	});
</script>
<script type="text/javascript" >
	jQuery(document).ready(function(){
		jQuery("a.hint").click(function(){
			var targetID = jQuery(this).attr(\'href\');
			jQuery(targetID).fadeIn();
		});
		jQuery("a#close-hint,a.close-hint").click(function(){
			var targetID = jQuery(this).attr(\'href\');
			jQuery("#hint-container").hide();
		});
	});
</script>
'; ?>
