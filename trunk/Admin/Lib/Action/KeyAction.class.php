<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function �ؼ��ֹ���

    @Filename KeyAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:20:24 $
*************************************************************/
class KeyAction extends CommonAction
{	
    public function index()
    {
		$key = M('key');
		$count = $key->count();
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
		$list = $key->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$list);
		$this->display('index');	
    }
	
	public function add()
    {
        $this->display('add');
    }
	
	public function doadd()
    {
		$key = M('key');
		$key->create(); 
		if($key->add())
		{
			alert('�����ɹ�!',U('Key/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function edit()
    {
		$key = M('key');
		$list = $key->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
        $this->display();
    }
	
	public function doedit()
    {
		$key = M('key');
		$key->create();
		if($key->save())
		{
			alert('�����ɹ�!',U('Key/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('key');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Key/index'));
			
		}
		alert('����ʧ��!',1);
		
    }
	
	public function delall()
	{
		$id = $_REQUEST['id'];  //��ȡid
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id)?$ids:$id;
		$map['id'] = array('in',$id); 
		$key = M('key');
		if($_REQUEST['Del'] == '�༭')
		{ 
			for($i = 0;$i < count($_REQUEST['keyid']);$i++)
			{
				$data['url'] = $_REQUEST['url'][$i];
				$key->where('id='.$_REQUEST['keyid'][$i])->save($data);
			}
			alert('�����ɹ�!',U('Key/index'));
		}
		
		if(!$id)
		{
			alert('�빴ѡ��¼!',1);
		}
		
		if($_REQUEST['Del'] == 'ɾ��')
		{ 
			if($key->where($map)->delete())
			{
				alert('�����ɹ�!',U('Key/index'));
			}
		alert('����ʧ��!',1);
		}
	}
	public function search()
	{
		$key = M('key');
		$count = $key->count();
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
		$map['title'] = array('like','%'.$_POST['keywords'].'%'); 
		$list = $key->where($map)->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$list);
		$this->display('index');	
	}
}
?>