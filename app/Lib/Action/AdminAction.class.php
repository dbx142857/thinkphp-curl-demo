<?php
class AdminAction extends Action
{
	function music_name()
	{
		$ap=new AjaxPageAction(array(
			"table_name"=>"music_name",
			"id_field_name"=>"id"
		));
		$ap->show($this,"page");
	}
	function index()
	{
		if($_SESSION['cur_user_id']!=1){
    		echo "这是系统后台，你非法操作，非请勿入！";die;
    	}
		header("content-type:text/html;charset=utf8");
		$this->display("index");die;
	}
    function upload_music()
    {
        $cats=M("music_cate");
        $this->assign("all_cates",json_encode($cats->select()));
        $this->display();
    }
	function music_accounts()
	{
		$ap=new AjaxPageAction(array(
			"table_name"=>"music_accounts",
			"id_field_name"=>"user_id"
		));
		$ap->show($this,"page");
	}
	function user_page_setting()
	{
		$ap=new AjaxPageAction(array(
			"table_name"=>"user_page_setting",
			"id_field_name"=>"user_id"
		));
		$ap->show($this,"page");
	}
	function left()
	{
		$this->display();
	}
	function suggest()
	{
		$ap=new AjaxPageAction(array(
			"table_name"=>"suggest",
			"id_field_name"=>"id"
		));
		$ap->show($this,"page");
	}
    function uploadify_do()
    {
    	if($_SESSION['cur_user_id']!=null){
    		$cid=$_POST['cat_id'];
	           $storage = new SaeStorage();
	        $domain = 'bxmusic';
	        $destFileName = $_FILES['Filedata']['name'];
	        $srcFileName = $_FILES['Filedata']['tmp_name'];
	        $result = $storage->upload($domain,$destFileName, $srcFileName);
			$kzm=pathinfo($destFileName, PATHINFO_EXTENSION);
			
	        if($result&&in_array($kzm,array("mp3","wav","wma","ogg"))){
	        
	        	$data=array(
	            	"cat_id"=>$cid,
	            	"src"=>$result,
	            	"name"=>$_FILES['Filedata']['name']
	            );
				$db=M("music_name");
				echo $db->add($data)?1:0;
	        }
			else{
				echo "error";
			}
    	}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }
}
