<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function ���۹���

    @Filename PlAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 15:13:19 $
*************************************************************/
class PlAction extends CommonAction
{	
    public function index()
    {
		import('@.ORG.Page');
		R("Article","urlmode");
		$pl = M('pl');
		if(isset($_GET['status']))
		{
			$count = $pl->where('status='.$_GET['status'])->order('ptime desc')->count();
			$p = new Page($count,20);
			$list = $pl->where('status='.$_GET['status'])->order('ptime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		else
		{
			$count = $pl->order('ptime desc')->count();
			$p = new Page($count,20); 
			$list = $pl->order('ptime desc')->limit($p->firstRow.','.$p->listRows)->select();
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
		$this->display();
    }
	
	 public function edit()
    {
		$type = M('pl');
		$list = $type->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
		$this->display();
    }
	
	public function doedit()
    {
		$pl=M('pl');
		$data['id'] = $_POST['id'];
	//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = stripslashes($_POST['content']);
		$data['recontent'] = stripslashes($_POST['recontent']);
		if($pl->save($data))
		{
			alert('�����ɹ�!',U('Pl/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('pl');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('ɾ���ɹ�!',U('Pl/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function status(){
		$pl = M('pl');
		if($_GET['status'] == 0)
		{
			$pl->where( 'id='.$_GET['id'] )-> setField( 'status',1);
		}
		elseif($_GET['status'] == 1)
		{
			$pl->where( 'id='.$_GET['id'] )-> setField( 'status',0);
		}else{
			alert('�Ƿ�����!',3);
		}
		$this->redirect('index');
	}


	public function delall(){
		$id = $_REQUEST['id'];  //��ȡ����id
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id) ? $ids : $id;
		$map['id'] = array('in',$id);
		if(!$id)
		{
			alert('���ȹ�ѡ��¼!',1);
		}
		$pl = M(pl);
		if($_REQUEST['Del'] == 'ɾ��')
		{
			if($pl->where($map)->delete())
			{
				alert('�����ɹ�!',U('Pl/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '����δ��')
		{
			$data['status'] = 0;
			if($pl->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Pl/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '�������')
		{
			$data['status'] = 1;
			if($pl->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Pl/index'));
			}
				alert('����ʧ��!',1);
		}
	}

	public function search()
	{
		import('@.ORG.Page');
		R("Article","urlmode");
		$pl = M('pl');
		$map['content'] = array('like','%'.$_POST['keywords'].'%');
		$count = $pl->where($map)->order('ptime desc')->count();
		$p = new Page($count,20); 
		$list = $pl->where($map)->order('ptime desc')->limit($p->firstRow.','.$p->listRows)->select();
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