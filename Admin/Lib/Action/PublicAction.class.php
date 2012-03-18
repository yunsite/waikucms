<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function 认证模块

    @Filename PublicAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 15:14:43 $
*************************************************************/
class PublicAction extends Action 
{	
	public function index()
	{
		//dump($_SESSION);
	//如果通过认证跳转到首页
		redirect(__APP__);
	}
	
    public function login()
	{
		$this->display('login');
	}
	
	function checkLogin()
	{
		if(empty($_POST['username']))
		{
			alert("帐号错误",1);
		}
		elseif (empty($_POST['password']))
		{
			alert("密码必须!",1);
		}
		elseif (empty($_POST['verify']))
		{
			alert('验证码必须!',1);
		}
		if(md5($_POST['verify']) != $_SESSION['verify'])
		{
			alert('验证码错误!',1);
		}
		
        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['username']	= trim($_POST['username']);
		$map["status"]	= array('gt',0);
		import ('@.ORG.RBAC' );
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo)
		{
			alert('帐号不存在!',1);
        }
		if(empty($authInfo))
		{
			alert('帐号不存在或已禁用!',1);
		}
		$pwdinfo = strcmp($authInfo['password'],md5('wk'.trim($_POST['password']).'cms'));
		if($pwdinfo <> 0)
		{
			alert('密码错误!',1);
        }
        $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
		$_SESSION['username']		=	$_POST['username'];
		$_SESSION['cookietime'] = time();
		$role = M('role_admin');
		$authInfo['role_id'] = $role->where('user_id='.$authInfo['id'])->getField('role_id');
        if($authInfo['role_id'] == '1')
		{
            $_SESSION['administrator']		=	true;
        }
		//保存登录信息
		$admin	=	M('admin');
		$ip		=	get_client_ip();
		$time	=	time();
        $data = array();
		$data['id']	=	$authInfo['id'];
		$data['lastlogintime']	=	$time;
		$data['lastloginip']	=	$ip;
		$admin->save($data);
		// 缓存访问权限
        RBAC::saveAccessList();
		//保存cookie信息
		Cookie::set($_SESSION['cookietime'],'1',60*60*3);
		//dump($_SESSION);
		$this->index();
	}
	
	function loginout()
	{
		if(isset($_SESSION[C('USER_AUTH_KEY')]))
		{
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
			alert('登出成功!',U('Public/login'));
        }
		alert('已经登出!',U('Public/login'));
	}
	
	public function verify()
	{
		import('@.ORG.Image');
		Image::buildImageVerify(5,1,gif,78,18,'verify');	
	}
}
?>