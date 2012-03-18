<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ������

    @Filename AdAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 09:59:26 $
*************************************************************/
class AdAction extends CommonAction
{	
    public function index()
    {

		$ad = M('ad');
		$count = $ad->count();
		import('@.ORG.Page');
		isset($_GET['type']) ? $map['type'] = $_GET['type'] : $map['type'] = array('neq',10);
		isset($_GET['status']) ? $map['status'] = $_GET['status'] :$map['status'] = array('neq',2);
		$count = $ad->where($map)->count();
		$p = new Page($count,20);
		$p->setConfig('prev','��һҳ'); 
		$p->setConfig('header','����¼');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>����¼ 20��/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$list=$ad->where($map)->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$list);
		$this->display();	
    }
	
	public function add()
    {
		$this->display('add');
    }
	
	public function edit()
    {
		$type = M('ad');
		$list = $type->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
		$this->display('edit');
    }
	
	public function doedit()
    {
		$ad = M('ad');
		$data['id'] = $_POST['id'];
		$data['title'] = $_POST['title'];
		$data['type'] = $_POST['type'];
		$data['description'] = $_POST['description'];
		//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = stripslashes($_POST['content']);
		$data['addtime'] = date('Y-m-d H:i:s');
		if($ad->save($data))
		{
			alert('�����ɹ�!',U('Ad/index'));
		}
       alert('����ʧ��!',1);
    }
	
	public function doadd()
    {
		$ad = M('ad');
		$data['title'] = $_POST['title'];
		$data['type'] = $_POST['type'];
		$data['description'] = $_POST['description'];
		$data['content'] = stripslashes($_POST['content']);
		$data['addtime'] = date('Y-m-d H:i:s');
		if($ad->add($data))
		{
			alert('�����ɹ�!',U('Ad/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('ad');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Ad/index'));
		}
		alert('����ʧ��!',1);	
    }
	
	public function status(){
		$a = M('ad');
		if($_GET['status'] == 0)
		{
			$a->where( 'id='.$_GET['id'] )-> setField( 'status',1); 
		}
		else
		{
			$a->where( 'id='.$_GET['id'] )-> setField( 'status',0); 
		}
		$this->redirect('index');
	}

	
	public function delall()
	{
		$id = $_REQUEST['id'];  //��ȡ����id
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id) ? $ids : $id;
		$map['id'] = array('in',$id); 
		if(!$id)
		{
			alert('�빴ѡ���!',1);
		}
		$ad = M('ad');
		if($_REQUEST['Del'] == '��ʾ')
		{ 
			$data['status'] = 1;
			if($ad->where($map)->save($data))
			{
			alert('�����ɹ�!',U('Ad/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '����')
		{ 
			$data['status'] = 0;
			if($ad->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Ad/index'));
			}
			alert('����ʧ��!',1);
		}
	}
}
?>