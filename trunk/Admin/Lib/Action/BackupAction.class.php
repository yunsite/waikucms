<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function:���ݿⱸ���뻹ԭ

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
			alert('��ѡ����Ҫ���ݵ����ݿ��',1);
		}
		$filesize = intval($_POST['filesize']);
		if ($filesize < 512) 
		{
			alert('������,��Ϊ�־��С����һ������512������ֵ��',1);
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
		alert('���ݿ�־��������,���ֳ�'.$p.'��sql�ļ���ţ�',U("Backup/restore"));
	}
	//����SQL�������
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
	//չʾ��ԭ
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
			alert('û�м�⵽�����ļ�,���ȱ��ݻ��ϴ������ļ���./Public/Backup/',U("Backup/index"));
		}
    }
	//���뻹ԭ
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
			alert('��'.$fileid.'�������ļ��ָ��ɹ�,׼���ָ���һ��,���Եȣ�',U('Backup/back?id='.$pre.'&fileid='.($fileid+1)));
		}
		else
		{
			alert("���ݿ�ָ��ɹ���",U("Backup/index"));
		}
		
	}
	//���ػ�ԭ
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
			alert('������,û���ҵ��־��ļ���',1);
		}
	}
	//ɾ���־��ļ�
	public function del()
	{
		$filename = trim($_GET['id']);
		@unlink('./Public/Backup/'.$filename);
		alert($filename.'�Ѿ�ɾ����',U("Backup/restore"));
	}
	//ɾ�����з־��ļ�
	public function delall()
	{
		if(empty($_POST['ids']))
		{
			alert("����ѡ�񱸷��ļ�!",1);
		}
		foreach($_POST['ids'] as $value)
		{
			@unlink('./Public/Backup/'.$value);
		}
		alert('����ɾ���־��ļ��ɹ���',U("Backup/restore"));
	}
	//�ϴ������ļ�
	public function upload()
	{
		$this->display('upload');
	}
	//ִ���ϴ�
	public function doupload()
	{
		//�����ļ���,��ȡԭʼ�ļ���
		$filename = str_replace(".sql","",$_FILES['url']['name']);
		import('@.ORG.UploadFile');
		$upload=new UploadFile();
		$upload->maxSize='2048000';  
		$upload->savePath='./Public/Backup/';
		$upload->saveRule= $filename;
		$upload->uploadReplace = true; 
		$upload->allowExts = array('sql');     //׼���ϴ����ļ���׺
		if($upload->upload())
		{
			alert('�ϴ��ɹ�!',U("Backup/restore"));
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}
}
?>