<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 系统全局配置

    @Filename ConfigAction.class.php $

    @Author pengyong $

    @Date 2011-11-25 20:26:12 $
*************************************************************/
class ConfigAction extends CommonAction
{	
	public function index()
	{
		$type = M('config');
		$list = $type->where('id=1')->find();
		$this->assign('list',$list);
		$this->display('index');
	} 
	public function update()
	{
		$data['sitetitle'] = trim($_POST['sitetitle']); 
		$data['sitetitle2'] = trim($_POST['sitetitle2']); 
		$data['siteurl'] = trim($_POST['siteurl']); 
		$data['updown'] = trim($_POST['updown']); 
		$data['xgwz'] = trim($_POST['xgwz']); 
		$data['sitedescription'] = trim($_POST['sitedescription']); 
		$data['sitekeywords'] = trim($_POST['sitekeywords']); 
		$data['sitelx'] = trim($_POST['sitelx']); 
		$data['sitetcp'] = trim($_POST['sitetcp']); 
		$data['sitelang'] = trim($_POST['sitelang']); 
		$data['watermark'] = trim($_POST['watermark']); 
		$data['watermarkimg'] = trim($_POST['watermarkimg']);
		$data['sitetpl'] = trim($_POST['sitetpl']); 
		$data['indexrec'] = trim($_POST['indexrec']); 
		$data['indexhot'] = trim($_POST['indexhot']);
		$data['indexlink'] = trim($_POST['indexlink']); 
		$data['indexpic'] = trim($_POST['indexpic']); 
		$data['noticeid'] = trim($_POST['noticeid']); 
		$data['noticenum'] = trim($_POST['noticenum']); 
		$data['isping'] = trim($_POST['isping']); 
		$data['pingoff'] = trim($_POST['pingoff']); 
		$data['bookoff'] = trim($_POST['bookoff']); 
		$data['mood'] = trim($_POST['mood']); 
		$data['sitelogo'] = trim($_POST['sitelogo']); 
		$data['ishits'] = trim($_POST['ishits']); 
		$data['iscopyfrom'] = trim($_POST['iscopyfrom']); 
		$data['isauthor'] = trim($_POST['isauthor']); 
		$data['indexvote'] = trim($_POST['indexvote']); 
		$data['ishomeimg'] = trim($_POST['ishomeimg']); 
		$data['mouseimg'] = trim($_POST['mouseimg']);
		$data['iszy'] = trim($_POST['iszy']);
		$data['id'] = trim($_POST['id']); 
		$data['artlistnum'] = trim($_POST['artlistnum']); 
		$data['artlisthot'] = trim($_POST['artlisthot']); 
		$data['artlistrec'] = trim($_POST['artlistrec']); 
		$data['articlerec'] = trim($_POST['articlerec']); 
		$data['articlehot'] = trim($_POST['articlehot']); 
		$data['rollnum'] = trim($_POST['rollnum']); 
		$data['postovertime'] = trim($_POST['postovertime']); 
		$data['urlmode'] = trim($_POST['urlmode']); 
		$data['flashmode'] = trim($_POST['flashmode']); 
		$data['indexnoticetitle'] = trim($_POST['indexnoticetitle']);
		$data['indexrectitle'] = trim($_POST['indexrectitle']);
		$data['indexhottitle'] = trim($_POST['indexhottitle']);
		$data['indexvotetitle'] = trim($_POST['indexvotetitle']);
		$data['indexpictitle'] = trim($_POST['indexpictitle']);
		$data['indexpicnum'] = trim($_POST['indexpicnum']); 
		$data['indexpicscroll'] = trim($_POST['indexpicscroll']); 
		$data['indexlinktitle'] = trim($_POST['indexlinktitle']);
		$data['indexlinkimg'] = trim($_POST['indexlinkimg']);
		$data['indexdiylink'] = trim($_POST['indexdiylink']);
		$data['listrectitle'] = trim($_POST['listrectitle']);
		$data['listhottitle'] = trim($_POST['listhottitle']);
		$data['listshowmode'] = trim($_POST['listshowmode']);
		$data['artrectitle'] = trim($_POST['artrectitle']);
		$data['arthottitle'] = trim($_POST['arthottitle']);
		$data['suffix'] = trim($_POST['suffix']); 
		switch($data['suffix'])
		{
			case 0:
				$resuffix = '.html';
				break;
			case 1:
				$resuffix = '.htm';
				break;
			case 2:
				$resuffix = '.shtml';
				break;
			case 3:
				$resuffix = '.php';
				break;
			case 4:
				$resuffix = '.asp';
				break;
			case 5:
				$resuffix = '.aspx';
				break;
			case 6:
				$resuffix = '.jsp';
				break;
		}
		$type = M('config');
		$config = F('basic','','./Web/Conf/');
		switch($config['suffix'])
		{
			case 0:
				$suffix = '.html';
				break;
			case 1:
				$suffix = '.htm';
				break;
			case 2:
				$suffix = '.shtml';
				break;
			case 3:
				$suffix = '.php';
				break;
			case 4:
				$suffix = '.asp';
				break;
			case 5:
				$suffix = '.aspx';
				break;
			case 6:
				$suffix = '.jsp';
				break;
		}
		unset($config);
		$result = $type->data($data)->save(); 
		if($result)
		{
			F('basic',$data,'./Web/Conf/');
		//url模式处理
			$path = './Web/Conf';
			if($data['urlmode'] == 0)
			{
				$fp = fopen($path."/config.php","r");
				$configStr = fread($fp,filesize($path."/config.php"));
				fclose($fp);
				$configStr = str_replace('=>2','=>1',$configStr);
				$configStr = str_replace('=>3','=>1',$configStr);
				$configStr = str_replace("=>'".$suffix."'","=>'".$resuffix."'",$configStr);
				$configStr = str_replace('index.php?s=','index.php/',$configStr);
				$configStr = str_replace('=>3','=>1',$configStr);
				@chmod($path,0777);
				$fp = fopen($path."/config.php","w") or die("<script>alert('写入配置失败，请检查../Web/Conf目录是否可写入！');history.go(-1);</script>");
				fwrite($fp,$configStr);
				fclose($fp);
			}
			elseif($data['urlmode'] == 1)
			{
				$fp = fopen($path."/config.php","r");
				$configStr = fread($fp,filesize($path."/config.php"));
				fclose($fp);
				$configStr = str_replace('=>1','=>2',$configStr);
				$configStr = str_replace('=>3','=>2',$configStr);
				$configStr = str_replace("=>'".$suffix."'","=>'".$resuffix."'",$configStr);
				$configStr = str_replace('index.php?s=','index.php/',$configStr);
				@chmod($path,0777);
				$fp = fopen($path."/config.php","w") or die("<script>alert('写入配置失败，请检查../Web/Conf目录是否可写入！');history.go(-1);</script>");
				fwrite($fp,$configStr);
				fclose($fp);
			}
			else
			{
				$fp = fopen($path."/config.php","r");
				$configStr = fread($fp,filesize($path."/config.php"));
				fclose($fp);
				$configStr = str_replace('=>1','=>3',$configStr);
				$configStr = str_replace('=>2','=>3',$configStr);
				$configStr = str_replace("=>'".$suffix."'","=>'".$resuffix."'",$configStr);
				$configStr = str_replace('index.php/','index.php?s=',$configStr);
				@chmod($path,0777);
				$fp = fopen($path."/config.php","w") or die("<script>alert('写入配置失败，请检查../Web/Conf目录是否可写入！');history.go(-1);</script>");
				fwrite($fp,$configStr);
				fclose($fp);
			}
			//网站语言
			if($data['sitelang'] == 0)
			{
				$path = './Web/Tpl/'.$data['sitetpl'].'/js/';
				$fp = fopen($path."/language.js","r");
				$configStr = fread($fp,filesize($path."/language.js"));
				fclose($fp);
				$configStr = str_replace('Default_isFT=1','Default_isFT=0',$configStr);
				@chmod($path,0777);
				$fp = fopen($path."/language.js","w") or die("<script>alert('写入配置失败，请检查".$path."目录是否可写入！');history.go(-1);</script>");
				fwrite($fp,$configStr);
				fclose($fp);
			}
			if($data['sitelang'] == 1)
			{
				$path = './Web/Tpl/'.$data['sitetpl'].'/js/';
				$fp = fopen($path."/language.js","r");
				$configStr = fread($fp,filesize($path."/language.js"));
				fclose($fp);
				$configStr = str_replace('Default_isFT=0','Default_isFT=1',$configStr);
				@chmod($path,0777);
				$fp = fopen($path."/language.js","w") or die("<script>alert('写入配置失败，请检查".$path."目录是否可写入！');history.go(-1);</script>");
				fwrite($fp,$configStr);
				fclose($fp);
			}
			alert('操作成功!',U('Config/index'));	
		}
		alert('操作失败!',1);
	}
}
?>