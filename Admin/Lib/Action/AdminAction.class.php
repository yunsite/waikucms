<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 管理员管理

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
	//添加管理员
	public function add()
    {
		if($_SESSION['authId'] <> 1)
		{
			alert('无权操作!',1);
		}
        $this->display();
    }
	//执行添加
	public function doadd()
    {
		if($_SESSION['authId'] <> 1)
		{
			alert('无权操作!',1);
		}
		$admin = M('admin');
		$data['username'] = trim($_POST['username']);
		if($admin->where('username=\''.$_POST['username'].'\'')->find())
		{
			alert('用户名已存在!',1);
		}
		if(empty($_POST['password']))
		{
			alert('密码不能为空!',1);
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
			alert('操作成功!密码为:'.$_POST['password'],U('Admin/index'));
		}
		alert('操作失败!',1);
    }

	//修改管理员
	public function edit()
    {
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert('无权操作!',1);
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
				alert('无权操作!',1);
			}
		}
		if(empty($_POST['username']))
		{
			alert('用户名不能为空!',1);
		}
		$admin = M('admin');
		$data['username'] = trim($_POST['username']);
		$data['id'] = $_POST['id'];
		$data['password'] = md5('wk' . trim($_POST['password']) . 'cms');
		if($admin->save($data))
		{
			alert('操作成功! 新密码:'.$_POST['password'],U('Admin/index'));
		}
		else
		{
			alert('操作失败!',1);
		}
    }
	
	public function del()
    {
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert("无权操作!",1);
			}
		}
		if($_GET['id'] == $_SESSION['authId'])
		{
			alert('不能删除自己!',1);
		}
		$type = M('admin');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('操作成功!',U('Admin/index'));
		}
		else
		{
			alert('操作失败!',1);
		}
    }
	
	public function status()
	{
		if($_SESSION['authId'] <> 1)
		{
			if($_SESSION['authId'] <> $_GET['id'])
			{
				alert('无权操作!',1);
			}
		}
		
		if($_GET['id'] == $_SESSION['authId'])
		{
			alert('不能禁用自己!',1);
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
			alert('非法操作',1);
		}
		$this->redirect('index');
	}
}
?>