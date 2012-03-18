<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ǰ̨ͶƱ	Action

    @Filename VoteAction.class.php $

    @Author pengyong $

    @Date 2011-11-18 11:58:00 $
*************************************************************/
class VoteAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',3);
	} 
	
     public function index()
    {
		if(empty($_GET['id']))
		{
			alert('�Ƿ�����!',3);
		}
		inject_check($_GET['id']);
		//��վͷ��
		R('Public','head');
		$config = F('basic','','./Web/Conf/');
		//��ȡ���ݿ�
		$vote = M('vote');
		$vo = $vote->where('id='.intval($_GET['id']))->find();
		
		//ҵ����
		if(!$vo)
		{
			alert('ͶƱ�����ڻ���ɾ��!',__APP__);
		}
		
		$over='';
		if(!cptime($vo['overtime'],date('Y-m-d H:i:s')))
		{
			$over='[�ѽ���]';
		}
		
		$total = 0;
		$strs = explode("\n",trim($vo['vote']));
		
		for($i = 0;$i < count($strs);$i++)
		{
			$s = explode('=',$strs[$i]);
			$data[$i]['num'] = $s[1];
			$data[$i]['title'] = $s[0];
			$total += $s[1];
		}
		
		foreach($data as $k=>$v)
		{
			$data[$k]['percent'] = round($v['num'] / $total * 100 + 0);
		}
		//��װ����
		$this->assign("vote",$data);
		$this->assign("over",$over);
		$this->assign("votetitle",$vo['title']);
		$this->assign("starttime",$vo['starttime']);
		$this->assign("overtime",$vo['overtime']);
		$this->assign("total",$total);
		//ģ�����
		$this->display(TMPL_PATH.$config['sitetpl'].'/vote.html');
    }
	


	public function update()
	{ 
		if(empty($_POST))
		{
			alert('��ѡ��ͶƱ��!',1);
		}
		inject_check($_POST['id']);
		if(Cookie::is_set('vote'.$_POST['id']))
		{
			alert('����Ͷ��Ʊ��!',1);
		}
		//��ȡ���ݿ�
		$vote=M('vote');
		$vo=$vote->where('id='.intval($_POST['id']))->field('vote,overtime,starttime')->find();
		//ҵ����
		if(!$vo)
		{
			alert('ͶƱ������!',3);
		}
		
		if(cptime(date('Y-m-d H:i:s'),$vo['overtime']))
		{
			alert('ͶƱ�ѽ���!',U('votes/'.$_POST['id']));
		}
		
		if(!cptime(date('Y-m-d H:i:s'),$vo['starttime']))
		{
			alert('ͶƱû�п�ʼ!',U('votes/'.$_POST['id']));
		}
		
		$data['vote'] = $vo['vote'];
		
		if($vo['vtype'] == 0)
		{
			$_POST['vote'] = array($_POST['vote']);
		}
		foreach($_POST['vote'] as $v)
		{
			$s = explode("=",$v);
			$data['vote'] = str_replace($v,$s[0]."=".($s[1] + 1),$data['vote']);
		}
		if($vote->where('id='.intval($_POST['id']))->save($data))
		{
			Cookie::set('vote'.$_POST['id'],'1',365 * 60 * 60 * 24);
			alert('ͶƱ�ɹ�!',U('votes/'.$_POST['id']));
		}
		alert("����ʧ��!",U('votes/'.$_POST['id']));
	}
}
?>