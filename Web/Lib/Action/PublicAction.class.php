<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 前台公共	Action

    @Filename PublicAction.class.php $

    @Author pengyong $

    @Date 2011-11-21 08:24:19 $
*************************************************************/
class PublicAction extends Action
{
	Public function _empty()
	{
		alert('方法不存在',3);
	}

     public function head()
    {
	//读取数据库和缓存
		$type = M('type');
		$article = M('article');
		$config = F('basic','','./Web/Conf/');
		
	//封装网站配置
		$this->assign('config',$config);
		
	//滚动公告
		$data['status'] = 1;
		$data['typeid'] = $config['noticeid']; 
		$roll=$article->where($data)->field('aid,title')->order('addtime desc')->limit($config['rollnum'])->select();
		//处理标题:防止标题过长撑乱页面
		foreach ($roll as $k=>$v)
		{
			$roll[$k]['title'] = msubstr($v['title'],0,20,'gb2312');
		}
		$this->assign('roll',$roll);
		
	//网站导航
		$menu = $type->where('ismenu=1')->order('drank asc')->select();
		foreach( $menu as $k=>$v)
		{
			$menuson[$k] = $type->where('fid='.$v['typeid'].' AND drank <> 0')->order('drank asc')->select();
		}
		$this->assign('menuson',$menuson);
		$this->assign('menu',$menu);
		
	//位置导航
		$nav = '<a href="__APP__">首页</a>';
		if(isset($_GET['aid']))
		{
			$typeid = $article->where('aid='.$_GET['aid'])->getField('typeid');
		}
		else
		{
			$typeid = $_GET['typeid'];
		}
		$typename = $type->where('typeid='.$typeid)->getField('typename');
		$path = $type->where('typeid='.$typeid)->getField('path');
		$typelist = explode('-',$path);
		//拼装导航栏字符串
		foreach($typelist as $v)
		{
			if($v==0) continue;
			$s = $type->where('typeid='.$v)->getField('typename');
			$nav.="&nbsp;&gt;&nbsp;<a href=\"".U('lists/'.$v)."\">{$s}</a>";
		}
		$nav.="&nbsp;&gt;&nbsp;<a href=\"".U('lists/'.$typeid)."\">{$typename}</a>";
		$this->assign('nav',$nav);
	//释放内存
		unset($type,$article);
		$this->assign('head',TMPL_PATH.$config['sitetpl'].'/head.html');
		$this->assign('footer',TMPL_PATH.$config['sitetpl'].'/footer.html');
    }
}
?>