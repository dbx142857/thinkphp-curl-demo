<?php if (!defined('THINK_PATH')) exit();?><html>   
<head>   
<meta charset="UTF-8">  
<title>百兴音乐网</title>     
</head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="app/public/js/jquery.min.js"></script>
		<script type="text/javascript" src="app/public/welcome/js.js"></script>

<body>
	<style>
		*
	{
		padding:0;
		margin:0;
		font-weight:bolder;
		font-family:"微软雅黑";
		color:white;
	}
	.support_list_item
{
	text-align:center;
	line-height:80px;
	font-size:20px;
	width:200px;
	height:80px;
	position:absolute;
	background:rgb(16,25,31);
	cursor:pointer;
	border-radius:10px;
}
	#support_list
	{
		position:relative;
		margin:30px 0;
		height:200px;
	}

#container
{
	width:860px;
	background:url('app/public/welcome/images/bg.jpg');
	position:relative;
	padding-top:10px;
}
h2
{
	display:block;
	padding:0 40px;
	margin-bottom:10px;
	margin-top:10px;
	font-size:24px;
}

body
{
	background:black;
}
.dbx_banner
{
	margin-top:100px;
	margin-bottom:10px;
}
#logo
{
	display:block;
	margin-left:65px;
}

</style>
	<div id="container">
		<img src="app/public/welcome/images/logo.png" id="logo"/>
		<div id="whole">
		<center><div id="banner">
			
		</div></center>
		<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好，欢迎来到百兴音乐网，这是您第一次光临本站，为了让您有更好的体验，请首先确认您所使用的浏览器是否在以下列表中:</h2>
		<div id="support_list">
			
		</div>
		<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;若确认您的浏览器在以上列表中，请点击下方的<font style="color:orange;">YES</font>按钮开启您的音乐之旅，否则请点击以上链接下载最新版的浏览器重新进入本网站哦！</h2>
		<center><input id="yes_button" type="image" src="app/public/welcome/images/yes.jpg" /></center>
		</div>
	</div>
</body>
<script>

	$(function()
	{
		var srcs=[];
		for(var i=1;i<=8;i++)
		{
			srcs.push("url('app/public/welcome/images/"+i+".jpg')");
		}
		$('#banner').banner_img_show({
			srcs:srcs,
			msg:new Array("强大的皮肤系统，想怎么换就怎么换","魔方模式+个性右键菜单","高同步率歌词显示系统","登录后可使用更多功能哦","歌曲收藏，喜欢啥就收藏啥","本地歌曲播放支持","内置幻灯片插件 音乐美图两不误","亲，还等什么，赶快试用吧！")
		});
	})
	$('#yes_button').hover(function()
	{
		$(this).attr("src","app/public/welcome/images/yes_hover.jpg");
	},function()
	{
		$(this).attr("src","app/public/welcome/images/yes.jpg");
	}).click(function()
	{
		location.href="Index.php?m=Index&a=bxmusic"
	})
	var $support_list=$('#support_list');
	var support_list=["谷歌27+  (推荐)","360chrome7.3+","百度2.1+","搜狗4.2+","火狐21+","IE10+"];
	$.each(support_list,function(k,v)
	{
		var $div=$("<div>").html(v).attr({
			title:"点击下载我哦"
		}).css({
			left:(k%3)*240+90+"px",
			top:parseInt(k/3)*105+10+"px",
			border:"solid transparent 5px"
		}).hover(function()
		{
			$(this).css("border-color","purple");
		},function()
		{
			$(this).css("border-color","transparent");
		}).appendTo($support_list);
		$div.get(0).className="support_list_item";
		$div.hover_scale({
			duration:100
		})
	})
</script>
<script>
	if($.browser.msie)
	{
		$("<script>").attr({
			src:"app/public/css3_pie/PIE.js"
		}).appendTo($('body'));
		PIE.attach(document.getElementById("container"));
		$('.support_list_item').each(function()
		{
			PIE.attach(this);
		})
		$('#container').show_in_center();
	}
	else
	{
		$('#container').css("margin","0 auto");
	}
</script>	
</html>