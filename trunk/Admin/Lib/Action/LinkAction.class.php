<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function �������ӹ���

    @Filename LinkAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:26:03 $
*************************************************************/
class LinkAction extends CommonAction
{	
    public function index()
    {
		$link = M('link');
		$count = $link->count();
		import('@.ORG.Page');
		$p = new Page($count,20);
		$p->setConfig('prev','��һҳ'); 
		$p->setConfig('header','����¼');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>����¼ 20��/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$list = $link->order('rank asc')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$list);
		$this->display();
    }
	
	  public function add()
    {
		$this->display('add');
    }
	
	 public function edit()
    {
		$link = M('link');
		$list = $link->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
		$this->display();
    }
	
	public function doedit()
    {
		$data['id'] = $_POST['id'];
		$data['title'] = $_POST['title'];
		$data['url'] = $_POST['url'];
		$data['rank'] = $_POST['rank'];
		$data['logo'] = $_POST['logo'];
		$data['islogo'] = $_POST['islogo'];
		$link = M('link');
		if($link->data($data)->save())
		{
			alert('�����ɹ�!',U('Link/index'));
		}
		alert('����ʧ��!',1);
    }
	public function doadd()
    {
		$data['title'] = $_POST['title'];
		$data['url'] = $_POST['url'];
		$data['rank'] = $_POST['rank'];
		$data['logo'] = $_POST['logo'];
		$data['islogo'] = $_POST['islogo'];
		$data['status'] = 1;
		$link = M('link');
		if($link->data($data)->add())
		{
			alert('�����ɹ�!',U('Link/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('link');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Link/index'));
		}
		alert('����ʧ��!',1);	
    }
	
	public function status(){
		$link = M('link');
		if($_GET['status'] == 0)
		{
			$link->where( 'id='.$_GET['id'] )-> setField( 'status',1); 
		}
		elseif($_GET['status']==1)
		{
			$link->where( 'id='.$_GET['id'] )-> setField( 'status',0); 
		}
		else
		{
			alert('�Ƿ�����!',3);
		}
		$this->redirect('index');
	}

	
	public function delall()
	{
		$id = $_REQUEST['id'];  //��ȡid
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id)?$ids:$id;
		$map['id'] = array('in',$id); 
		$link = M('link');
		
		if($_REQUEST['Del'] == '�༭')
		{ 
			for($i = 0;$i < count($_REQUEST['linkid']);$i++)
			{
				$data['title'] = $_REQUEST['title'][$i];
				$data['url'] = $_REQUEST['url'][$i];
				$data['rank'] = $_REQUEST['rank'][$i];
				$link->where('id='.$_REQUEST['linkid'][$i])->save($data);
			}
			alert('�����ɹ�!',U('Link/index'));
		}
		
		if(!$id)
		{
			alert('���ȹ�ѡ��¼!',1);
		}
		
		
		if($_REQUEST['Del'] == 'ɾ��') 
		{ 
			if($link->where($map)->delete())
			{
				alert('�����ɹ�!',U('Link/index'));
			}
		}
		
		if($_REQUEST['Del'] == '������ʾ') 
		{ 
			$data['status'] = 1;
			if($link->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Link/index'));
				
			}
		}
		
		if($_REQUEST['Del'] == '��������')
		{ 
			$data['status']=0;
			if($link->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Link/index'));
			}
		}
	}
}
?>