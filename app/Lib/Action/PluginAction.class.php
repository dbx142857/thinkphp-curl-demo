<?php
/*
 * 运行时直接实例化，无需写autoloadf方法
 * 严重警告：建无限极分类表时严格遵循：顶级pid=0，子id的pid递增，这样才能保证该插件所有代码完美运行
 */
 /*
  * 插件方法目录：(一下方法中带!!!的不可以独立存在，需要依赖该库中其他方法，所依赖的方法名为!后的括号中的方法)
  * 	checkIfExist()->//该方法配合jquery.form.js里bool_ajax_check方法以及相关方法使用,检查某个字段是否已经存在该值
  * 	!!!(tree)	getAllCateByCateTable：通过提供一个表的名字获取到表中所有的分类，并返回数组
  * 	tree()->递归找到表中所有的分类，并返回数组
  * 	array_add_item_using_another_array()->将另一个数组中所有的键和值添加到另一个数组中，并返回另一个数组
  * 	getAllBrothersAndParentsInCateArray()->在排序完毕的无限极分类数组中赵铎某一个索引对应的节点的所有祖先和兄弟元素
  * 	getAllSonsInCateArray()->在排序完毕的无限极分类数组中赵铎某一个索引对应的节点的所有子节点（可指定是否包含自身）
  * 	!!!(getAllCateByCateTable()和tree())	getCateEditSelect()->通过传递过来的排序完毕的无限极分类数组中某一个索引显示出一个下拉菜单，里面的option为其所有兄弟及其祖先
  * 	!!!(依靠jquery.tinyTools里的deleteDataById方法)deleteDataById（）根据传递过来的id删除对应记录
  * 	show_yzm()->依靠form.js里yzm_system实现验证码显示
  * 	check_yzm()->依靠form.js里yzm_system实现验证码正确性检测
  * */
  /*以后可能用到，但在my_ecshop里没有用到的：
   * getAllSonsInCateArray()
   * */
class PluginAction extends Action 
{
	function __construct()
	{
		header("content-type:text/html;charset=utf8");
	}
	//该方法配合jquery.form.js里bool_ajax_check方法以及相关方法使用
    public function checkIfExist()
    {
        extract($_POST);
		$db=M($table_name);

		if(!isset($self_val))//不存在自身的值，则检查自身的值
		{
			if($db->where("$field='$val'")->select())
			{
				echo 1;
			}
		}
		else//存在自身的值，则不检查自身的值
		{
			$arr=$db->where("$field='$val'")->find();
			if(!empty($arr))
			{
				if($self_val!=$arr[$field])
				{
					echo 1;
				}
			}
		}
    }
	//顶层分类的父id默认为0，如果数据库中顶层分类的父id不为0，则该方法无法运行
	//使用space表示最终数组里值为n个空格的键
	/*！！！调用前需要实例化PluginAction对象
	 * 调用参考：$plugin=new PluginAction();
		return $plugin->getAllCateByCateTable(array(
		"cat_table_name"=>"Cate",
		"pid_field_name"=>"parent_id",//父id在表中的字段
		"self_id_field_name"=>"cate_id",//自己的id
		"cate_name_field_name"=>"cate_name"//分类名称在表中的字段
		));
	 * 
	 * */
	function getAllCateByCateTable($param_arr)
	{
		$db=M($param_arr['cate_table_name']);
		$result=$this->tree($db->select(),0,"",$param_arr);
		return $result;
	}
	function tree($arr,$pid,$space,$param_arr)
		{
			static $result;
			$space.="&nbsp;&nbsp;";
			foreach ($arr as $key => $value) 
			{
				if($value[$param_arr['pid_field_name']]==$pid)
				{
					$value['space']=$space;
					$result[]=$value;
					$this->tree($arr,$value[$param_arr['self_id_field_name']],$space,$param_arr);//找儿子
				}
			}
			return $result;
		}
	/*
	 * 可能会使用到的方法:
	 */
	 
