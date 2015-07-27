$(function(){
	$.getScript("app/public/js/pinyin.js",function(){
		$("body,html").css({
			width:"100%",
			height:"100%"
		})
		var $b=$("body");
		var $div=$("<div>").appendTo($("body").css({
			position:"relative"
		}));
		var $msg=$("<div>")
			.appendTo($("body"))
			.css({
				position:"fixed",
				top:"0",
				right:"0",
				width:"400px",
				height:"100%",
				overflowY:"scroll",
				"word-wrap": "break-word",
				background:"rgb(11,234,23)",
				padding:"10px"
			}),
			msg=$msg.get(0);
		var site_host="http://desk.zol.com.cn";
		
		var request=function(host,fn){
			msg.innerHTML+="正在请求："+host+"......<br>";
			$.ajax({
				type:"post",
				url:"index.php?m=Index&a=get_site_content",
				data:{
					site_url:host
				},
				async:false,
				success:function(ee,s){
					try{
						$div.html(ee);
						fn();
					}
					catch(e){
						//fn();
					}
					
				}
			})
		}
		var fn1=function(){
			$(".main_img02 li").each(function(k){
						var href=$(this).find("a:first").attr("href");
						request(href,fn2);
					})
		}
		var down_img=function(cat,src){
			$.ajax({
				type:"get",
				url:"index.php?m=Index&a=down_img&file="+src+"&cat="+cat,
				async:false,
				success:function(e){
					msg.innerHTML+=e+"<br>";
				}
			})
		}
		var fn2=function(cur_href){
			$(".ulSmallPic li").each(function(){
				var src=$(this).find("img").attr("src");
				src=src.substring(0,src.length-11)+".jpg";
				down_img("win4000_com",src);
			})
		}
		for(var i=1;i<=37;i++){
			var url="http://www.win4000.com/mobile_2340_0_0_"+i+".html";
			//localstorage.dbx_index=i;
			request(url,fn1);
		}
	})
})
