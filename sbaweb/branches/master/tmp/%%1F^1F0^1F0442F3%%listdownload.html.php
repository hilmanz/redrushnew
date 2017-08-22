<?php /* Smarty version 2.6.13, created on 2012-04-12 07:18:24
         compiled from Download/listdownload.html */ ?>
<html>
<head>
	<title>Ini adalah List Download</title>
</head>

<body>
<?php echo '
 <script language="javascript" src="js/jquery.js"></script>
  <script language="javascript">
  var obj = new Object();
   function loadData(id){
      $.get(\'download.php\',
		{q:id},
		function(data){
		  if(data.error == undefined){ 
			  var num = data.don.length;
			  $(\'#content\').hide().html(\' \');
			  var htm = \'\';
			  for(var i=0; i<num; i++){
				htm += \'<div id="wrapper_content" align="left" style="float:left; padding-left:15px; ;">\';
				htm +=	\'<div id="wrap_content_album" align="left" style="float:left; margin-right:10px;  width:200px; height:200px;  ">\';
				htm +=		\'<div id="kiri_content" align="left" style="float:left; width:140px;">\';
				htm +=			\'<div id="image_thumb" align="left" style="float:left;width:100px;height:100px;">\';
				htm +=				\'<img src="contents/download/\'+data.don[i].thumb+\'" style="width:100px; height:100px; padding-top:25px;">\';
				htm +=				\'<a href="?view=download&id=\'+data.don[i].id+\'">\'+data.don[i].name+\'</a>\';						
				htm +=			\'</div>\';
				htm +=		\'</div>\';
				htm +=	\'</div>\';
				htm += \'</div>\';
			  }
			  $(\'#content\').html(htm);
			  $(\'#content\').fadeIn();
		}else{
			 alert(data.error);
		  }
		},\'json\'
      );      
   }
   $(function(){
	   $(\'#category\').change(
			function(){
				if( $(\'#category:selected\').val() != \'\' )
					//loadData($(this).val());
					$("#frm1").submit();
				}
	   );
   });
  </script>
'; ?>


<div class="title">
	
	<h1>List Downloads</h1>
	<div align="right" >
		<form id="frm1" method="get">
		<input type="hidden" name="download" value="1"/>
			<select name="c" id="category">	
			<option value="0">Category</option>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['select_filter']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<option value="<?php echo $this->_tpl_vars['select_filter'][$this->_sections['i']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['select_filter'][$this->_sections['i']['index']]['name']; ?>
</option>
			<?php endfor; endif; ?>
			</select>
		</form>
	</div>
</div>

	<div id="content">
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['content_download']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<div id="wrap_content_album" align="left" style="float:left; margin-right:10px;  width:125px; height:200px;  ">
					<div id="image_thumb" align="left" style="float:left;width:100px;height:100px;">
						<img src="contents/download/<?php echo $this->_tpl_vars['content_download'][$this->_sections['i']['index']]['thumb']; ?>
" style="width:100px; height:100px; padding-top:25px;">
						<div>
							<?php echo $this->_tpl_vars['content_download'][$this->_sections['i']['index']]['name']; ?>

							<br>
							<?php echo $this->_tpl_vars['content_download'][$this->_sections['i']['index']]['size']; ?>

							<br>
						</div>
					<div>
						<a href="download.php?f=<?php echo $this->_tpl_vars['content_download'][$this->_sections['i']['index']]['file']; ?>
&uid=<?php echo $this->_tpl_vars['uid']; ?>
&name=<?php echo $this->_tpl_vars['content_download'][$this->_sections['i']['index']]['name']; ?>
" class="download" style="margin-bottom: 500px;">Download</a>
					</div>
				</div>					
			</div>
		<?php endfor; endif; ?>
	<p><?php echo $this->_tpl_vars['paging']; ?>
</p>
	
	</div>