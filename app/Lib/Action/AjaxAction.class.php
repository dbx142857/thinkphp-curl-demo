<?php
	class AjaxAction extends Action
	{
        //加载音乐数据
        function load_music_datas()
        {
        	$cate_id=$_POST['cate_id'];
			$db=M("music_name");
			echo json_encode($db->where("cat_id=".$cate_id)->select());
        }
		function let_me_update()
		{
			//只可以保存单个数据
			$key=implode("",array_keys($_POST));
			$db=M('user_page_setting');
			if($key=="bg")
			{
				$previous_bg_src=$db->where("user_id=".$_SESSION['cur_user_id'])->getField('bg');
				if(strlen($previous_bg_src)>2)
				{
					@unlink($previous_bg_src);
				}
			}
			$data[$key]=$_POST[$key];
            
			$uid=$_SESSION['cur_user_id'];
			$db->where("user_id=$uid")->save($data);
            echo $db->getLastSql();
		}
		function load_lrc()
		{
			header("Content-type:text/html;charset=gb2312");
			$str=$_POST['lrc_name'];
			$url=$str.".lrc";
			$new_url=null;
			$url1=$str.".trc";
			$arr=explode("-", $url);
			foreach ($arr as $key => &$value) {
				$value=trim($value);
			}
			$new_url=implode("-", $arr);
			// $url=iconv('UTF-8','GB2312',$url);
			// $url1=iconv('UTF-8','GB2312',$url1);
			// $url2=iconv('UTF-8','GB2312',$url2);
			// $new_url=iconv('UTF-8','GB2312',$new_url);
			//echo $new_url;die;
			//$new_url=iconv('UTF-8','GB2312',$new_url);
			//echo $new_url;die;
			//echo file_get_contents($new_url);die;
			try 
			{
				echo file_get_contents($new_url);die;
			} 
			catch(Exception $e) 
			{
				try 
				{
					echo file_get_contents($url);die;
				} 
				catch(Exception $e) 
				{
					echo file_get_contents($url1);die;
				}
			}
			echo file_get_contents($new_url);die;
			echo $new_url;die;
			if(file_exists($new_url)){
				echo "hello";
			}
			else
				{
					echo "fucku";
				}
				die;
			echo $new_url;die;
			if($lrc=file_get_contents($new_url)){
				echo $new_url;die;
			}
			else {
				echo $url."<br>".$new_url;
			}
			
			
			// if(substr(strtolower(php_uname()),0,7)=="windows")
			// {
				// $url=iconv('UTF-8','GB2312',$url);
				// $url1=iconv('UTF-8','GB2312',$url1);
				// $url2=iconv('UTF-8','GB2312',$url2);
			// }
			if($lrc=file_get_contents($url)){
				echo $lrc;die;
			}
			else if($lrc=file_get_contents($url1)){
				echo $lrc;die;
			}
			else if($lrc=file_get_contents($url2)){
				echo $lrc;die;
			}
			else{
				
			}
			// if(file_exists($url))
			// {
				// $handle = fopen($url, "r");
				// $content =fread($handle, filesize ($url));
				// fclose($handle);
				// echo $content;
			// }
			// else if(file_exists($url1))
			// {
				// $handle = fopen($url1, "r");
				// $content = fread($handle, filesize ($url1));
				// fclose($handle);
				// echo $content;
			// }
			// else if(file_exists($url2))
			// {
				// $handle = fopen($url2, "r");
				// $content = fread($handle, filesize ($url2));
				// fclose($handle);
				// echo $content;
			// }
		}
		function log_in()
		{
			$account=$_POST['log_in_account_input_text'];
			$pwd=$_POST['log_in_pwd_input_text'];
			$if_auto_login=$_POST['if_auto_login'];
			$pwd=sha1($pwd);
			$db=M('music_accounts');
			$arr=$db->where("account='$account'")->select();
			if(!empty($arr))
			{
					if($arr[0]['pwd']==$pwd)
					{
							$_SESSION['cur_user_id']=$db->where("account='$account'")->getField("user_id");
							if($if_auto_login==1)
							{
								setcookie("cur_user_id",$_SESSION['cur_user_id'],time()+3600*24*14);
							}
							header("location:Index.php?m=Index&a=bxmusic");
					}
					else
					{
						$this->error("亲，密码好像不对哦","Index.php?m=Index&a=bxmusic");
					}
			}
			else
			{
				$this->error("亲，好像不存在这个用户哦","Index.php?m=Index&a=bxmusic");
			}
		}
		function log_out()
		{
			
			unset($_SESSION['cur_user_id']);
			session_destroy();
			if(!empty($_COOKIE['cur_user_id']))
			{
				setcookie('cur_user_id' , '' , time()-1);
			}
		}
		function update_favorite_songs()
		{
			
			$song_name=$_GET['song_name'];
			$msg=$_GET['msg'];
			$id=$_SESSION['cur_user_id'];
			$db=M('user_page_setting');
			$favorites=$db->where("user_id=$id")->select();
			$favorites=$favorites[0]['favorite_song'];
			
			if($msg=="jia")
			{
				$if_add_music_icon="♫";
				if($favorites=="")
				{
					$if_add_music_icon="";
				}
				$data['favorite_song']=$favorites.$if_add_music_icon.$song_name;
			}
			else
			{
				$pos=strpos($favorites,"♫");
				$will_removed_str;
				if($pos==null)
				{
					$will_removed_str=$song_name;
				}
				else
				{
					$first_song=substr($favorites,0,$pos);
					if($first_song==$song_name)
					{
						$will_removed_str=$song_name."♫";
					}
					else
					{
						$will_removed_str="♫".$song_name;
					}
				}
				$data['favorite_song']=str_replace($will_removed_str,"", $favorites);
			}
			$db->where("user_id=$id")->save($data);
		}
		function update_body_bg()
		{
			
			$cur_user_id=$_SESSION['cur_user_id'];
			$db=M('user_page_setting');
				$obj=$_FILES['page_bg_chooser_input'];
			$storage = new SaeStorage();
			$domain = 'bxmusic';
				//if($obj['size']<=1024*1024)
				//{
					$previous_bg_src=$db->where("user_id=$cur_user_id")->getField("bg");
					if(strlen($previous_bg_src)>2)
					{
						$storage->delete($domain,$previous_bg_src);
						//@unlink($previous_bg_src);
					}
					// $previous_name=$obj['name'];
					// $postfix=substr($previous_name,-4);
					// $cur_name=$cur_user_id."_".date('YmdHis').rand(1,99999).$postfix;
					// $data['bg']="app/upload_files/body_bg/".$cur_name;
					// if(move_uploaded_file($obj['tmp_name'],$data['bg']))
					// {
						// $db->where("user_id='$cur_user_id'")->save($data);
					// }
					 $destFileName = $obj['name'];
			        $srcFileName = $obj['tmp_name'];
			        $result = $storage->upload($domain,$destFileName, $srcFileName);
					if($result){
						$data['bg']=$result;
						$db->where("user_id='$cur_user_id'")->save($data);
					}
				//}
			
		}
		function update_fd_bg()
		{
			$sys_pic_index=$_POST['sys_pic'];
			$cur_user_id=$_SESSION['cur_user_id'];
			$db=M('user_page_setting');
			if($sys_pic_index=="")
			{
				$obj=$_FILES['change_bg_file'];
				//if($obj['size']<=1024*1024)
				//{
					$previous_bg_src=$db->where("user_id=$cur_user_id")->getField("fd_bg_src");
					$storage = new SaeStorage();
					$domain = 'bxmusic';
					if(strlen($previous_bg_src)!=1)
					{
						$storage->delete($domain,$previous_bg_src);
						//@unlink($previous_bg_src);
					}
					
					 
			        $destFileName = $_FILES['change_bg_file']['name'];
			        $srcFileName = $_FILES['change_bg_file']['tmp_name'];
			        $result = $storage->upload($domain,$destFileName, $srcFileName);
					if($result){
						$data['fd_bg_src']=$result;
						$db->where("user_id='$cur_user_id'")->save($data);
					}
					// $previous_name=$obj['name'];
					// $postfix=substr($previous_name,-4);
					// $cur_name=$cur_user_id."_".date('YmdHis').rand(1,99999).$postfix;
					// $data['fd_bg_src']="app/upload_files/fd_bg/".$cur_name;
					// if(move_uploaded_file($obj['tmp_name'],$data['fd_bg_src']))
					// {
						// $db->where("user_id='$cur_user_id'")->save($data);
					// }
				//}
			}
			else
			{
				$previous_bg_src=$db->where("user_id=$cur_user_id")->getField("fd_bg_src");
					if(strlen($previous_bg_src)!=1)
					{
						@unlink($previous_bg_src);
					}
				$data['fd_bg_src']=$sys_pic_index;
				$db->where("user_id='$cur_user_id'")->save($data);
			}
		}
		function download_mp3_file()
		{
			$f_name =$_POST['path'];
			header("Cache-Control:must-revalidate,post-check=0,pre-check=0"); 
			header("Content-Description:File Transfer"); 
			header ("Content-type: application/octet-stream");
			header ("Content-Length: " . filesize ($f_name));
			header ("Content-Disposition: attachment; filename=" . $_POST['name']); 
			readfile($f_name); 
			
			
			
			// $path=$_COOKIE['path'];
			// $name=$_COOKIE['name'];
			// if(substr(strtolower(php_uname()),0,5)=="linux")
			// {
				// header("Content-type:text/html;charset=utf8");
			// }
			// else
			// {
				// header("Content-type:text/html;charset=gb2312");
				// if($_COOKIE['is_ie']==0)
				// {
					// $path=iconv('UTF-8','GB2312',$path);
					// $name=iconv('UTF-8','GB2312',$name);
				// }
			// }
			// header('Content-type:audio/mp3');
			// header('Content-Disposition:attachment;filename='.$name);
			// readfile($path);
		}
		function register()
		{
			header("content-type:text/html;charset=utf8");
			$account=$_POST['account'];
			$pwd=$_POST['register_pwd_input_text'];
			$re_pwd=$_POST['register_re_pwd_input_text'];
			$email=$_POST['register_email_input_text'];
			
			$reg_account='/^[a-zA-Z_]\w{5,20}$/';
			if (empty($account)) 
			{
				die('用户名不能为空!');
			} 
			else if(!@preg_match($reg_account,$account))
			{
				die('用户名格式错误!');	
			}
			$reg_pwd='/^[a-z0-9!@#$%^&*]{6,16}$/';
			if (!preg_match($reg_pwd, $pwd)) 
			{
				die('密码格式错误!');
			} 
			if($re_pwd!=$pwd){
				die("两次密码不一致");
			} 
			$reg_email='/^\w+@\w+\.(com|cn|net)$/';
			if (!preg_match($reg_email, $email)) 
			{
				die('邮箱格式错误!');
			}
			$music_account=M('music_accounts');
			$user_page_setting=M('user_page_setting');
			$data_music_account=array(
				"account"=>$account,
				"pwd"=>sha1($pwd),
				"email"=>$email,
				"register_time"=>date('Y-m-d h:i:s')
			);
			$data_user_page_setting=array(
				"page_color"=>"pink",
				"if_clock_visible"=>0,
				"bg"=>"29",
				"bg_opacity"=>1.0,
				"sys_volume"=>0.1,
				"lrc_line_height"=>-1,
				"fd_opacity"=>1,
				"list_mode"=>"DIV"
			);
			if($music_account->add($data_music_account)&&$user_page_setting->add($data_user_page_setting))
			{
				$_SESSION['cur_user_id']=$user_page_setting->getLastInsId();
				$this->success("恭喜亲注册成功","Index.php?m=Index&a=bxmusic");
			}
			else
			{
				$this->error("亲，注册失败，再试一次吧","Index.php?m=Index&a=bxmusic");
			}
		}
		function add_suggest()
		{
			$db=M('suggest');
			echo $db->add($_POST)?1:0;
		}
	}
