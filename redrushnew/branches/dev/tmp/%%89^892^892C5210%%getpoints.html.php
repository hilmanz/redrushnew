<?php /* Smarty version 2.6.13, created on 2012-05-31 16:32:03
         compiled from RedRushWeb//getpoints.html */ ?>
<?php echo '
<script type="text/javascript">
$(\'.logo a\').click(function() {
  $(\'.panel5\').blur();
});
</script>
'; ?>

<div id="main-container" class="home getpoint">
	<div class="wrapper">
    	<div id="containers">
            <div class="logo">
            	<a href="#">&nbsp;</a>
            </div><!-- .logo -->
            <div class="panel5">
                		<div class="title">
                        	<h1>Get Points</h1>
                        </div>
            	<div class="entry">
                        <div class="list">
                        	<div class="box-game">
                            	<h1>Solve The Rush</h1>
                            	<div class="thumb-game"><a href="?page=getpoints&act=minigame1"><img src="img/gamethumb/puzzle.jpg" /></a></div>
                            </div><!-- .box-game -->
                        </div><!-- .list -->
                        <div class="list">
                        	<div class="box-game">
                            	<h1>Whats Cooking? Cucina Italia</h1>
                            	<div class="thumb-game"><a href="?page=getpoints&act=minigame2"><img src="img/gamethumb/cooking.jpg" /></a></div>
                            </div><!-- .box-game -->
                        </div><!-- .list -->
                        <div class="list boxgame3">
                        	<div class="box-game">
                            	<h1>Rush Hour Delivery</h1>
                            	                            	<div class="thumb-game"><a href="?page=getpoints&act=minigame3"><img src="img/gamethumb/rush-hour.jpg" /></a></div>
                            </div><!-- .box-game -->
                        </div><!-- .list -->
                        <div class="list boxgame4">
                        	<div class="box-game">
							<h1>Rush Seekers</h1>
                            	<div class="thumb-game"><a href="?page=getpoints&act=minigame4"><img src="img/gamethumb/treasure.jpg" /></a></div>
                            </div><!-- .box-game -->
                        </div><!-- .list -->
                </div><!-- .entry -->
            </div><!-- .panel -->
            <div id="sidebar">
            	<div class="entry">
                	<table width="200%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                            <div id="profile2">
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
 Race</span>
                                    <span class="total-win"><?php echo $this->_tpl_vars['wins']; ?>
 Wins</span>
                                    <span class="rank">Rank <?php echo $this->_tpl_vars['rank']; ?>
</span>
                                    <span class="total-point"><?php echo $this->_tpl_vars['points']; ?>
 PTS</span>
                                </div>
                           </div><!-- #profile --> 
                        </td>
                      </tr>
                      <tr>
                        <td>
                        <div id="text-getPoint">
                        	<?php echo '
                        	<style>
								#text-getPoint h3 a{
									color:#FF0;
									text-decoration:underline;
								}
								#text-getPoint h3 a:hover{
									color:#fff;
									text-decoration:underline;
								}
							</style>
                            '; ?>

                        	<h3 style="color:#fff; text-align:center; font-size:20px; margin:0; padding:65px 0 0 0;">WIN <a href="index.php?page=race">RACES</a><br />MEET REDRUSH CREW @<br /><a  href="index.php?page=events">EVENTS</a> OR<br />VARIOUS HANGOUT SPOT</h3>
                        </div>
                        </td>
                      </tr>
                    </table>
                </div><!-- .entry -->
            </div><!-- #sidebar -->
            <div class="peoples-new"></div>
        </div><!-- #containers -->
    </div><!-- .wrapper -->
</div><!-- #main-container -->