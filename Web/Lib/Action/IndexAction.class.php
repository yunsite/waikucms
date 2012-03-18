<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 前台首页	Action

    @Filename IndexAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 20:06:22 $
*************************************************************/
class IndexAction extends Action
{
	Public function _empty()
	{ 
		alert('方法不存在',__APP__);
	} 

     public function index()
    {
	//网站头部
		R('Public','head');
		
	//查询数据库,读取缓存
		$type = M('type');
		$article = M('article');
		$config = F('basic','','./Web/Conf/');
		
	//网站公告
		$notice = $article->where('status=1 AND typeid='.$config['noticeid'])->field('aid,title')->order('addtime desc')->limit($config['noticenum'])->select();
		$this->assign('notice',$notice);
		unset($notice);
		
	//首页幻灯内容
		//先模式判断
		if($config['flashmode'] == 0)
		{
			$hd = M('flash');
			$hd = $hd->where('status=1')->order('rank asc')->limit($config['ishomeimg'])->select();
			foreach ($hd  as $k=>$v)
			{
				$hd[$k]['imgurl'] = __PUBLIC__."/Uploads/hd/".$v['pic'];
				if(empty($v['pic']))
				{
					$hd[$k]['imgurl'] = TMPL_PATH.$config['sitetpl']."/images/nopic.png";
				}
			}
		}
		else
		{
			$hd = $article->where('isflash=1')->field('title,aid,imgurl')->order('addtime desc')->limit($config['ishomeimg'])->select();
			//判断处理图片地址
			foreach ($hd  as $k=>$v)
			{
				$hd[$k]['url'] = U("articles/".$v['aid']);
				if(empty($v['imgurl']))
				{
					$hd[$k]['imgurl'] = TMPL_PATH.$config['sitetpl']."/images/nopic.png";
				}
			}
		}
		$this->assign('flash',$hd);
		unset($flash);
		
	//首页top 2
		$map['istop'] = 1;
		$map['ishot'] = 1;
		$map['status'] = 1;
		$top = $article->where($map)->field('aid,title,note')->order('addtime desc')->limit(2)->select();
		$top[0]['title'] = msubstr($top[0]['title'],0,18,'gb2312');
		$top[0]['note'] =  msubstr($top[0]['note'],0,50,'gb2312');
		$top[1]['title'] = msubstr($top[1]['title'],0,18,'gb2312');
		$top[1]['note'] =  msubstr($top[1]['note'],0,50,'gb2312');
		$this->assign('top',$top);
		unset($top,$map);
	//首页栏目内容
		$list = $type->where('isindex=1')->order('irank asc')->field('typeid,typename,indexnum')->select();
		foreach ($list as $k=>$v)
		{
			$data['status'] = 1;
			$data['typeid'] = $v['typeid'];
			$k % 2 ==0 ? $list[$k]['i'] = 0 : $list[$k]['i'] = 1;
			//方便定位广告,引入p
			$list[$k]['p'] = $k;
			$list[$k]['article'] = $article->where($data)->order('addtime desc')->field('title,aid,titlecolor')->limit($v['indexnum'])->select();
		}
		$this->assign('list',$list);
		unset($list);
	//首页投票
		$this->vote($config['indexvote']);
		
	//释放内存
		unset($type,$article);
	//友情链接
		$link=M('link');
		$map['islogo'] = 0;
		$map['status'] = 1;
		$lk = $link->where($map)->field('url,title')->order('rank')->select();
		$map['islogo'] = 1;
		$logolk = $link->where($map)->field('url,title,logo')->order('rank')->select();
		$this->assign('link',$lk);
		$this->assign('logolink',$logolk);
		unset($link,$logolk,$map);
	//输出模板
		$this->display(TMPL_PATH.$config['sitetpl'].'/index.html');
    }
	
	public function search()
	{
		if(empty($_POST['k'])){
			alert('请输入关键字!',1);
		}
		inject_check($_GET['k']);
		inject_check($_GET['p']);
		//网站头部
		R('Public','head');

		//查询数据库准备
		$config = F('basic','','./Web/Conf/');
		$article = M('article');
		$map['status'] = 1;
		$keyword = $_POST['k'];
		$map['title'] = array('like','%'.$keyword.'%');
		
		//导入分页类
		import('@.ORG.Page');
		
		//分页相关配置
		$count = $article->where($map)->count();
		$p = new Page($count,20);
		$p->setConfig('prev','上一页'); 
		$p->setConfig('header','篇文章');
		$p->setConfig('first','首 页');
		$p->setConfig('last','末 页');
		$p->setConfig('next','下一页');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li><li><span>共<font color='#CD4F07'><b>%totalRow%</b></font>篇文章 20篇/每页</span></li>");
		
		//查询数据库
		$prefix = C('DB_PREFIX');
		$list = $article->join('left join '.$prefix.'type on '.$prefix.'article.typeid='.$prefix.'type.typeid')->where($map)->field("aid,title,addtime,".$prefix."article.typeid as typeid,typename")->limit($p->firstRow.','.$p->listRows)->order("addtime desc")->select();
		//echo $article->getLastSql(); 验证sql
		
		//封装变量
		$this->assign('list',$list);
		$this->assign('num',$count);
		$this->assign('page',$p->show());
		$this->assign('keyword',$keyword);
		//模板输出
			$this->display(TMPL_PATH.$config['sitetpl'].'/search.html');
	}

	//调查模块
	private function vote($isvote)
	{
		$vote = M('vote');
		$vo = $vote->where('id='.intval($isvote))->find();
		if($vo)
		{
			$strs = explode("\n",trim($vo['vote']));
			for($i = 0;$i < count($strs);$i++)
			{
					$s = explode("=",$strs[$i]);
					$data[$i]['num'] = $s[1];
					$data[$i]['title'] = $s[0];
			}
		}
		else
		{
			$vo['title'] = '投票ID不存在!请检查网站配置!';
		}
		$this->assign('vtype',$vo['stype']);
		$this->assign('vid',$isvote);
		$this->assign('vtitle',$vo['title']);
		$this->assign('vdata',$data);
	}
	//申请友链
	public function reglink()
	{
		$config = F('basic','','./Web/Conf/');
		$this->display(TMPL_PATH.$config['sitetpl'].'/reglink.html');
	}
	public function doreglink()
	{
		header("Content-type: text/html; charset=gb2312");
		$data['title'] = $_POST['Title'];
		$data['url'] = $_POST['LinkUrl'];
		$data['logo'] = $_POST['LogoUrl'];
		$data['status'] = 0;
		$data['rank'] = 10;
		$link = M('link');
		if($link->add($data))
		{
			echo "<br/><br/><br/><font color=red>添加成功，等待审核！请在贵站加上本站链接。</font>";
		}
		else
		{
			echo "<br/><br/><br/><font color=red>添加失败,请联系管理员!</font>";
		}

	}
}
?>