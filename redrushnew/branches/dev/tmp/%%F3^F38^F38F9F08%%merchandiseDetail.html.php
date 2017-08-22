<?php /* Smarty version 2.6.13, created on 2012-05-04 15:55:44
         compiled from RedRushWeb//merchandiseDetail.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'RedRushWeb//merchandiseDetail.html', 21, false),)), $this); ?>

<div id="main-container" class="bg-blue">
	<div class="wrapper">
    	<div id="containers">
        <div class="merchandise-text">
        	        </div><!-- .merchandise-text -->
            <div class="logo">
            	<a href="">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel4">
                <div class="titles">
                    <h1>Your Current Points : <span class="yellow"> <?php echo $this->_tpl_vars['points']; ?>
 Pts.</span> </h1>
                    <a class="add-points" href="?page=race">&nbsp;</a>
                </div>
            	<div class="entry">
                    	<div class="merchandise">
                            <div class="merchandise-list" id="merchandise-list">
							 <?php if (count($this->_tpl_vars['merchandiseItemGroup']) > 1): ?>
                                 <ul class="jcarousel-skin-tango list-merchandise-carousel">
                                    <?php unset($this->_sections['gm']);
$this->_sections['gm']['name'] = 'gm';
$this->_sections['gm']['loop'] = is_array($_loop=$this->_tpl_vars['merchandiseItemGroup']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['gm']['show'] = true;
$this->_sections['gm']['max'] = $this->_sections['gm']['loop'];
$this->_sections['gm']['step'] = 1;
$this->_sections['gm']['start'] = $this->_sections['gm']['step'] > 0 ? 0 : $this->_sections['gm']['loop']-1;
if ($this->_sections['gm']['show']) {
    $this->_sections['gm']['total'] = $this->_sections['gm']['loop'];
    if ($this->_sections['gm']['total'] == 0)
        $this->_sections['gm']['show'] = false;
} else
    $this->_sections['gm']['total'] = 0;
if ($this->_sections['gm']['show']):

            for ($this->_sections['gm']['index'] = $this->_sections['gm']['start'], $this->_sections['gm']['iteration'] = 1;
                 $this->_sections['gm']['iteration'] <= $this->_sections['gm']['total'];
                 $this->_sections['gm']['index'] += $this->_sections['gm']['step'], $this->_sections['gm']['iteration']++):
$this->_sections['gm']['rownum'] = $this->_sections['gm']['iteration'];
$this->_sections['gm']['index_prev'] = $this->_sections['gm']['index'] - $this->_sections['gm']['step'];
$this->_sections['gm']['index_next'] = $this->_sections['gm']['index'] + $this->_sections['gm']['step'];
$this->_sections['gm']['first']      = ($this->_sections['gm']['iteration'] == 1);
$this->_sections['gm']['last']       = ($this->_sections['gm']['iteration'] == $this->_sections['gm']['total']);
?>
                                        <?php if ($this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['id'] != $this->_tpl_vars['merchandise']->id): ?>
                                        <li>
										<form action="" method="GET" id="form_<?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['id']; ?>
">
                                            <div class="description">
                                            <h1><?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['item_name']; ?>
</h1>
                                            <span class="orange" style="display:block;"><?php if ($this->_tpl_vars['ownPart'] == 0): ?> <?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['prize']; ?>
 PTS. <?php else: ?> <span class="purchased-item-detail">PURCHASED</span> <?php endif; ?></span>
                                            <?php if ($this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['variant'] != '0'): ?>
                                            <div class="size-tshirt">
                                                <input type="radio" value="m" name="variant" checked /><label>M</label>
                                                <input type="radio" value="l" name="variant" /><label>L</label>
                                            </div>
                                            <?php endif; ?>
                                          <?php if ($this->_tpl_vars['ownPart'] == 0): ?>
											<input type="hidden" name="merchandise_id" value="<?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['id']; ?>
" >
											<input type="hidden" name="page" value="merchandise" >										
											<input type="hidden" name="act" value="redeem_form" >
                                          	<a class="btn-redeem2" href="javascript:void(0)" onClick="document.getElementById('form_<?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['id']; ?>
').submit()" merchandise_id="<?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['id']; ?>
" >&nbsp;</a><?php else: ?> &nbsp; <?php endif; ?>
                           					  <a class="btn-backs" href="?page=merchandise">&nbsp;</a>
                                            </div>
                                            <img class="img-purchase-detail" src="img/merchandise/<?php echo $this->_tpl_vars['merchandiseItemGroup'][$this->_sections['gm']['index']]['img']; ?>
" alt="" />
										 </form>
									   </li><?php endif; ?>
                                    <?php endfor; endif; ?>
                                  </ul>
                                    <?php else: ?>
                                    <form action="" method="GET" id="form_<?php echo $this->_tpl_vars['merchandise']->id; ?>
">
                                    <div class="description">
                                    <h1><?php echo $this->_tpl_vars['merchandise']->item_name; ?>
</h1>
                                    <span class="orange" style="display:block;"><?php if ($this->_tpl_vars['ownPart'] == 0): ?> <?php echo $this->_tpl_vars['merchandise']->prize; ?>
 PTS. <?php else: ?> <span class="purchased-item-detail">PURCHASED</span> <?php endif; ?></span>
                                    <?php if ($this->_tpl_vars['merchandise']->variant != '0'): ?>
                                    <div class="size-tshirt">
                                        <input type="radio" value="m" name="variant" checked  /><label>M</label>
                                        <input type="radio" value="l" name="variant" /><label>L</label>
                                    </div>
                                    <?php endif; ?>
                                  <?php if ($this->_tpl_vars['ownPart'] == 0): ?>
											<input type="hidden" name="merchandise_id" value="<?php echo $this->_tpl_vars['merchandise']->id; ?>
" >
											<input type="hidden" name="page" value="merchandise" >										
											<input type="hidden" name="act" value="redeem_form" >								  
                                  	<a class="btn-redeem2" href="javascript:void(0)" onClick="document.getElementById('form_<?php echo $this->_tpl_vars['merchandise']->id; ?>
').submit()" merchandise_id="<?php echo $this->_tpl_vars['merchandise']->id; ?>
" >&nbsp;</a><?php else: ?> &nbsp; <?php endif; ?>
                         		    <a class="btn-backs" href="?page=merchandise">&nbsp;</a>
                                    </div>
                                    <img class="img-purchase-detail" src="img/merchandise/<?php echo $this->_tpl_vars['merchandise']->img; ?>
" alt="" /> 
									</form>
                               <?php endif; ?>
							  
                            </div>
                        </div><!-- .merchandise -->
                </div><!-- .entry -->
            </div><!-- .panel -->
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->
					   <div id="popup-merchandise" class="popup popupmerchandise ">
						
							<div class="confirm-buy">
                                   <h2>Confirm Reedem</h2>
                                    <div class="thumb-merchandise" ><?php if ($this->_tpl_vars['ownPart'] == 0): ?><img src="img/merchandise/<?php echo $this->_tpl_vars['merchandise']->img; ?>
" alt="" /><?php else: ?><img class="img-purchase-detail" src="img/merchandise/<?php echo $this->_tpl_vars['merchandise']->img; ?>
" alt="" /><?php endif; ?></div>
                                    <h3>Reedem This Merchandise ?</h3>
                                    <span class="sparepart-point purchase_point" ><?php if ($this->_tpl_vars['ownPart'] == 0): ?> <?php echo $this->_tpl_vars['merchandise']->prize; ?>
 PTS. <?php else: ?> PURCHASED <?php endif; ?></span>
                                    <div class="confirm-btn">
                                     <?php if ($this->_tpl_vars['ownPart'] == 0): ?>  <a href="javascript:void(0)"  merchandise_id="<?php echo $this->_tpl_vars['merchandise']->id; ?>
" class="purchaseMerchandise purchase"  >Yes</a><?php else: ?>  <span class="purchased-item-detail">PURCHASED</span> <?php endif; ?>
                                        <a href="#" class="cancel-purchase">Exit</a>
                                    </div>
                       
							</div>  								
                            </div>
                            <div class="backgroundPopup"></div>

<?php echo '
<script>
$(document).ready(function() {

$(\'.purchaseMerchandise\').click(function(){
$(\'a.btn-redeem2\').css({\'display\' : \'none\'});
$(\'#merchandise_id\').val($(this).attr(\'merchandise_id\'));
//$(\'#purchaseMerchandise\').submit();

var merchandise_id = $(this).attr(\'merchandise_id\');
$.post(\'?page=merchandise&act=purchaseMerchandise\', { merchandise_id: merchandise_id },
				function(data) {
			$(\'.purchase_point\').html(data);
	});



});
//$(\'.size-tshirt\').change(function(){
//var href = $(\'.btn-redeem2\').attr(\'href\').replace();
//$(\'.btn-redeem2\').attr(\'href\',href+\'&variant=\'+$(this).val());
//});
$(\'.cancel-purchase\').click(function(){
	$(".backgroundPopup").fadeOut("slow");
	$(".popup").fadeOut("slow");
	popupStatus = 0;
});
});
</script>
'; ?>
