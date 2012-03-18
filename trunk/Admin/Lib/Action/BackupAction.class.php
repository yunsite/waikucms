<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function:数据库备份与还原

    @Filename BackupAction.class.php $

    @Author pengyong $

    @Date 2011-11-30 15:13:34 $
*************************************************************/
class BackupAction extends CommonAction
{	
     public function index()
    {
		$rs = new Model();
		$list = $rs->query("SHOW TABLES FROM "."`".C('DB_NAME')."`");
		$table = array();
        foreach ($list as $k => $v)
		{
            $table[$k] = current($v);
        }
		$this->assign('tablelist',$table);
		$this->display();
	}
	public function dobackup()
	{
		if(empty($_POST['ids']))
		{
			alert('请选择需要备份的数据库表！',1);
		}
		$filesize = intval($_POST['filesize']);
		if ($filesize < 512) 
		{
			alert('出错了,请为分卷大小设置一个大于512的整数值！',1);
		}
		$file ='./Public/Backup/';
		$random = mt_rand(1000, 9999);
		$sql = ''; 
		$p = 1;
		foreach($_POST['ids'] as $table)
		{
			$rs = D(str_replace(C('db_prefix'),'',$table));
			$array = $rs->select();
			$sql.= "TRUNCATE TABLE `$table`;\n";
			foreach($array as $value)
			{
				$sql.= $this->insertsql($table, $value);
				if (strlen($sql) >= $filesize*1000) 
				{
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;
					$sql='';
				}
			}
		}
		if(!empty($sql))
		{
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		alert('数据库分卷备份已完成,共分成'.$p.'个sql文件存放！',U("Backup/restore"));
	}
	//生成SQL备份语句
	public function insertsql($table, $row)
	{
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) 
		{
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		}
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql;
	}
	//展示还原
    public function restore()
	{
		$filepath = './Public/Backup/*.sql';
		$filearr = glob($filepath);
		if (!empty($filearr)) 
		{
			foreach($filearr as $k=>$sqlfile)
				{
					preg_match("/([0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
					$restore[$k]['filename'] = basename($sqlfile);
					$restore[$k]['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
					$restore[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
					$restore[$k]['pre'] = $num[1];
					$restore[$k]['number'] = $num[2];
					$restore[$k]['path'] = './Public/Backup/';
				}
			$this->assign('list',$restore);
        	$this->display('restore');
		}
		else
		{
			alert('没有检测到备份文件,请先备份或上传备份文件到./Public/Backup/',U("Backup/index"));
		}
    }
	//导入还原
	public function back()
	{
		$rs = new Model();
		$pre = $_GET['id'];
		$fileid = $_GET['fileid'] ? intval($_GET['fileid']) : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = './Public/Backup/'.$filename;
		if(file_exists($filepath))
		{
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query)
			{
				$rs->query(trim($query));
			}
			alert('第'.$fileid.'个备份文件恢复成功,准备恢复下一个,请稍等！',U('Backup/back?id='.$pre.'&fileid='.($fileid+1)));
		}
		else
		{
			alert("数据库恢复成功！",U("Backup/index"));
		}
		
	}
	//下载还原
	public function down()
	{
		$filepath = './Public/Backup/'.$_GET['id'];
		if (file_exists($filepath))
		{
			$filename = $filename ? $filename : basename($filepath);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($filepath);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($filepath);
			exit;
		}
		else
		{
			alert('出错了,没有找到分卷文件！',1);
		}
	}
	//删除分卷文件
	public function del()
	{
		$filename = trim($_GET['id']);
		@unlink('./Public/Backup/'.$filename);
		alert($filename.'已经删除！',U("Backup/restore"));
	}
	//删除所有分卷文件
	public function delall()
	{
		if(empty($_POST['ids']))
		{
			alert("请先选择备份文件!",1);
		}
		foreach($_POST['ids'] as $value)
		{
			@unlink('./Public/Backup/'.$value);
		}
		alert('批量删除分卷文件成功！',U("Backup/restore"));
	}
	//上传备份文件
	public function upload()
	{
		$this->display('upload');
	}
	//执行上传
	public function doupload()
	{
		//处理文件名,获取原始文件名
		$filename = str_replace(".sql","",$_FILES['url']['name']);
		import('@.ORG.UploadFile');
		$upload=new UploadFile();
		$upload->maxSize='2048000';  
		$upload->savePath='./Public/Backup/';
		$upload->saveRule= $filename;
		$upload->uploadReplace = true; 
		$upload->allowExts = array('sql');     //准许上传的文件后缀
		if($upload->upload())
		{
			alert('上传成功!',U("Backup/restore"));
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}
}
?>