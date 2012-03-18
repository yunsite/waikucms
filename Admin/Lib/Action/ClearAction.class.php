<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function �������뻺��

    @Filename ClearAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 14:56:44 $
*************************************************************/
class ClearAction extends CommonAction
{	
	//�������
	public function index()
	{
		$this->display('index');
	}
	//****����õƸ���*****
	public function clearhd()
	{
		//ͳ�Ƹ���
		$path = './Public/Uploads/hd/';
		$fso = opendir($path);
		if(!$fso)
		{
			$this->error('ϵͳ�õ��ļ��в�����!');
			
		}
		while($flist = readdir($fso))
		{
			$data[] = $flist;
		}
		closedir($fso);
		$data = array_slice($data,2);
		
		//��ȡ���ݿ⸽������
		$a = M('flash');
		$list = $a->field('pic')->select();
		if(!empty($list))
		{
			foreach($list as $k=>$v)
			{
				$pic[] = $v['pic'];
			}
			//ȡ�ò����
			$dpic = array_diff($data,$pic);
		}
		else
		{
			$dpic = $data;
		}
		
		if(empty($dpic))
		{
			$msg = '��ϲ,ϵͳ�޻õ������ļ�!';
			$this->assign('waitSecond',10); 
			$this->success($msg);
			
		}
		$i = 0;
		$j = 0;
		$p = '';
		//ִ������
		foreach($dpic  as $v)
		{
			$ipath = $path.$v;
			$result	=@unlink($ipath);
			if ($result	==	true)
			{
				$i++;//ͳ�Ƴɹ�ɾ�����ļ�����
			}
			else
			{
				$j++;//ͳ��δɾ�����ļ�����
				$p.=$v.',&nbsp;&nbsp;';
			}
		}
		$msg = 'ϵͳ���������õƸ���'.($i+$j)."��<br>�ɹ�������{$i}���ļ�";
		if($j > 0)
		{
			$msg.="������{$j}���ļ���������:<br>".$p;
		}
		$this->assign('waitSecond',10); 
		$this->success($msg);
	}
	
	//****������������****
	public function clearlink()
	{
		//ͳ�Ƹ���
		$path = './Public/Uploads/link/';
		$fso = opendir($path);
		if(!$fso)
		{
			$this->error('ϵͳ���������ļ��в�����!');
			
		}
		while($flist = readdir($fso))
		{
			$data[] = $flist;
		}
		closedir($fso);
		$data = array_slice($data,2);
		
		//��ȡ���ݿ⸽������
		$a = M('link');
		$list = $a->field('logo')->select();
		
		if(!empty($list))
		{
			foreach($list as $k=>$v)
			{
				$pic[] = $v['logo'];
			}
		//ȡ�ò����
			$dpic = array_diff($data,$pic);
		}else{
			$dpic = $data;
		}
		if(empty($dpic))
		{
			$msg = '��ϲ,ϵͳ�����������ļ�!';
			$this->assign("waitSecond",10); 
			$this->success($msg);
			
		}
		$i = 0;
		$j = 0;
		$p = '';
		//ִ������
		foreach($dpic  as $v)
		{
			$ipath = $path.$v;
			$result =@unlink($ipath);
	
			if ($result == true)
			{
			$i++;//ͳ�Ƴɹ�ɾ�����ļ�����
			}
			else
			{
			$j++;//ͳ��δɾ�����ļ�����
			$p.=$v.',&nbsp;&nbsp;';
			}
		}
		$msg = 'ϵͳ����������������'.($i + $j)."��<br>�ɹ�������{$i}���ļ�";
		if($j > 0)
		{
			$msg.="������{$j}���ļ���������:<br>".$p;
		}
		$this->assign('waitSecond',10); 
		$this->success($msg);
	}
	
	//****����LOGO����****
	public function clearlogo()
	{
		//ͳ�Ƹ���
		$path = './Public/Uploads/logo/';
		$fso = opendir($path);
		if(!$fso)
		{
		$this->error('ϵͳLOGO�ļ��в�����!');
		
		}
		while($flist = readdir($fso))
		{
			$data[] = $flist;
		}
		closedir($fso);
		$data = array_slice($data,2);
		//��ȡ���ݿ⸽������
		$a = M('config');
		$logo = $a->where('id=1')->getField('sitelogo');
		$watermarkimg = $a->where('id=1')->getField('watermarkimg');
		if(!empty($logo) || !empty($watermarkimg))
		{
			if(!empty($logo)) $pic[] = $logo;
			if(!empty($watermarkimg)) $pic[] = $watermarkimg;
			//ȡ�ò����
			$dpic = array_diff($data,$pic);
		}
		else
		{
			$dpic = $data;
		}
		if(empty($dpic))
		{
			$msg='��ϲ,ϵͳ��logo�����ļ�!';
			$this->assign('waitSecond',10); 
			$this->success($msg);
			
		}
		$i = 0;
		$j = 0;
		$p = '';
		//ִ������
		foreach($dpic  as $v)
		{
			$ipath = $path.$v;
			$result =@unlink($ipath);
	
			if($result)
			{
				$i++;//ͳ�Ƴɹ�ɾ�����ļ�����
			}
			else
			{
				$j++;//ͳ��δɾ�����ļ�����
				$p.=$v.',&nbsp;&nbsp;';
			}
		}
		
		$msg = 'ϵͳ��������logo����'.($i+$j)."��<br>�ɹ�������{$i}���ļ�";
		if($j > 0)
		{
			$msg.="������{$j}���ļ���������:<br>".$p;
		}
		$this->assign('waitSecond',10); 
		$this->success($msg);
	}
	
	//****����ϵͳ����****
	public function clearcache()
	{
		//����·��
		$Webpath = './Web/Runtime/';
		$Adminpath = './Admin/Runtime/';
		if(is_dir($Webpath))
		{
			@deldir($Webpath);
		}
		if(is_dir($Adminpath))
		{
			@deldir($Adminpath);
		}
		$msg = 'ϵͳ�����������!';
		$this->assign('waitSecond',10); 
		$this->assign('jumpUrl',U('Index/main')); 
		$this->success($msg);
	}
}
?>