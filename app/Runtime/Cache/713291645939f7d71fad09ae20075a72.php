<?php if (!defined('THINK_PATH')) exit();?><html>   
<head>   
<meta charset="UTF-8">  
<title>百兴音乐网</title>     
<script type="text/javascript" src="app/public/js/jquery.min.js"></script>
	<script type="text/javascript" src="app/public/welcome/js.js"></script>
<style>
		*
	{
		padding:0;
		margin:0;
		font-weight:bolder;
		font-family:"微软雅黑";
	}
	#iframe1
	{
		height:100%;
		width:15%;
		text-align:center;
		border-right:solid orange 2px;
	}
	body
	{
		overflow:hidden;
		position:relative;
	}
	#iframe2
	{
		width:85%;
		position:absolute;
		top:0;
		left:15%;
		height:100%;
	}
</style>
</head>
<body>
	<iframe src="index.php?m=Admin&a=left" frameborder="no" id="iframe1" name="iframe1"></iframe>
	<iframe src="index.php?m=Admin&a=upload_music" frameborder="no" id="iframe2" name="iframe2"></iframe>

	
</body>	
</html>