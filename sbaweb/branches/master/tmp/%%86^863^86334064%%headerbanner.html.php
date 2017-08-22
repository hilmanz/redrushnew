<?php /* Smarty version 2.6.13, created on 2012-04-12 08:31:43
         compiled from Banner/headerbanner.html */ ?>
<div id="jqb_object">	
	<div class="jqb_slides">
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['show_banner_header']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<div class="jqb_slide">
			<?php if ($this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['type'] == 'image'): ?>
				<a href="<?php echo $this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['redirect_url']; ?>
"><img src="contents/banner/<?php echo $this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['file']; ?>
" /></a>
			<?php else: ?>
				<a href="<?php echo $this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['redirect_url']; ?>
">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="<?php if ($this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['position'] == 0): ?>970<?php else: ?>240<?php endif; ?>" height="<?php if ($this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['position'] == 0): ?>170<?php else: ?>200<?php endif; ?>" align="middle">
					<param name="allowFullScreen" value="false" />
					<param name="movie" value="contents/banner/<?php echo $this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['file']; ?>
" />
					<param name="quality" value="high" />
					<param name="scale" value="noscale" />
					<param name="salign" value="lt" />
					<param name="wmode" value="opaque" />
					<param name="allowScriptAccess" value="always" />
					<embed src="contents/banner/<?php echo $this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['file']; ?>
" quality="high" width="<?php if ($this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['position'] == 0): ?>970<?php else: ?>240<?php endif; ?>" height="<?php if ($this->_tpl_vars['show_banner_header'][$this->_sections['i']['index']]['position'] == 0): ?>170<?php else: ?>200<?php endif; ?>" wmode="opaque" align="middle" scale="noscale" salign="LT" allowFullScreen="false" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
					</object>
				</a>
			<?php endif; ?>
			</div>
		<?php endfor; endif; ?>
	</div>
</div>