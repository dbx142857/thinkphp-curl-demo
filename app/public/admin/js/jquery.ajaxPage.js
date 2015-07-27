/*
 * !!!超级重要的代码：（如果删除了表格中某一行数据（ajax）后吧该页的数据给删玩了,调用类似以下代码可以返回上衣个P）
 * 	每当一个ajax删除操作成功执行后，需要调用$.check_if_cur_page_null（）方法检测当前页内容是否为空，
 * 如果为空则跳到前一页
 */

/*
 * 说明：
 * 使用方法：
 * 1->引入控制器中的AjaxPageAction.class.php和jquery.ajaxPage.js两个文件,在一个控制器里写方法调用并显示
 * 2->控制器的方法可同时用于页面载入的显示和ajax调用的显示
 * 3->模板页面在以下示例代码不变的情况下需要写入一个以下方法分配的变量：{$APD}
 * 4->该插件使用了全局变量，在一个网页里只可以对一个表格调用该插件,如需多个，也许可尝试iframe
 * 5->运行机制：修改该文件中几个大写的全局变量，调用change_ajax_page时使用这几个变量获取符合条件的记录
 * 6->fun:change_ajax_page_by_where_con->对一组类似下拉菜单的东西遍历生成where条件进行查询
 * 控制器代码示例：(options里的两个方法，第一个用来自定义表格中非操作的td的内容，事件和样式，
 * 
 * 
 * 
 * 
 * 
 * html_demo:
 * <html>
	<head>
		<title>i love u i want u i need u ifuck u</title>
			<script src="app/public/js/jquery.js"></script>
			<script src="app/public/js/jquery.ajaxPage.js"></script>
	</head>
	<style>
		
	</style>
<body>
	$('body).ajax_page();
</body>
</html>
 * 
 * 
 * 
 * function role_manage()
	{
		单表查询示例：
	  	$ap=new AjaxPageAction(array(
			"table_name"=>"qx_roles",
			"page_count"=>20,//不写则默认10
			"where"=>isset($_POST['where'])?$_POST['where']:'',
			"id_field_name"=>"user_id"
			"url"=>array(
					"index"=>"Index.php",
					"m"=>"Index",
					"a"=>"show_table"//不指定则默认为location.href
		));
		$ap->show($this);
		多表查询示例：
		$ap=new AjaxPageAction(array(
			"table_name"=>"indent",
			"page_count"=>20,//不写则默认10
			// "where"=>isset($_POST['where'])?$_POST['where']:'',
			"sql"=>"select indent.id,goods_id,price,goods_num from indent_detail join indent on indent_detail.indent_id=indent.id",
			"count_sql"=>"select count(indent.id) from indent_detail join indent on indent_detail.indent_id=indent.id"
			
			));
		$ap->show($this);
	}
/*js代码示例：
 * 当采用table模式时：				
	$('table').eq(0).ajax_page();
	当采用div模式时：
		$('table').eq(0).ajax_page({
		MAKE_EVERY_DIV:function($div,item)
		{
			$div.css({
				display:"inline-block",
				width:"28%",
				margin:"20px"
			})
			$("<img>")
				.attr({
					src:item.goods_photo
				})
				.css({
					width:"80%",
					display:"block",
					cursor:"pointer"
				})
				.click(function()
				{
					location.href="Index.php?m=Goods&a=goodsDetail&gid="+item.id;
				})
				.appendTo($div);
			$("<div>")
				.appendTo($div)
				.html(item.goods_name);
			$("<div>")
				.appendTo($div)
				.html("￥"+item.price);
		}
	})
 */
var $TABLE,
P=1,
PAGE_COUNT,
WHERE=null,
URL,
SHOW_TABLE_OPTIONS=null;

