<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ��ǩ����

    @Filename LabelAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:25:56 $
*************************************************************/
class LabelAction extends CommonAction
{
    public function index()
    {
		$label = M('label');
		$list = $label->order('addtime desc')->select();
		$this->assign('list',$list);
		$this->display('index');
    }
//��ӱ�ǩ
	public function add()
    {
        $this->display('add');
    }
//ִ�����
	public function doadd()
    {
		$label = M('label');
		$data['title'] = $_POST['title'];
	//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = stripslashes($_POST['content']);
		$data['addtime'] = date('Y-m-d H:i:s');
		if($label->add($data))
		{
			alert('�����ɹ�!',U('Label/index'));
		}
		alert('����ʧ��!',1);
    }

	public function edit()
    {
		$label = M('label');
		$list = $label->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
        $this->display();
    }
	
	public function doedit()
    {
		$label = M('label');
		$data['id'] = $_POST['id'];
		$data['title'] = $_POST['title'];
		$data['content'] = stripslashes($_POST['content']);
		$data['addtime'] = date('Y-m-d H:i:s');
		if($label->save($data))
		{
			alert('�����ɹ�!',U('Label/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('label');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Label/index'));
		}
		alert('����ʧ��!',1);
    }

		public function status()
	{
		$status=M('label');
		if($_GET['status'] == 0)
		{
			$status->where( 'id='.$_GET['id'] )-> setField( 'status',1); 
		}
		elseif($_GET['status'] == 1)
		{
			$status->where( 'id='.$_GET['id'] )-> setField( 'status',0); 
		}
		else
		{
			alert('�Ƿ�����!',3);
		}
		$this->redirect('index');
	}
}
?>