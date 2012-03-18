<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 留言模块

    @Filename GuestbookAction.class.php $

    @Author pengyong $

    @Date 2011-11-18 09:01:26 $
*************************************************************/
class GuestbookAction extends Action
{
	Public function _empty()
	{ 
		alert('方法不存在',U('Guestbook/index'));
	} 
	
	public function index()
	{
	//网站头部
		R('Public','head');
		$config = F('basic','','./Web/Conf/');
	//用于ajaxjs的根路径变量
		$url=__ROOT__;
		$this->assign('url',$url);
		$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook.html');
	}
	
	public function update()
	{
	//输出gb2312码,ajax默认转的是utf-8
		header("Content-type: text/html; charset=gb2312");
		if(!isset($_POST['author']) or !isset($_POST['content']))
		{
			alert('非法操作!',3);
		}
	//读取数据库和缓存
		$pl = M('guestbook');
		$config = F('basic','','./Web/Conf/');
	//相关判断
		if(Session::is_set('posttime'))
		{
			$temp = Session::get('posttime') + $config['postovertime'];
			if(time() < $temp)
			{
				echo "请不要连续发布!";
				exit;
			}
		}
	//准备工作
		if($config['bookoff'] == 0) $data['status'] = 0;
	//先解密js的escape
		$data['author'] = htmlspecialchars(unescape($_POST['author']));
		$data['content'] = htmlspecialchars(trim(unescape($_POST['content'])));
		$data['addtime'] = date('Y-m-d H:i:s');
	//处理数据
		if($pl->add($data))
		{
			Session::set('posttime', time());
			if($config['bookoff'] == 0)
			{
				echo '发布成功,留言需要管理员审核!';
				exit;
			}
			else
			{
				echo '发布成功!';
				exit;
			}
		}
		else
		{
			echo '发布失败!';
			exit;
		}
	}
	
	public function show()
	{
	//读取数据库
		$pl = M('guestbook');
		$config = F('basic','','./Web/Conf/');
	//相关判断
		$data['status'] = 1;
		$list = $pl->where($data)->select();
		if(!$list)
		{
			$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook_nopl.html','gb2312','text/xml');
			exit;
		}
	//分页处理
		$count = $pl->where($data)->count();
		$this->assign('allnum',$count);
	//每10条分页
		$pagenum = 10;
	//总页数
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
				$v['recontent'] = '<font color=red><b>管理员回复：'.$v['recontent'].'</b></font>';
			}
				$pp[$k]=$v;
				$pp[$k]['num'] = $k + 1 + (intval($_GET['page']) - 1) * $pagenum;
		}
	//当前页
		$this->assign('nowpage',intval($_GET['page']));
	//总页数
		$this->assign('pages',$pages);
	//最后一条记录数
		$this->assign('lastnum',$lastnum);
		$this->assign('list',$pp);
		$this->display(TMPL_PATH.$config['sitetpl'].'/guestbook_pl.html','gb2312','text/xml');
	}
}
?>