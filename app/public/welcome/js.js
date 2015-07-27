$.fn.extend({
	hover_scale:function(options)
	{
		var defaults={
			scale:1.2,
			duration:500
		};
		var options=$.extend(defaults,options);
		if($(this).attr("default_width")==undefined)
		{
			var default_w=$(this).width();
			var default_h=$(this).height();
			var default_l=$(this).position().left;
			var default_t=$(this).position().top;
			var default_font_size=parseInt($(this).css("font-size"));
			$(this).attr("default_width",default_w);
			$(this).attr("default_height",default_h);
			$(this).attr("default_left",default_l);
			$(this).attr("default_top",default_t);
			$(this).attr("default_font_size",default_font_size);
		}
		var target_w=options.scale*$(this).attr("default_width");
		var target_h=options.scale*$(this).attr("default_height");
		var target_l=$(this).attr("default_left")-(target_w-$(this).attr("default_width"))/2;
		var target_t=$(this).attr("default_top")-(target_h-$(this).attr("default_height"))/2;
		var target_font_size=options.scale*$(this).attr("default_font_size");
		$(this).hover(function()
		{
			$(this).stop().animate({
				width:target_w+"px",
				height:target_h+"px",
				lineHeight:target_h+"px",
				left:target_l+"px",
				top:target_t+"px",
				fontSize:target_font_size
			},options.duration);
		},function()
		{
			$(this).stop().animate({
				width:$(this).attr("default_width")+"px",
				height:$(this).attr("default_height")+"px",
				lineHeight:$(this).attr("default_height")+"px",
				left:$(this).attr("default_left")+"px",
				top:$(this).attr("default_top")+"px",
				fontSize:$(this).attr("default_font_size")+"px"
			},options.duration);
		})
		return $(this);
	},
	banner_img_show:function(options)
	{
		var defaults=({
			width:640,
			height:360,
			interval:5000,
			srcs:["red","blue"],
			msg:null//如果按36px字体大小算，支持最多16个字的说明
		});
		var options=$.extend(defaults,options);
		var $outer=$("<div>")
			.appendTo($(this))
			.css({
				width:options.width+"px",
				height:options.height+"px",
				position:"relative",
				margin:"0 auto",
				overflow:"hidden",
				borderRadius:"5px",
				border:"solid rgb(41,22,31) 5px"
			});
		var $inner=$("<div>")
			.appendTo($outer)
			.css({
				height:"100%",
				width:options.width*options.srcs.length+"px",
				position:"absolute"
			})
		var index=0;
		_change_img=function(jia_or_jian)
		{
			$('.banner_img_show_text_div').eq(index).become_hidden();
			index+=jia_or_jian;
			if(index==-1)
			{
				index=options.srcs.length-1;
			}
			if(index==options.srcs.length)
			{
				index=0;
			}
			$inner.stop().animate({
				left:-options.width*index+"px"
			},500);
			$('.banner_img_show_radio')
				.css({
				background:"url('app/public/welcome/images/bullets.png')"
			});
			$('.banner_img_show_radio').eq(index)
				.css({
				background:"url('app/public/welcome/images/bullets.png') -1px -11px"
			});
			setTimeout(function()
			{
				$('.banner_img_show_text_div').eq(index).become_visible({
					
				});
			},500);
		}
		var $prev=$("<div>")
			.appendTo($outer)
			.attr({
				title:"点击切换到上一张"
			})
			.css({
				left:"5px",
				background:"url('app/public/welcome/images/bg_direction_nav_white.png') 0 -1px"
			})
			.click(function()
			{
				_change_img(-1);
			});
			
		var $next=$("<div>")
			.appendTo($outer)
			.attr({
				title:"点击切换到下一张"
			})
			.css({
				right:"5px",
				background:"url('app/public/welcome/images/bg_direction_nav_white.png') -27px -1px"
			})
			.click(function()
			{
				_change_img(1);
			});
		document.onkeydown=function(e)
		{
			var theEvent=window.event||e;
			var code=theEvent.keyCode||theEvent.which;
			if(code==37)
			{
				$prev.click();
			}
			else if(code==39)
			{
				$next.click();
			}
		}
		$.each([$prev,$next],function(k,$v)
				{
					$v
						.css({
							cursor:"pointer",
							width:"29px",
							height:"27px",
							overflow:"hidden",
							position:"absolute",
							top:options.height/2-10+"px",
							opacity:"0",
							padding:"0",
							margin:"0"
						})
				});
				
		var base_css_previous_and_next_sub={
			width:"30px",
			height:"8px",
			position:"absolute",
			background:"white",
			borderRadius:"5px",
			cursor:"pointer"
		}
		$outer.hover(function()
		{
			$.each([$prev,$next],function(k,$v)
			{
				$v.stop().animate({
					opacity:"1"
				},500)
			});
		},function()
		{
			$.each([$prev,$next],function(k,$v)
			{
				$v.stop().animate({
					opacity:"0"
				},500)
			});
		})
		$.each(options.srcs,function(k,v){
			var $item=$("<div>")
			.appendTo($inner)
			.css({
				width:options.width+"px",
				height:"100%",
				position:"absolute",
				left:options.width*k+"px",
				background:v
			})
			$("<div>")
				.appendTo($item)
				.css({
					width:options.msg[k].length*36+30+"px",
					borderRadius:"10px",
					opacity:"0",
					height:"80px",
					lineHeight:"70px",
					textAlign:"center",
					background:"url('app/public/welcome/images/text_bg.png')",
					position:"absolute",
					fontSize:"36px",
					color:"white",
					textShadow:"0 1px 0 #ccc,0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15)",
					behavior:"url('app/public/welcome/css3_pie/PIE.htc')"				
				})
				.html(options.msg[k])
				.get(0).className="banner_img_show_text_div";
			$("<div>")
			.appendTo($outer)
			.css({
				position:"absolute",
				width:"11px",
				height:"11px",
				left:options.width/2+22*(k-options.srcs.length/2)+"px",
				background:"url('app/public/welcome/images/bullets.png')",
				borderRadius:"50%",
				cursor:"pointer",
				top:options.height-30+"px",
				overflow:"hidden"
			})
			.click(function()
			{
				_change_img($('.banner_img_show_radio').index($(this))-index);
			}).get(0).className="banner_img_show_radio";
		})
		$('.banner_img_show_radio').eq(0).css({
			background:"chocolate"
		});
		var $jin_du=$("<div>")
		.appendTo($outer)
		.css({
			position:"absolute",
			width:"0",
			background:"#ccc",
			opacity:"0.5",
			height:"5px",
			borderRadius:"2px",
			bottom:"0",
			marginBottom:"0",
			cursor:"pointer"
		})
		_increase_jin_du=function()
		{
			$jin_du.stop().animate({
				width:options.width+"px"
			},options.interval,function()
			{
				_change_img(1);
				$jin_du.animate({
					width:"0"
				},400);
			})
		}
		_change_img(0);
		_increase_jin_du();
		setInterval("_increase_jin_du()",options.interval+500);
		return $(this);
	},
	become_visible:function(options)
	{
		var defaults=({
			left:($(this).parent().outerWidth()-$(this).outerWidth())/2,
			top:($(this).parent().outerHeight()-$(this).outerHeight())/2,
			duration:500,
			final_opacity:1
		})
		var options=$.extend(defaults,options);
		var init_left=[-$(this).outerWidth(),
								-$(this).outerWidth(),
								$(this).parent().outerWidth(),
								$(this).parent().outerWidth(),
								options.left,
								-$(this).outerWidth(),
								$(this).parent().outerWidth(),
								options.left];
		var init_top=[-$(this).outerHeight(),
								$(this).outerHeight()+$(this).parent().outerHeight(),
								-$(this).outerHeight(),
								$(this).outerHeight()+$(this).parent().outerHeight(),
								-$(this).outerHeight(),
								options.top,
								options.top,
								$(this).outerHeight()+$(this).parent().outerHeight()]
		var index=parseInt(Math.random()*8);
		$(this).stop().css({
			left:init_left[index]+"px",
			top:init_top[index]+"px",
			opacity:0
		})
		.animate({
			left:options.left+"px",
			top:options.top+"px",
			opacity:options.final_opacity
		},options.duration)
	},
	become_hidden:function(options)
	{
		var defaults=({
			left:($(this).parent().outerWidth()-$(this).outerWidth())/2,
			top:($(this).parent().outerHeight()-$(this).outerHeight())/2,
			duration:500,
			final_opacity:1
		})
		var options=$.extend(defaults,options);
		var init_left=[-$(this).outerWidth(),
								-$(this).outerWidth(),
								$(this).parent().outerWidth(),
								$(this).parent().outerWidth(),
								options.left,
								-$(this).outerWidth(),
								$(this).parent().outerWidth(),
								options.left];
		var init_top=[-$(this).outerHeight(),
								$(this).outerHeight()+$(this).parent().outerHeight(),
								-$(this).outerHeight(),
								$(this).outerHeight()+$(this).parent().outerHeight(),
								-$(this).outerHeight(),
								options.top,
								options.top,
								$(this).outerHeight()+$(this).parent().outerHeight()]
		var index=parseInt(Math.random()*8);
		$(this)
		.stop()
		.animate({
			left:init_left[index]+"px",
			top:init_top[index]+"px",
			opacity:0
		},options.duration)
	},
	show_in_center:function(options)
	{
		var defaults={
			x:true,
			y:false
		}
		var options=$.extend(defaults,options);
		var $thi=$(this);
		if(options.x==true)
		{
			var pos=$thi.css("position");
			if($.inArray(pos,["garbage","relative","absolute","fixed"]))
			{
				$thi.css("position","relative");
			}
			$thi.css("left",$thi.parent().outerWidth()/2-$thi.outerWidth()/2+"px");
		}
		$(window).resize(function()
		{
			$thi.show_in_center(options);
		})
		return $thi;
	}
})