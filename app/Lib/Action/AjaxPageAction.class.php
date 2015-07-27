<?php
	class AjaxPageAction extends Action
	{
		public $config;
		public $show_field_limit;
		public $total_count;
		public $sql=null;
		public $count_sql;
		function __construct($config_arr,$show_field_limit_arr)
		{
			$this->config=$config_arr;
			$this->config['p']=isset($_POST['p'])?$_POST['p']:1;
			$this->config['page_count']=$_POST['page_count']?$_POST['page_count']:(isset($config_arr['page_count'])?$config_arr['page_count']:10);
			$db=M($this->config['table_name']);
			if(!isset($config_arr['sql']))
			{
				if(isset($show_field_limit_arr))
				{
					$this->show_field_limit=$show_field_limit_arr;
				}
				else
				{
					foreach ($db->getDbFields() as $key => $value) {
						$this->show_field_limit[]=$value;
					}
				}
				$this->total_count=$db->join($this->config['join'])->where($this->config['where'])->count();
			}
			else
			{
				$this->sql=$config_arr['sql'];
				$this->count_sql=$config_arr['count_sql'];
			}
		}
		function getPageContent()
		{
			$db=M($this->config['table_name']);
			$start=($this->config['p']-1)*$this->config['page_count'];
			if($this->sql==null)
			{
				$inf=$db->join($this->config['join'])->where($this->config['where'])->limit("$start,{$this->config['page_count']}")->select();
			}
			else
			{
				$this->total_count=$db->query($this->count_sql);
				foreach ($this->total_count[0] as $key => $value) {
					$this->total_count=$value;
				}
				$this->sql=$this->sql." limit ".$start.",".$this->config['page_count'];
				$inf=$db->query($this->sql);
				foreach ($inf[0] as $key => $value) {
					$this->show_field_limit[]=$key;
				}
			}
			return $inf;
		}
		function show($obj,$tpl_name)
		{
			$inf[]=$this->config;//配置信息
			$inf[]=$this->getPageContent();//本次显示所查询到的内容
			$inf[]=$this->total_count;//数据库中总记录数目
			$inf[]=$this->show_field_limit;//需要显示的字段名称限制
			$inf=json_encode($inf);
			if(!isset($_POST['p'])&&!isset($_POST['page_count'])&&!isset($_POST['where']))
			{
				$APD="<div id=\"APD\" style=\"display:none\">".$inf."</div>";
				echo $APD;
				$obj->display($tpl_name==null?ACTION_NAME:$tpl_name);
			}
			else
			{
				echo $inf;
			}
		}
		function update_data()
		{
			extract($_POST);
			$db=M($table_name);
			$data[$key]=$value;
			$db->where($id_k."=".$id_v)->save($data);
		}
	}
?>