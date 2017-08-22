<?php /* Smarty version 2.6.13, created on 2012-05-16 16:22:58
         compiled from RedRushWeb/popup-engine.html */ ?>


	<div id="popup-engine" class="partbar">
		<a class="popup-close" href="#popup-engine">[x] Close</a>
        <div class="content-popup">
          <ul class="jcarousel-skin-tango list-sparepart-carousel">
                 <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['parts']['engines']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
					<li >
						  <div class="sparepart-list">
							<div class="thumb-sparepart"><img src="contents/delivery/parts/<?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->img; ?>
" alt="" />
                            <?php if ($this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->level > $this->_tpl_vars['level']): ?><img src="img/part_lock.png" class="partlock" /><?php endif; ?>
                           
                           <?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['cek_winning_ultimate_car']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
                                                      <?php if ($this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->level == $this->_tpl_vars['cek_winning_ultimate_car'][$this->_sections['c']['index']]->level+1): ?>
                                <?php if ($this->_tpl_vars['cek_winning_ultimate_car'][$this->_sections['c']['index']]->total == 0): ?>
                                <img src="img/part_lock.png" class="partlock" />
                                <?php endif; ?> 
                            <?php endif; ?>
                           <?php endfor; endif; ?>
                            
                            </div>
							<div class="sparepart-detail">
								<span class="sparepart-name">LEVEL <?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->level; ?>
</span><br>
								<span class="sparepart-name" ><?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->name; ?>
 </span><br>
								<span class="sparepart-point  <?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->id; ?>
_class"><?php if ($this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->ownPart == 0): ?> <?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->price; ?>
 PTS. <?php else: ?> PURCHASED <?php endif; ?></span>
							<a href="javascript:void(0)" type-part="engine" part_point="<?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->price; ?>
" part_id="<?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->id; ?>
" img_path="contents/delivery/parts/<?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->img; ?>
"  class="purchasePartFirst <?php if ($this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->level > $this->_tpl_vars['level']):  elseif ($this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->ownPart == 0): ?> buy-item <?php endif; ?> <?php echo $this->_tpl_vars['parts']['engines'][$this->_sections['i']['index']]->id; ?>
_purchase"  ></a>   
							</div>
						   </div>          
				  </li>
						  <?php endfor; endif; ?>
              
          </ul>
       </div>
       
    
    <div class="confirm-buy engine" style="display:none;">
            <h2>Confirm</h2>
			<div class="thumb-sparepart" ><img class="purchase_img" src="contents/delivery/parts/<?php echo $this->_tpl_vars['parts']['engines'][0]->img; ?>
" alt="" /></div>
            <h3 class="">Purchase This Part ?</h3>
            <span class="sparepart-point  purchase_point" ><?php if ($this->_tpl_vars['parts']['engines'][0]->ownPart == 0): ?> <?php echo $this->_tpl_vars['parts']['engines'][0]->price; ?>
 PTS. <?php else: ?> PURCHASED <?php endif; ?></span>
            <div class="confirm-btn">
              <a href="javascript:void(0)" part_id="<?php echo $this->_tpl_vars['parts']['engines'][0]->id; ?>
" class="purchasePart purchase"  >Yes</a>
                <a href="#" class="cancel-purchase">Exit</a>
            </div>
        </div>    
	</div>
	<div class="backgroundPopup"></div>
    
    