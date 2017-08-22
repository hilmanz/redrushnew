var last_id = 0;
var api_url = '';
function post_me(id,signed_request){

	var post = $("#post").val();
	
	if(post.length>0){
		var p = {'user_id':id,'text':post,'signed_request':signed_request};
		$.post(api_url+'post.php',p, function(data) {
  			$('#timeline').prepend(data);
		});
	}
}
function clear_post(){
	$("#post").val('');
}
function post_like(uid,post_id,signed_request){
	var p = {'user_id':uid,'post_id':post_id,'signed_request':signed_request};
	$.post(api_url+'like.php',p, function(data) {
		if(data!=""){
			$('#like'+post_id).html("Like ("+data+")");
		}
	});
}
function post_comment(uid,post_id,txt,signed_request){
	var p = {'reply':1,'user_id':uid,'post_id':post_id,'text':txt,'signed_request':signed_request};
	$.post(api_url+'post.php',p, function(data) {
		if(data!=""){
			$('#rs'+post_id).hide();
			view_comment(post_id);
		}
	});
}
function getFeeds(id){
	
	var p = {'user_id':id,'last_id':last_id,'get':'1'};
		$.get(api_url+'post.php',p, function(data) {
			
			var rs = eval(data);
			if(rs[1]!=null){
				$('#timeline').prepend(rs[0]);
				last_id = rs[1];
			}
	});
}
function getNews(id){
	
	var p = {'user_id':id,'last_id':0};
		$.get(api_url+'news.php',p, function(data) {
			
			var rs = eval(data);
			//alert(rs);
			if(rs[1]!=null){
				$('#newsfeeds').prepend(rs[0]);
				//last_id = rs[1];
			}
	});
}
function comment_box(id){
	$('#txtcomment'+id).val("type a comment..");
	$('#comment'+id).show(1);
}

function has_comment(id){
	
	var p = {'post_id':id,'total_reply':'1'};
	$.get(api_url+'post.php',p, function(data) {
		
		if(data>0){
			
			$('#replies'+id).show(1);
		}
	});
}
function view_comment(id){
	var p = {'post_id':id,'get_reply':'1'};
	$.get(api_url+'post.php',p, function(data) {
		var rs = eval(data);
		if(rs[1]!=null){
			$('#replies'+id).show();
			$('#replies'+id).html(rs[0]);
			//last_id = rs[1];
		}
	});
}

var moreStart = 10;
$(document).ready(function(){
	$('#view-all').click(function(){
		$.ajax({
			type: "GET",
			url: api_url+'post.php',
			data: {'user_id':uid,'more':1,'start': moreStart},
			dataType: "json",
			success: function (data) {
				if( data.feed ){
				var num = data.feed.length;
				var htm = "";
				for( var i=0; i<num; i++ ){
					htm += '<div class="list wall">';
					htm += '<a class="small-thumb" href="index.php?profile=1&profile_id='+data.feed[i].user_id+'">';
					if( data.feed[i].small_img != '' ){
						htm += '<img src="contents/images/tiny_'+data.feed[i].user_id+'.jpg"/>';
					}else{
						htm += '<img src="images/no_photo_small.gif"/>';
					}
					htm += '</a>';
					htm += '<div class="text">';
					htm += '<span class="post-comment">';
					htm += '<a href="index.php?profile=1&profile_id='+data.feed[i].user_id+'" class="username">'+data.feed[i].name+'</a>';
					htm += data.feed[i].post;
					htm += '</span>';
					htm += '<p class="comment-act">';
					htm += '<span class="time inline">'+data.feed[i].post_time+'</span> - ';
					htm += '<a class="likethiscomment" href="javascript:;" id="like'+data.feed[i].post_id+'" onclick="likeit('+data.feed[i].post_id+')">Like'; 
					if( data.feed[i].like > 0 ){
						htm += '('+data.feed[i].like+')';
					}
					htm += '</a> - ';
					htm += '<a class="comment-link" href="javascript:;" onclick="comment_box('+data.feed[i].post_id+');return false;">Comment</a> -'; 
					htm += '<span id="replies'+data.feed[i].post_id+'" class="span-11 prepend-1 last box" style="display:none;">';
					htm += '<a href="javascript:;" onclick="view_comment('+data.feed[i].post_id+');return false;">View Comments ('+data.feed[i].reply+')</a></span>';
					htm += '</p>';      
					htm += '</div>';
					htm += '<div id="comment'+data.feed[i].post_id+'" style="display:none;">';
					htm += '<input type="text" name="txtcomment'+data.feed[i].post_id+'" id="txtcomment'+data.feed[i].post_id+'" size="40" value=""/></div>';
					htm += '<span id="rs'+data.feed[i].post_id+'" class="info" style="display:none;">saving comment ...</span>';	
					htm += "<script type='text/javascript'>";
					htm += 		'$("#txtcomment'+data.feed[i].post_id+'").click(function(){';
					htm += 			"var el = 'txtcomment"+data.feed[i].post_id+"';";
					htm += 			'$("#"+el).val("");';		
					htm += 		"});";
					htm += 		'$("#txtcomment'+data.feed[i].post_id+'").keypress(function(event){';
					htm += 			"var el = 'txtcomment"+data.feed[i].post_id+"';";
					htm += 			"var el1 = 'comment"+data.feed[i].post_id+"';";
					htm += 			"var el2 = 'rs"+data.feed[i].post_id+"';";
					htm += 			"var pid = "+data.feed[i].post_id+";";
					htm += 			"if(event.which==13){";
					htm += 				'$("#"+el1).hide();';
					htm += 				'$("#"+el2).show();';
					htm += 				'reply( pid, $("#"+el).val() );';
					htm += 			"}";
					htm += 		"});";
					htm += "</script>";
					htm += "<script>";
					htm += 		"has_comment("+data.feed[i].post_id+");";
					htm += "</script></div>";
				}
				$("#timeline").append(htm);
				moreStart = moreStart + 10;
				}else{
					alert("Tidak ada update yang ditampilkan");
				}
			},
			error: function (res, status) {
				if (status === "error") {
					var errorMessage = $.parseJSON(res.responseText);
					alert(errorMessage.Message);
				}
			}
		});
	});
	
	$('#forum-new').submit(function(){
		//var ext = document.forum-new.img.value;
		var ext = $('#img').val()
		ext = ext.toLowerCase();
		ext = ext.split('.');
		num = ext.length - 1;
		ext = ext[num];
		if(ext == 'jpg' || ext == 'png' || ext == 'gif' || ext == 'bmp' || ext == ''){
			return true;
		}
		alert('Hanya file JPG, PNG, BMP dan GIF yang diperbolehkan!');
		return false;
	});
	
});
