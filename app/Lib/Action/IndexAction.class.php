<?php
class IndexAction extends Action 
{
    public function index()
    {
    	$this->sort_imgs("win4000_com");die;
    	//echo file_get_contents("http://50tu.com/meinv/");die;
       $this->display();
    }
	function get_site_content(){
		$url=$_POST['site_url'];
		$str=file_get_contents($url);
		$str=str_replace("script","", $str);
		echo $str;
	}
	function down_img(){
		set_time_limit(0);
		$file=$_GET['file'];
		$cat=$_GET['cat'];
		if(!file_exists("downloads/{$cat}")){
			mkdir("downloads/{$cat}");
		}
		$name="downloads/$cat/".basename($file);
		if(file_put_contents($name, file_get_contents($file))){
			echo "文件成功保存在downloads/".$cat."/".$cat."目录下";
		}
	}
	function sort_imgs($dir_name){
		$dir="downloads/$dir_name/";
		if(!file_exists($dir)){
			echo "目录不存在，已经操作过了哦";
			return;
		}
		$handle=opendir($dir);
		$dir_phone="downloads/{$dir_name}_phone/";
		$dir_pc="downloads/{$dir_name}_pc/";
		$dir_phone_hd="downloads/{$dir_name}_phone_hd/";
		$dir_pc_hd="downloads/{$dir_name}_pc_hd/";
		if(!file_exists($dir_phone)){
			mkdir($dir_phone);
		}
		if(!file_exists($dir_pc)){
			mkdir($dir_pc);
		}
		if(!file_exists($dir_phone_hd)){
			mkdir($dir_phone_hd);
		}
		if(!file_exists($dir_pc_hd)){
			mkdir($dir_pc_hd);
		}
		while($file=readdir($handle)){
			if($file!="."&&$file!=".."){
				
				if(!file_exists($dir_phone.$file."/")){
					mkdir($dir_phone.$file."/");
				}
				if(!file_exists($dir_pc.$file."/")){
					mkdir($dir_pc.$file."/");
				}
				if(!file_exists($dir_phone_hd.$file."/")){
					mkdir($dir_phone_hd.$file."/");
				}
				if(!file_exists($dir_pc_hd.$file."/")){
					mkdir($dir_pc_hd.$file."/");
				}
				$h=opendir($dir.$file);
				while($f=readdir($h)){
					if($f!="."&&$f!=".."){
						$old_name=$dir.$file."/".$f;
						if($arr=getimagesize($old_name)){
							if($arr[0]<$arr[1]){
								rename($old_name,$dir_phone.$file."/".$f);
							}else{
								rename($old_name,$dir_pc.$file."/".$f);
							}
						}else{
							echo $old_name."损坏";
							if(unlink($old_name)){
								echo "已经删除".$old_name;
							}
						}
					}
				}
				echo "删除目录".$dir.$file."<br>";
				rmdir($dir.$file);
				$hh=opendir($dir_phone.$file);
				while($ff=readdir($hh)){
					if($ff!="."&&$ff!=".."){
						$old=$dir_phone.$file."/".$ff;
						$new=$dir_phone_hd.$file."/".$ff;
						if($arr=getimagesize($old)){
							if($arr[0]>=720&&$arr[1]>=1280){
								if(rename($old, $new)){
									echo "筛选phone_hd文件：{$ff}成功";
								}
							}else{
								if(unlink($old)){
									echo "{$old}不是高清图片，已经删除";
								}
							}
						}else{
							echo $old."损坏";
							if(unlink($old)){
								echo "已经删除".$old_name;
							}
						}
					}
				}
				echo "删除目录".$dir_phone.$file."<br>";
				rmdir($dir_phone.$file);
				$hhh=opendir($dir_pc.$file);
				while($fff=readdir($hhh)){
					if($fff!="."&&$fff!=".."){
						$old=$dir_pc.$file."/".$fff;
						$new=$dir_pc_hd.$file."/".$fff;
						if($arr=getimagesize($old)){
							if($arr[0]>=1920&&$arr[1]>=1080){
								if(rename($old, $new)){
									echo "筛选pc_hd文件：{$fff}成功";
								}
							}else{
								if(unlink($old)){
									echo "{$old}不是高清图片，已经删除";
								}
							}
						}else{
							echo $old."损坏";
							if(unlink($old)){
								echo "已经删除".$old_name;
							}
						}
					}
				}
				echo "删除目录".$dir_pc.$file."<br>";
				rmdir($dir_pc.$file);
			}
			
		}
		echo "删除目录{$dir_phone}<br>";
		echo "删除目录{$dir_pc}<br>";
		echo "删除目录{$dir}<br>";
		rmdir($dir);
		rmdir($dir_phone);
		rmdir($dir_pc);
		echo "操作完成!!";
		echo "<script>location.reload();</script>";
	}
}
?>