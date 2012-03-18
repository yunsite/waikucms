<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 友情链接管理

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
		$p->setConfig('prev','上一页'); 
		$p->setConfig('header','条记录');
		$p->setConfig('first','首 页');
		$p->setConfig('last','末 页');
		$p->setConfig('next','下一页');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>共<font color='#009900'><b>%totalRow%</b></font>条记录 20条/每页</span></li>");
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
			alert('操作成功!',U('Link/index'));
		}
		alert('操作失败!',1);
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
			alert('操作成功!',U('Link/index'));
		}
		alert('操作失败!',1);
    }
	
	public function del()
    {
		$type = M('link');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('操作成功!',U('Link/index'));
		}
		alert('操作失败!',1);	
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
			alert('非法操作!',3);
		}
		$this->redirect('index');
	}

	
	public function delall()
	{
		$id = $_REQUEST['id'];  //获取id
		$ids = implode(',',$id);//批量获取id
		$id = is_array($id)?$ids:$id;
		$map['id'] = array('in',$id); 
		$link = M('link');
		
		if($_REQUEST['Del'] == '编辑')
		{ 
			for($i = 0;$i < count($_REQUEST['linkid']);$i++)
			{
				$data['title'] = $_REQUEST['title'][$i];
				$data['url'] = $_REQUEST['url'][$i];
				$data['rank'] = $_REQUEST['rank'][$i];
				$link->where('id='.$_REQUEST['linkid'][$i])->save($data);
			}
			alert('操作成功!',U('Link/index'));
		}
		
		if(!$id)
		{
			alert('请先勾选记录!',1);
		}
		
		
		if($_REQUEST['Del'] == '删除') 
		{ 
			if($link->where($map)->delete())
			{
				alert('操作成功!',U('Link/index'));
			}
		}
		
		if($_REQUEST['Del'] == '批量显示') 
		{ 
			$data['status'] = 1;
			if($link->where($map)->save($data))
			{
				alert('操作成功!',U('Link/index'));
				
			}
		}
		
		if($_REQUEST['Del'] == '批量隐藏')
		{ 
			$data['status']=0;
			if($link->where($map)->save($data))
			{
				alert('操作成功!',U('Link/index'));
			}
		}
	}
}
?>