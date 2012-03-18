<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ���Թ���

    @Filename GuestbookAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 15:03:17 $
*************************************************************/
class GuestbookAction extends CommonAction
{	
    public function index()
    {
		import('@.ORG.Page');
		$guestbook = M('guestbook');
		if(isset($_GET['status']))
		{
			$count = $guestbook->where('status='.$_GET['status'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $guestbook->where('status='.$_GET['status'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		else
		{
			$count = $guestbook->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $guestbook->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		
		$p->setConfig('prev','��һҳ');
		$p->setConfig('header','������');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>������ 20��/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display('index');
    }
	
	public function edit()
    {
    $type = M('guestbook');
	$list = $type->where('id='.$_GET['id'])->find();
	$this->assign('list',$list);
    $this->display('edit');
    }
	
	public function doedit()
    {
		$guestbook = M('guestbook');
		$data['id'] = $_POST['id'];
	//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = stripslashes($_POST['content']);
		$data['recontent'] = stripslashes($_POST['recontent']);
		if($guestbook->save($data))
		{
			alert('�����ɹ�!',U('Guestbook/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('guestbook');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Guestbook/index'));
		} 
		alert('����ʧ��!',1);
    }
	
	public function status()
	{
		$a = M('guestbook');
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
			alert('�Ƿ�����!',3);
		}
		$this->redirect('index');
	}

	public function delall()
	{
		$id = $_REQUEST['id'];  //��ȡ����id
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id)?$ids:$id;
		$map['id'] = array('in',$id);
		if(!$id)
		{
			alert('�빴ѡ��¼!',1);
		}
		$guestbook = M('guestbook');
		if($_REQUEST['Del']	==	'ɾ��')
		{
			if($guestbook->where($map)->delete())
			{
				alert('�����ɹ�!',U('Guestbook/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del']	==	'����δ��')
		{
			$data['status'] = 0;
			if($guestbook->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Guestbook/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del']	==	'�������')
		{
			$data['status']=1;
			if($guestbook->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Guestbook/index'));
			}
			alert('����ʧ��!',1);
		}
	}

	public function search()
	{
		import('@.ORG.Page');
		$guestbook = M('guestbook');
		$map['content'] = array('like','%'.$_POST['keywords'].'%');
		$count = $guestbook->where($map)->order('addtime desc')->count();
		$p = new Page($count,20); 
		$list = $guestbook->where($map)->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('prev','��һҳ');
		$p->setConfig('header','������');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>������ 20��/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display('index');
	}
}
?>