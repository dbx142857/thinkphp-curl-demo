<?php if (!defined('THINK_PATH')) exit();?><html>   
<head>   
<meta charset="UTF-8">  
<title>百兴音乐网</title>     
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.8.3/jquery.min.js"></script>
<style>
		*
	{
		padding:0;
		margin:0;
		font-weight:bolder;
		font-family:"微软雅黑";
		text-align:center;
	}
	body
	{
		padding-top:20px;
	}
	a
	{
		text-decoration:none;
		font-size:18px;
	}
	a:hover
	{
		color:chocolate;
	}
</style>
</head>
    <a href="javascript:;" id="upload_music">上传音乐</a><br>
    <a href="javascript:;" id="music_name">music_name表</a><br>
	<a href="javascript:;" id="user_manage">用户账户管理</a><br>
	<a href="javascript:;" id="user_info_manage">用户信息管理</a><br>
	<a href="Index.php?m=Admin&a=suggest" target="iframe2" id="user_suggest_manage">用户建议管理</a><br>
</body>	
</html>
<script>
$("a").click(function()
	{
		window.parent.document.getElementById('iframe2').src="index.php?m=Admin&a="+this.id;
	})
</script>