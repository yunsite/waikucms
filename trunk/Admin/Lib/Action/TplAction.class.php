<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ģ�����

    @Filename TplAction.class.php $

    @Author pengyong $

    @Date 2011-12-12 20:10:23 $
*************************************************************/
class TplAction extends CommonAction
{	
		public function index()
		{
			$dirpath = $this->dirpath();//��ǰĿ¼
			$dirlast = $this->dirlast();//��һ��Ŀ¼
			import("@.ORG.Dir");
			$dir = new Dir($dirpath);
			$list_dir = $dir->toArray();
			if (empty($list_dir))
			{
				alert('���ļ�������û���ļ���',1);
			}
			foreach($list_dir as $key=>$value)
			{
				$list_dir[$key]['pathfile'] = wk_url_repalce($value['path'],'desc').'|'.$value['filename'];
			}
			$_SESSION['tpl_jumpurl'] = '?s=Tpl/index/id/'.wk_url_repalce($dirpath,'desc');
			if($dirlast && $dirlast != '.')
			{
				$this->assign('dirlast',wk_url_repalce($dirlast,'desc'));
			}
			$this->assign('dirpath',$dirpath);
			$this->assign('list_dir',list_sort_by($list_dir,'mtime','desc'));
			$this->display('index');
		}
	//��ȡģ�嵱ǰ·��
	public function dirpath()
	{
		$id = wk_url_repalce(trim($_GET['id']));
		if ($id) 
		{
			$dirpath = $id;
		}
		else
		{
			$dirpath ='./Web/Tpl';
		}
		if (!strpos($dirpath,'Tpl')) 
		{
			alert("����ģ���ļ��з�Χ�ڣ�",1);
		}
		return $dirpath;
	}
	//��ȡģ����һ��·��
	public function dirlast()
	{
		$id = wk_url_repalce(trim($_GET['id']));
		if ($id) 
		{
			return substr($id,0,strrpos($id, '/'));
		}
		else
		{
			return false;
		}
	}
	// �༭ģ��
	public function add()
	{
		$filename = wk_url_repalce(str_replace('*','.',trim($_GET['id'])));
		if (empty($filename)) 
		{
			alert('ģ�����Ʋ���Ϊ�գ�',1);
		}
		$content = read_file($filename);
		$this->assign('filename',$filename);
		$this->assign('content',htmlspecialchars($content));
		$this->display('add');
	}
	// ����ģ��
	public function update()
	{
		$filename = trim($_POST['filename']);
		$content = stripslashes(htmlspecialchars_decode($_POST['content']));
		if (!testwrite(substr($filename,0,strrpos($filename,'/'))))
		{
			$this->error('���߱༭ģ����Ҫ��'.__ROOT__."/Web/Tpl".'���д��Ȩ�ޣ�');
		}
		if (empty($filename))
		{
			$this->error('ģ���ļ�������Ϊ�գ�');
		}
		if (empty($content))
		{
			$this->error('ģ�����ݲ���Ϊ�գ�');
		}
		write_file($filename,$content);
		if (!empty($_SESSION['tpl_jumpurl']))
		{
			$this->assign("jumpUrl",$_SESSION['tpl_jumpurl']);
		}
		else
		{
			$this->assign("jumpUrl",'?s=Tpl/index');
		}
		$this->success('��ϲ����ģ����³ɹ���');
	}
	// ɾ��ģ��
    public function del()
	{
		$id = wk_url_repalce(str_replace('*','.',trim($_GET['id'])));
		if (!substr(sprintf("%o",fileperms($id)),-3))
		{
			$this->error('��ɾ��Ȩ�ޣ�');
		}
		@unlink($id);
		if (!empty($_SESSION['tpl_jumpurl']))
		{
			$this->assign("jumpUrl",$_SESSION['tpl_jumpurl']);
		}
		else
		{
			$this->assign("jumpUrl",'?s=Tpl/index');
		}
		$this->success('ɾ���ļ��ɹ���');
    }
	
	
}
?>