<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ǰ̨���� Action

    @Filename PlAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 21:30:58 $
*************************************************************/
class PlAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',__APP__);
	} 
	
	public function update()
	{
	//���gb2312��,ajaxĬ��ת����utf-8
		header("Content-type: text/html; charset=gb2312");
		if(!isset($_POST['aid']) or !isset($_POST['author']) or !isset($_POST['content']))
		{
			alert('�Ƿ�����!',__APP__);
		}
	//��ȡ���ݿ�ͻ���
		$pl = M('pl');
		$config = F('basic','','./Web/Conf/');
		$data['ip'] = get_client_ip();
	//�Ƚ���js��escape
		$data['author'] = htmlspecialchars(unescape($_POST['author']));
	//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = htmlspecialchars(trim(stripslashes(unescape($_POST['content']))));
		$data['ptime'] = date('Y-m-d H:i:s');
		$data['aid'] = htmlspecialchars($_POST['aid']);
		
		if(Session::is_set('pltime'))
		{
			$temp=Session::get('pltime') + $config['postovertime'];
			if(time() < $temp)
			{
				echo "�벻Ҫ��������!";
				exit;
			}
		}
		if($config['pingoff'] == 0) $data['status'] = 0;
		if($pl->add($data))
		{
			Session::set('pltime', time());
			if($config['pingoff'] == 0)
			{
				echo "�����ɹ�,������Ҫ����Ա���!";
				exit;
			}
			else
			{
				echo "�����ɹ�!";
				exit;
			}
		}
		else
		{
			echo "����ʧ��!";
			exit;
		}
	}
	
	public function index()
	{
		inject_check($_GET['aid']);
		inject_check($_GET['page']);
		$pl = M('pl');
		$config = F('basic','','./Web/Conf/');
		$data['status'] = 1;
		$data['aid'] = intval($_GET['aid']);
		$list = $pl->where($data)->select();
		if(!$list)
		{
			$this->display(TMPL_PATH.$config['sitetpl'].'/pl_nopl.html','gb2312','text/xml');
			exit;
		}
		$count = $pl->where($data)->count();
		$this->assign('allnum',$count);
		$pagenum = 6;//ÿ������ҳ
		$pages = ceil($count / $pagenum);//��ҳ��
		$prepage = ($_GET['page']-1) * $pagenum;
		$endpage = $_GET['page'] * $pagenum;
		$tempnum = $pagenum * $_GET['page'];
		$lastnum = ($tempnum < $count) ? $tempnum : $count;
		$plist = $pl->where($data)->order('ptime asc')->limit($prepage.','.$endpage)->select();
		foreach($plist as $k=>$v)
		{
			if(!empty($v['recontent']))
			{
				$v['recontent'] = '<font color=red><b>����Ա�ظ���'.$v['recontent'].'</b></font>';
			}
			$pp[$k] = $v;
			$pp[$k]['num']= $k + 1 + (intval($_GET['page'])-1) * $pagenum;
		}
	//��װ����
		$this->assign('nowpage',intval($_GET['page']));//��ǰҳ
		$this->assign('pages',$pages);//��ҳ��
		$this->assign('aid',intval($_GET['aid']));//����aid
		$this->assign('lastnum',$lastnum);//���һ����¼��
		$this->assign('list',$pp);
	//ģ�����
		$this->display(TMPL_PATH.$config['sitetpl'].'/pl_pl.html','gb2312','text/xml');
	}
}
?>