<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ����Ա����

    @Filename AdminAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:38:17 $
*************************************************************/
class AdminAction extends CommonAction
{	
    public function index()
    {
		$admin = M('admin');
		if($_SESSION['authId'] == 1)
		{
			$list = $admin->field('id,lastlogintime,lastloginip,username,status')->select();
		}
		else
		{
			$list = $admin->where('id='.$_SESSION['authId'])->field('id,lastlogintime,lastloginip,username,status')->select();
		}
		$this->assign('list',$list);
		$this->display('index');
    }
	//��ӹ���Ա
	public function add()
    {
		if($_SESSION['authId'] <> 1)
		{
			alert('��Ȩ����!',1);
		}
        $this->display();
    }
	//ִ�����
	public function doadd()
    {
		if($_SESSION['authId'] <> 1)
		{
			alert('��Ȩ����!',1);
		}
		$admin = M('admin');
		$data['username'] = trim($_POST['username']);
		if($admin->where('username=\''.$_POST['username'].'\'')->find())
		{
			alert('�û����Ѵ���!',1);
		}
		if(empty($_POST['password']))
		{
			alert('���벻��Ϊ��!',1);
		}
		$data['lastlogintime'] = time();
		$data['lastloginip'] = get_client_ip();
		$data['password'] = md5('wk'.trim($_POST['password']).'cms');
		$role = M('role_admin');
		if($admin->add($data))
		{
			$map['user_id'] = $admin->where('username=\''.$_POST['username'].'\'')->getField('id');
			$map['role_id'] = 1;
			$role->add($map);
			alert('�����ɹ�!����Ϊ:'.$_POST['password'],U('Admin/index'));
		}
		alert('����ʧ��!',1);
    }

	//�޸Ĺ���Ա
	public function edit()
    {
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert('��Ȩ����!',1);
			}
		}
		$admin = M('admin');
		$list = $admin->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
        $this->display();
    }
	
	public function doedit()
    {
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_POST['id'])
			{
				alert('��Ȩ����!',1);
			}
		}
		if(empty($_POST['username']))
		{
			alert('�û�������Ϊ��!',1);
		}
		$admin = M('admin');
		$data['username'] = trim($_POST['username']);
		$data['id'] = $_POST['id'];
		$data['password'] = md5('wk' . trim($_POST['password']) . 'cms');
		if($admin->save($data))
		{
			alert('�����ɹ�! ������:'.$_POST['password'],U('Admin/index'));
		}
		else
		{
			alert('����ʧ��!',1);
		}
    }
	
	public function del()
    {
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert("��Ȩ����!",1);
			}
		}
		if($_GET['id'] == $_SESSION['authId'])
		{
			alert('����ɾ���Լ�!',1);
		}
		$type = M('admin');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Admin/index'));
		}
		else
		{
			alert('����ʧ��!',1);
		}
    }
	
	public function status()
	{
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert('��Ȩ����!',1);
			}
		}
		
		if($_GET['id'] == $_SESSION['authId'])
		{
			alert('���ܽ����Լ�!',1);
		}
		
		$a = M('admin');
		
		if($_GET['status'] == 0)
		{
			$a->where( 'id='.$_GET['id'] )-> setField( 'status',1);
		}
		elseif($_GET['status'] == 1)
		{
			$a->where( 'id='.$_GET['id'] )-> setField( 'status',0);
		}
		else
		{
			alert('�Ƿ�����',1);
		}
		$this->redirect('index');
	}
}
?>