	//将另一个数组里所有的键和值添加到旧数组里，为旧数组添加新的键和值，并返回旧数组
		function array_add_item_using_another_array($old_array,$another_array)
		{
			foreach ($another_array as $key => $value) 
			{
				$old_array[$key]=$value;
			}
			return $old_array;
		}
		function getAllBrothersAndParentsInCateArray($arr,$pid_field_name,$index)
		{
			$last_brother_index=$index;
			for($i=$index+1;$i<count($arr);$i++)
			{
				if($arr[$i][$pid_field_name]>$arr[$index][$pid_field_name])
				{
					$last_brother_index++;
				}
				if($arr[$i][$pid_field_name]<=$arr[$index][$pid_field_name])
				{
					break;
				}
			}
			array_splice($arr,$index,$last_brother_index-$index+1);
			return $arr;
		}
		////调用参考：$plugin->getAllSonsInCateArray($arr,$pid_field_name="parent_id",$index=3);
		function getAllSonsInCateArray($arr,$pid_field_name,$index,$and_self=false)
		{
			$last_brother_index=$index;
			for($i=$index+1;$i<count($arr);$i++)
			{
				if($arr[$i][$pid_field_name]>$arr[$index][$pid_field_name])
				{
					$last_brother_index++;
				}
				if($arr[$i][$pid_field_name]<=$arr[$index][$pid_field_name])
				{
					break;
				}
			}
			return array_splice($arr,$and_self==false?$index+1:$index,$last_brother_index-$index+1);
		}
		//严重警告：该select下拉菜单select标签的name=参数中的$self_id_field_name,option的value值=对应索引数组里的$self_id_field_name
		//根据$index对应的索引获取一个允许添加它的下拉菜单列表，如果未指定$index或者$index为空则获取所有菜单项
		/*调用参考:
		 * $plugin->getCateEditSelect(array(
			"cate_table_name"=>"Cate",
			"pid_field_name"=>"parent_id",
			"cate_name_field_name"=>"cate_name",
			"self_id_field_name"=>"cate_id",
			"index"=>$_GET['k']
		))
		 * 
		 *///为每一个option菜单项添加一个num_of_space属性，便于前台获取空格数量的处理
		function getCateEditSelect($param_arr)
		{
			$arr=$this->getAllCateByCateTable(array(
				"cate_table_name"=>$param_arr['cate_table_name'],
				"pid_field_name"=>$param_arr['pid_field_name'],
				"self_id_field_name"=>$param_arr['self_id_field_name'],
				"cate_name_field_name"=>$param_arr['cate_name_field_name']
				));
			if(!isset($param_arr['index']))
			{
				$index_item_pid=0;
			}
			else
			{
				$index_item_pid=$arr[$param_arr['index']][$param_arr['pid_field_name']];
				$arr=$this->getAllBrothersAndParentsInCateArray($arr,$param_arr['pid_field_name'],$param_arr['index']);
			}
			$select="<select name=\"{$param_arr['self_id_field_name']}\" id=\"{$param_arr['self_id_field_name']}\"><option value=\"0\" num_of_space=\"0\">顶层分类</option>";
			for($i=0;$i<count($arr);$i++)
			{ 
				$cur_item=$arr[$i];
				$num_of_space=strlen($cur_item['space'])/strlen("&nbsp;");
				$select.="<option";
					if($cur_item[$param_arr['self_id_field_name']]==$index_item_pid)
					{
						$select.=" selected=\"selected\"";
					}
					$select.=" value=\"{$cur_item[$param_arr['self_id_field_name']]}\"
					 num_of_space=\"{$num_of_space}\">
						{$cur_item['space']}{$cur_item[$param_arr['cate_name_field_name']]}</option>";
			}
			return $select."</select>";
		}
		function show_yzm()
		{
			// import("ORG.Util.Image");
			// Image::buildImageVerify(4,1,"png",60,30,"yzm");
			
			require_once("app/Common/Image.class.php");
			Image::buildImageVerify(4,1,"png",60,30,"yzm");
		}
		function check_yzm()
		{
			echo md5($_POST["yzm"])==session("yzm")?1:0;
		}
}