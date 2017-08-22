<?php /* Smarty version 2.6.13, created on 2012-05-04 15:55:46
         compiled from RedRushWeb//redeem.html */ ?>

<div id="main-container" class="bg-blue">
	<div class="wrapper">
    	<div id="containers">
        <div class="merchandise-text">
        	<h1>You have chosen to redeem <?php echo $this->_tpl_vars['merchandise']->prize; ?>
 points for <?php echo $this->_tpl_vars['merchandise']->item_name; ?>
</h1>
<p>Remember. Once redeemed, points deducted cannot be restored. Please confirm or update.
The merchandise will be delivered within one month to the address below. Please confirm or update with new details.</p>
        </div><!-- .merchandise-text -->
            <div class="logo">
            	<a href="">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel4">
                <div class="titles">
                    <h1>MERCHANDISE </h1>
                </div>
            	<div class="entry">
                    	<div class="merchandise">
                        	<div class="item-redeem">
                            	<div class="thumb-frame">
                              
                                    	<img class="thumb-item" src="img/merchandise/<?php echo $this->_tpl_vars['merchandise']->img; ?>
" />
                                   <!-- .thumb-item -->
                                </div><!-- .thumb-frame -->
                                <div class="description">
                                    <h1><?php echo $this->_tpl_vars['merchandise']->item_name; ?>
 : <span class="orange"><?php echo $this->_tpl_vars['merchandise']->prize; ?>
 pts</span></h1>
                                    <p><?php echo $this->_tpl_vars['merchandise']->description; ?>
</p>
                                </div>
                            </div><!-- .item -->
                            <div class="redeem-form">
                            	<form action="?page=merchandise&act=purchaseMerchandise" method="POST" class="form-redeem">
                                	<label>Address <span id='erraddress' style='color:red'></span></label>
									<input type="text" name="address"  id="address" onBlur="clearWarning('address')" value="<?php echo $this->_tpl_vars['user_StreetName']; ?>
" />
                                    <label>City <span id='errcity_name' style='color:red'></span></label>
                                    <input type="text" name="city_name" id="city_name" onBlur="clearWarning('city_name')" value="<?php echo $this->_tpl_vars['user_CityName']; ?>
" />
                                    <label>Zip Code <span id='errzip_code' style='color:red'></span></label>
                                    <input type="text" name="zip_code" id="zip_code" onBlur="clearWarning('zip_code')"  />
                                    <label>Phone <span id='errphone' style='color:red'></span></label>
                                    <input type="text" name="phone" id="phone" onBlur="clearWarning('phone')"   />
                                    <label>Mobile <span id='errmobile' style='color:red'></span></label>
                                    <input type="text" name="mobile" id="mobile" onBlur="clearWarning('mobile')" value="<?php echo $this->_tpl_vars['user_MobilePhone']; ?>
"  />
									<input type="hidden" name="merchandise_id" value="<?php echo $this->_tpl_vars['merchandise']->id; ?>
"/>
									<input type="hidden" name="variant" value="<?php echo $this->_tpl_vars['variant']; ?>
"/>
                                    <div class="agreement">
                                    <label>I AGREE WITH THE <a href="?page=tos">TERMS AND CONDITIONS</a></label>
                                    <input type="checkbox" name="checkTOS" id="checkTOS" />
                                    </div>
                                    <div class="rowsubmit">
                                    <input type="button" value="&nbsp;" class="confirm-redeem" />
                                    </div>
                                </form>
                            </div>
                        </div><!-- .merchandise -->
						<div id="bgPopup"></div>
                        <div id="popup-message" class="popup popmessage" style="display:none;">
                            <div class="inner-popup">
                                <h1 style="font-size:30px; color:#fff; margin:0 0 0 0">Please agree to the <br /> Term and Conditions</h1>
                                <a href="#" class="popupClose">OK</a>
                            </div>
                        </div>
                </div><!-- .entry -->
            </div><!-- .panel -->
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->
<?php echo '
<script>
$(\'.confirm-redeem\').click(function(){
var checkTOS = $(\'#checkTOS\').is(\':checked\');
var address = $(\'#address\').val();
var city_name = $(\'#city_name\').val();
var zip_code = $(\'#zip_code\').val();
var phone = $(\'#phone\').val();
var mobile = $(\'#mobile\').val();
if(checkTOS!=true){
          	$("#popup-message").fadeIn("slow");
          	$("#bgPopup").fadeIn("slow");
          	$(".merchandise-text").fadeOut("slow");
            $(".merchandise").fadeOut("slow");
      	 	 $("a.popup-close,a.popupClose").click(function() {
            $("#popup-message").fadeOut("slow");
          	$("#bgPopup").fadeOut("slow");
         	 $(".merchandise").fadeIn("slow");
         	 $(".merchandise-text").fadeIn("slow");
        });
return false;
}
if(address==\'\'){
$(\'#erraddress\').html(\' ( please fill address )\');
return false;
}
if(city_name==\'\'){
$(\'#errcity_name\').html(\' ( please fill city name )\');
return false;
}
if(zip_code==\'\'){
$(\'#errzip_code\').html(\' ( please fill zip code )\');
return false;
}
if(phone==\'\'){
$(\'#errphone\').html(\' ( please fill phone number )\');
return false;
}
if(mobile==\'\'){
$(\'#errmobile\').html(\' ( please fill mobile phone )\');
return false;
}
return $(\'.form-redeem\').submit();
});

function clearWarning(id){

$(\'#err\'+id).html(\'\');
}

</script>
'; ?>


<?php echo '
<script type="text/javascript">
</script>
'; ?>