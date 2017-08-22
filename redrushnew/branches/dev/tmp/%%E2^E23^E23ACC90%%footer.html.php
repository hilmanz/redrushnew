<?php /* Smarty version 2.6.13, created on 2012-06-29 20:46:55
         compiled from RedRushWeb/footer.html */ ?>

    <div id="footer">
    	<div id="site-info">
        	<span>Informasi dalam website ini di tujukan untuk perokok berusia 18 tahun atau lebih dan tinggal di wilayah Indonesia </span>
        </div>
        <div class="menu-footer">
			<a href="">Halaman Utama</a>
			<a href="https://login.marlboro.co.id/Templates/Termsandconditions.aspx" target="_blank">Syarat dan Ketentuan</a>
			<a href="https://login.marlboro.co.id/Templates/RemoveMe.aspx" target="_blank">Hapus Saya</a>
			<a href="https://login.marlboro.co.id/Templates/FAQ.aspx" target="_blank">Daftar Pertanyaan</a>
			<a href="https://login.marlboro.co.id/Templates/Contactus.aspx" target="_blank">Kontak KamI</a>
			<a href="http://www.pmi.com/id/smokingandhealth" target="_blank">Perihal Merokok</a>
        </div>
        <div id="hw">
        </div>
	</div><!-- #footer --> 

<?php echo '
<script type="text/javascript">
$(document).ready(function() {
$(function() {
            $(this).bind("contextmenu", function(e) {
                e.preventDefault();
            });
        }); 
$(document).keydown(function(event) {
		  if ( event.ctrlKey==true || event.keyCode == 123) {
			 return false;
		   }
		});
var tlu001  =  setInterval(function(){ $.post(\'?page=ajax&act=tlu001\')}, 15000);
var qr001  =  setInterval(function(){ $.post(\'?page=ajax&act=qr001\')}, 100000);
var ru001  =  setInterval(function(){ $.post(\'?page=ajax&act=ru001\')}, 100000);
$.post(\'?page=ajax&act=inu001\',function(data) {
   $(\'.count-message\').html(\'( \'+data+\' )\');
 });
var inu001  =  setInterval(function(){ 
$.post(\'?page=ajax&act=inu001\',function(data) {
   $(\'.count-message\').html(\'( \'+data+\' )\');
 });
 }, 10000);

  

  
});
'; ?>

<?php echo $this->_tpl_vars['MOP_EMBED']; ?>

<?php echo '
</script>
'; ?>

<iframe src="refresh.php" style="height:0px;visibility: hidden;"></iframe>