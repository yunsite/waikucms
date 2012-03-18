<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function:ͶƱ����ģ��

    @Filename VoteAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:33:32 $
*************************************************************/
class VoteAction extends CommonAction
{	
     public function index()
    {
		import('@.ORG.Page');
		$vote = M('vote');
		$count = $vote->count();
		$p = new Page($count,20); 
		$p->setConfig('prev','��һҳ'); 
		$p->setConfig('header','����¼');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>����¼ 20��/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$list = $vote->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$list);
		$this->display();	
    }
	
	public function add()
    {
        $this->display();
    }
	
	public function doadd()
    {
		$vote=M('vote');
		$vote->create(); 
		if($vote->add())
		{
			alert('�����ɹ�!',U('Vote/index'));
		}
		alert('����ʧ��!',1);	
    }

	   public function edit()
    {
		$vote = M('vote');
		$list = $vote->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
        $this->display();
    }
	
	public function doedit()
    {
		$data['id'] = $_POST['id'];
		$data['vote'] = $_POST['vote'];
		$data['title'] = $_POST['title'];
		$data['starttime'] = $_POST['starttime'];
		$data['overtime'] = $_POST['overtime'];
		$data['rank'] = $_POST['rank'];
		$data['stype'] = $_POST['stype'];
		$vote = M('vote');
		if($vote->save($data))
		{
			alert('�����ɹ�!',U('Vote/index'));
		}
		alert('����ʧ��!',1);
    }
	
	public function del()
    {
		$type = M('vote');
		if($type->where('id='.$_GET['id'])->delete())
		{
			alert('�����ɹ�!',U('Vote/index'));	
		}
		alert('����ʧ��!',1);
    }
	
	public function status()
	{
		$a = M('vote');
		if($_GET['status'] == 0)
		{
			$a->where('id='.$_GET['id'])->setField('status',1); 
		}
		elseif($_GET['status']==1)
		{
			$a->where('id='.$_GET['id'])-> setField('status',0); 
		}
		else
		{
			alert("�Ƿ�����!",3);
		}
		$this->redirect('index');
	}
	
	public function delall()
	{
		$id = $_REQUEST['id'];  //��ȡid
		$ids = implode(',',$id);//������ȡid
		$id = is_array($id) ? $ids : $id;
		$map['id'] = array('in',$id); 
		if(!$id)
		{
			alert('�빴ѡ��¼!',U('Vote/index'));
		}
		
		$vote = M('vote');
		
		if($_REQUEST['Del'] == 'ɾ��') 
		{ 
			if($vote->where($map)->delete())
			{
				alert('�����ɹ�!',U('Vote/index'));
			}
			$this->error('ɾ������ʧ��!');
		}
		
		if($_REQUEST['Del'] == '����')
		{
			$data['status'] = 0;
			if($vote->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Vote/index'));
			}
			$this->error('����ʧ��!');
		}
		
		if($_REQUEST['Del']=='��ʾ')
		{
			$data['status'] = 1;		
			if($vote->where($map)->save($data))
			{
				alert("�����ɹ�!",U('Vote/index'));
			}
			$this->error('����ʧ��!');
		}
	}
	
	public function show()
	{
		$vote = M('vote');
		$vo = $vote->where('id='.$_GET['id'])->find();
		$strs = explode("\n",trim($vo['vote']));
		$total = 0;
		
		for($i = 0;$i < count($strs);$i++)
		{
			$s = explode("=",$strs[$i]);
			$data[$i]['num'] = $s[1];
			$data[$i]['title'] = $s[0];
			$total += $s[1];
		}
		
		foreach($data as $k=>$v)
		{
			$data[$k]['percent'] = round($v['num'] / $total * 100 + 0);
		}
		$this->assign('list',$data);
		$this->display();
	}
}
?>