<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function ��֤ģ��

    @Filename PublicAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 15:14:43 $
*************************************************************/
class PublicAction extends Action 
{	
	public function index()
	{
		//dump($_SESSION);
	//���ͨ����֤��ת����ҳ
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
			alert("�ʺŴ���",1);
		}
		elseif (empty($_POST['password']))
		{
			alert("�������!",1);
		}
		elseif (empty($_POST['verify']))
		{
			alert('��֤�����!',1);
		}
		if(md5($_POST['verify']) != $_SESSION['verify'])
		{
			alert('��֤�����!',1);
		}
		
        //������֤����
        $map            =   array();
		// ֧��ʹ�ð��ʺŵ�¼
		$map['username']	= trim($_POST['username']);
		$map["status"]	= array('gt',0);
		import ('@.ORG.RBAC' );
        $authInfo = RBAC::authenticate($map);
        //ʹ���û����������״̬�ķ�ʽ������֤
        if(false === $authInfo)
		{
			alert('�ʺŲ�����!',1);
        }
		if(empty($authInfo))
		{
			alert('�ʺŲ����ڻ��ѽ���!',1);
		}
		$pwdinfo = strcmp($authInfo['password'],md5('wk'.trim($_POST['password']).'cms'));
		if($pwdinfo <> 0)
		{
			alert('�������!',1);
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
		//�����¼��Ϣ
		$admin	=	M('admin');
		$ip		=	get_client_ip();
		$time	=	time();
        $data = array();
		$data['id']	=	$authInfo['id'];
		$data['lastlogintime']	=	$time;
		$data['lastloginip']	=	$ip;
		$admin->save($data);
		// �������Ȩ��
        RBAC::saveAccessList();
		//����cookie��Ϣ
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
			alert('�ǳ��ɹ�!',U('Public/login'));
        }
		alert('�Ѿ��ǳ�!',U('Public/login'));
	}
	
	public function verify()
	{
		import('@.ORG.Image');
		Image::buildImageVerify(5,1,gif,78,18,'verify');	
	}
}
?>