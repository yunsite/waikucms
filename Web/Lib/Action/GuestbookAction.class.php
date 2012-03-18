<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ����ģ��

    @Filename GuestbookAction.class.php $

    @Author pengyong $

    @Date 2011-11-18 09:01:26 $
*************************************************************/
class GuestbookAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',U('Guestbook/index'));
	} 
	
	public function index()
	{
	//��վͷ��
		R('Public','head');
		$config = F('basic','','./Web/Conf/');
	//����ajaxjs�ĸ�·������
		$url=__ROOT__;
		$this->assign('url',$url);
		$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook.html');
	}
	
	public function update()
	{
	//���gb2312��,ajaxĬ��ת����utf-8
		header("Content-type: text/html; charset=gb2312");
		if(!isset($_POST['author']) or !isset($_POST['content']))
		{
			alert('�Ƿ�����!',3);
		}
	//��ȡ���ݿ�ͻ���
		$pl = M('guestbook');
		$config = F('basic','','./Web/Conf/');
	//����ж�
		if(Session::is_set('posttime'))
		{
			$temp = Session::get('posttime') + $config['postovertime'];
			if(time() < $temp)
			{
				echo "�벻Ҫ��������!";
				exit;
			}
		}
	//׼������
		if($config['bookoff'] == 0) $data['status'] = 0;
	//�Ƚ���js��escape
		$data['author'] = htmlspecialchars(unescape($_POST['author']));
		$data['content'] = htmlspecialchars(trim(unescape($_POST['content'])));
		$data['addtime'] = date('Y-m-d H:i:s');
	//��������
		if($pl->add($data))
		{
			Session::set('posttime', time());
			if($config['bookoff'] == 0)
			{
				echo '�����ɹ�,������Ҫ����Ա���!';
				exit;
			}
			else
			{
				echo '�����ɹ�!';
				exit;
			}
		}
		else
		{
			echo '����ʧ��!';
			exit;
		}
	}
	
	public function show()
	{
	//��ȡ���ݿ�
		$pl = M('guestbook');
		$config = F('basic','','./Web/Conf/');
	//����ж�
		$data['status'] = 1;
		$list = $pl->where($data)->select();
		if(!$list)
		{
			$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook_nopl.html','gb2312','text/xml');
			exit;
		}
	//��ҳ����
		$count = $pl->where($data)->count();
		$this->assign('allnum',$count);
	//ÿ10����ҳ
		$pagenum = 10;
	//��ҳ��
		$pages = ceil($count / $pagenum);
		$prepage = (intval($_GET['page']) - 1) * $pagenum;
		$endpage = intval($_GET['page']) * $pagenum;
		$tempnum = $pagenum * intval($_GET['page']);
		$lastnum = $tempnum < $count ? $tempnum:$count;
		$plist = $pl->where($data)->order('addtime asc')->limit($prepage.','.$endpage)->select();
		foreach($plist as $k=>$v)
		{
			if(!empty($v['recontent']))
			{
				$v['recontent'] = '<font color=red><b>����Ա�ظ���'.$v['recontent'].'</b></font>';
			}
				$pp[$k]=$v;
				$pp[$k]['num'] = $k + 1 + (intval($_GET['page']) - 1) * $pagenum;
		}
	//��ǰҳ
		$this->assign('nowpage',intval($_GET['page']));
	//��ҳ��
		$this->assign('pages',$pages);
	//���һ����¼��
		$this->assign('lastnum',$lastnum);
		$this->assign('list',$pp);
		$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook_pl.html','gb2312','text/xml');
	}
}
?>