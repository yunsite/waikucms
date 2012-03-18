<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 前台评论 Action

    @Filename PlAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 21:30:58 $
*************************************************************/
class PlAction extends Action
{
	Public function _empty()
	{ 
		alert('方法不存在',__APP__);
	} 
	
	public function update()
	{
	//输出gb2312码,ajax默认转的是utf-8
		header("Content-type: text/html; charset=gb2312");
		if(!isset($_POST['aid']) or !isset($_POST['author']) or !isset($_POST['content']))
		{
			alert('非法操作!',__APP__);
		}
	//读取数据库和缓存
		$pl = M('pl');
		$config = F('basic','','./Web/Conf/');
		$data['ip'] = get_client_ip();
	//先解密js的escape
		$data['author'] = htmlspecialchars(unescape($_POST['author']));
	//使用stripslashes 反转义,防止服务器开启自动转义
		$data['content'] = htmlspecialchars(trim(stripslashes(unescape($_POST['content']))));
		$data['ptime'] = date('Y-m-d H:i:s');
		$data['aid'] = htmlspecialchars($_POST['aid']);
		
		if(Session::is_set('pltime'))
		{
			$temp=Session::get('pltime') + $config['postovertime'];
			if(time() < $temp)
			{
				echo "请不要连续发布!";
				exit;
			}
		}
		if($config['pingoff'] == 0) $data['status'] = 0;
		if($pl->add($data))
		{
			Session::set('pltime', time());
			if($config['pingoff'] == 0)
			{
				echo "发布成功,评论需要管理员审核!";
				exit;
			}
			else
			{
				echo "发布成功!";
				exit;
			}
		}
		else
		{
			echo "发布失败!";
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
		$pagenum = 6;//每六条分页
		$pages = ceil($count / $pagenum);//总页数
		$prepage = ($_GET['page']-1) * $pagenum;
		$endpage = $_GET['page'] * $pagenum;
		$tempnum = $pagenum * $_GET['page'];
		$lastnum = ($tempnum < $count) ? $tempnum : $count;
		$plist = $pl->where($data)->order('ptime asc')->limit($prepage.','.$endpage)->select();
		foreach($plist as $k=>$v)
		{
			if(!empty($v['recontent']))
			{
				$v['recontent'] = '<font color=red><b>管理员回复：'.$v['recontent'].'</b></font>';
			}
			$pp[$k] = $v;
			$pp[$k]['num']= $k + 1 + (intval($_GET['page'])-1) * $pagenum;
		}
	//封装变量
		$this->assign('nowpage',intval($_GET['page']));//当前页
		$this->assign('pages',$pages);//总页数
		$this->assign('aid',intval($_GET['aid']));//文章aid
		$this->assign('lastnum',$lastnum);//最后一条记录数
		$this->assign('list',$pp);
	//模板输出
		$this->display(TMPL_PATH.$config['sitetpl'].'/pl_pl.html','gb2312','text/xml');
	}
}
?>