jQuery.extend({
	/*
	 * $.change_ajax_page_by_where_con({
	 * 	$con_objs:$('span).eq(0).children(),
	 * mo_hu_query:true,
	 * $firer:$('#query)
	 * })
	 */
	change_ajax_page_by_where_con:function(options)
	{
		var defaults={
			$con_objs:null,
			mo_hu_query:true,
			$firer:null,
			_event:"click"
		};
		var options=$.extend(defaults,options);
		if(options.$con_objs!=null&&options.$firer!=null)
		{
			options.$firer.live(options._event,function()
			{
				WHERE="";
				var where_index=0;
				var objs_num=options.$con_objs.size();
				var if_all_objs_val_null=1;
				options.$con_objs.each(function()
				{
					where_index++;
					if(this.value!="")
					{
						if(options.mo_hu_query==true)
						{
							WHERE+=this.name+" like '"+this.value+"'&";
						}
						else
						{
							WHERE+=this.name+"='"+this.value+"'&";
						}
						if_all_objs_val_null=0;
					}
				})
				WHERE=WHERE.substring(0,WHERE.length-1);
				if(if_all_objs_val_null==1)
				{
					WHERE=null;
				}
				$.change_ajax_page();
			})


		}
	}
	,
	change_ajax_page:function(options)
	{
		var defaults={
			async:true
		}
		var options=$.extend(defaults,options);
		var data="rand="+Math.random()*999;
		if(PAGE_COUNT!=null)
		{
			data+="&page_count="+PAGE_COUNT;
		}
		if(P!=null)
		{
			data+="&p="+P;
		}
		if(WHERE!=null)
		{
			data+="&where="+WHERE;
		}
		$.ajax({
			type:"post",
			url:URL,
			data:data,
			async:options.async,
			success:function(inf)
			{
				SHOW_TABLE_OPTIONS.json=inf;
				SHOW_TABLE_OPTIONS.show_table();
			}
		})
	},
//检测当前页是否内容为空，为空则跳到前一页
	check_if_cur_page_null:function()
	{
		if($TABLE.find("tr").size()-$TABLE.attr("default_tr_num")==2)
		{
			P=P==1?1:P-1;
			$.change_ajax_page();
		}
	}
})
var make_page_controller_td=function($td,total_count,p,page_count)
{
	total_count=total_count==null?0:total_count;
	var COLOR="orange",
	FONTSIZE="1.2em";
	var total_page=Math.ceil(total_count/page_count);
	$total_count=$("<span>")
		.html("记录总数：<font>"+total_count+"</font>条&nbsp;&nbsp;")
		.appendTo($td);
	$cur_page=$("<span>")
		.html("当前第<font>"+p+"/</font><font>"+(total_count==0?1:total_page)+"</font>页&nbsp;&nbsp;每页")
		.appendTo($td);
	$page_count_text=$("<input>")
		.attr({
			type:"text",
			title:"点击并设置每页的记录条数"
		})
		.val(PAGE_COUNT)
		.css({
			width:"40px",
			color:COLOR,
			margin:"0 5px",
			textAlign:"center",
			fontSize:FONTSIZE
		})
		.blur(function()
		{
			PAGE_COUNT=parseInt($(this).val());
			if(PAGE_COUNT>total_count)
			{
				PAGE_COUNT=total_count;
			}
			if(PAGE_COUNT>0)
			{
				$.change_ajax_page()
			}
		})
		.appendTo($td);
	var $tiao=$("<span>")
		.html("条&nbsp;&nbsp;")
		.appendTo($td);
	var p_arr=[1,p-1>0?p-1:1,parseInt(p)+1>total_page?total_page:parseInt(p)+1,total_page];
	$.each(["第一页","上一页","下一页","最后一页"],function(k,v)
	{
		var $a=$("<input>")
			.attr({
				type:"button"
			})
			.val(v)
			.css({
				marginRight:"10px",
				textDecoration:"none",
				background:"none",
				border:"none",
				cursor:"pointer"
			})
			.hover(function()
			{
				$(this).css({
					textDecoration:"underline"
				})
			},function()
			{
				$(this).css({
					textDecoration:"none"
				})
			})
			.click(function()
			{
				P=p_arr[k];
				$.change_ajax_page()
			})
			.appendTo($td);
		$a.get(0).className="ajax_page_navigator";
	})
	$('.ajax_page_navigator')
		.css({
			cursor:"pointer"
		})
		.removeAttr("disabled")
		.removeAttr("title");
	if(P==1||P==null)
	{
		$('.ajax_page_navigator:lt(2)').attr({
			disabled:"disabled",
			title:"亲，当前已经是第一页了哦"
		})
		.css({
			cursor:"default"
		});
	}
	if(P>=total_page)
	{
		$('.ajax_page_navigator:gt(1)').attr({
			disabled:"disabled",
			title:"亲，当前已经是最后一页了哦"
		})
		.css({
			cursor:"default"
		});
	}
	var $select=$("<select>")
		.css({
				color:COLOR,
				fontSize:FONTSIZE
			})
		.attr({
			title:"点击跳转到指定分页哦"
		})
		.change(function()
		{
			P=$(this).val();
			$.change_ajax_page()
		})
		.appendTo($td);
	for(var i=0;i<(total_count==0?1:total_page);i++)
	{
		$("<option>")
			.attr({
				value:i+1
			})
			.html(i<9?"0"+(i+1):i+1)
			.appendTo($select);
	}
	$select.find($('option')).eq(parseInt(P)-1).attr("selected","selected");
	$td.find($('font')).attr("color",COLOR).css({
		fontSize:FONTSIZE
	});
}
jQuery.fn.extend({
	ajax_page:function(options)
	{
		var defaults={
			json:$('#APD').html(),//待解析的json数据
			MAKE_TABLE_CONTENT:null,//每解析出一个记录，就调用该方法给非操作的td自定义写入方式
			MAKE_OPERATE_TD:null,//每解析出一个记录，就调用该方法给操作的td自定义写入方式
			CHANGE_SUCCESS_FUNCTION:null,//当每一页数据加载完毕时调用的方法,
			MAKE_EVERY_DIV:null,
			make_page_controller_td:true,
			allow_edit:true,
			update_data_url:"Index.php?m=AjaxPage&a=update_data",
			style_attr:{
				border:"1",
				cellpadding:"0",
				cellspacing:"0"
			}
		}
		var options=$.extend(defaults,options);
		var $thi=$(this);
		if($thi.get(0).nodeName=="TABLE")
		{
			$TABLE=$thi;
		}
		else
		{
			$TABLE=$('<table>').appendTo($thi);
		}
		for(var i in options.style_attr)
		{
			$TABLE.attr(i,options.style_attr[i]);
		}
		SHOW_TABLE_OPTIONS=options;
		SHOW_TABLE_OPTIONS.show_table=function()
		{
			var msg=$.parseJSON(SHOW_TABLE_OPTIONS.json);
			var config=msg[0];//配置信息
			PAGE_COUNT=parseInt(config['page_count']);
			if(config.url==undefined)
			{
				URL=location.href;
			}
			else
			{
				var the_url=config.url;
				URL=the_url.index+"?m="+the_url.m+"&a="+the_url.a;
			}
			var content=msg[1];//查询出来的信息
			var total_count=msg[2];//数据库中总记录数目
			var show_field=msg[3];//需要显示的字段
			if($TABLE.children().size()==0)
			{
				var $tr=$("<tr align='center'>").appendTo($TABLE);
				for(var v in show_field)
				{
					$("<td>").html(show_field[v]).appendTo($tr);
				}
				if(SHOW_TABLE_OPTIONS.MAKE_OPERATE_TD!=null)
				{
					$("<td>").html("操作").appendTo($tr);
				}
			}
			else
			{
				$TABLE.html($TABLE.attr("default_table_content"));
			}
			$TABLE
			.attr({
				default_table_content:$TABLE.html(),
				default_tr_num:$TABLE.find("tr").size()
			});
			if(config.page_type=="table"||config.page_type==undefined)
			{
				if(options.MAKE_TABLE_CONTENT==null)
								{
									options.MAKE_TABLE_CONTENT=function(item,name,value,$td)
									{
										switch(name)
										{
											default:
											{
												$td.html(value);
												break;
											}
										}
									}
								}
				var show_field_length=show_field.length;
				for(var i in content)
				{
					var item=content[i];
					var $tr=$("<tr align='center'>").appendTo($TABLE);
					var td_index=0;
					for(var k in show_field)
					{
						for(var j in item)
						{
							if(show_field[k]==j)
							{
								td_index++;
								var $td=$("<td>").attr({
									key:show_field[k],
									id_v:item[config.id_field_name]
								}).appendTo($tr);
								options.MAKE_TABLE_CONTENT(item,show_field[k],item[show_field[k]],$td);
								if(options.allow_edit==true)
								{
									$td.click(function()
									{
											var $thi=$(this);
											var table_name;
											if($thi.attr("table_name")==undefined)
											{
												table_name=config.table_name;
											}
											else
											{
												table_name=$thi.attr("table_name");
											}
											if($thi.find("input").size()==0)
											{
												var $input=$("<input type='text' autofocus/>").css({
													display:"block",
													width:"100%",
													height:"100%"
												}).css({
													textAlign:"center"
												}).val($thi.text())
													.blur(function()
													{
														var val=$(this).val();
														$thi.html(val);
														$.ajax({
															type:"post",
															url:options.update_data_url,//你的url
															data:{
																id_k:config.id_field_name,
																id_v:$thi.attr("id_v"),
																table_name:table_name,
																key:$thi.attr("key"),
																value:val
															}
														})
													})
													.appendTo($thi.html("")).focus().select();
													
										}
										
									})
								}
								if(td_index==show_field_length)
								{
									var $td;
									if(options.MAKE_OPERATE_TD!=null)
									{
										$td=$("<td>").appendTo($tr);
										options.MAKE_OPERATE_TD(item,$td);
									}
									td_index=0;
								}
							}
						}
						
					}
				}
				if(options.CHANGE_SUCCESS_FUNCTION!=null)
				{
					options.CHANGE_SUCCESS_FUNCTION();
				}
				if(options.make_page_controller_td==true)
				{
					var $page_control_tr=$("<tr>")
						.appendTo($TABLE);
					var $page_control_td=$("<td>")
						.attr({
							colspan:$TABLE.find("tr").eq(0).children().size(),
							align:"right"
						})
						.css({
							width:"100%"
						})
						.appendTo($page_control_tr);
					make_page_controller_td($page_control_td,total_count,config.p,config.page_count);
				}
			}
			else if(config.page_type=="div")
			{
				for(var i in content)
				{
						if(options.MAKE_EVERY_DIV!=null)
						{
							var $div=$("<div>").appendTo($TABLE);
							options.MAKE_EVERY_DIV($div,content[i]);
						}
				}
				if(options.make_page_controller_td==true)
				{
					var $div=$("<div>").appendTo($TABLE);
					make_page_controller_td($div,total_count,config.p,config.page_count);
				}
			}
		}
		SHOW_TABLE_OPTIONS.show_table();
		return $(this);
	}
})