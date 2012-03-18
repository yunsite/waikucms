<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 文章管理

    @Filename ArticleAction.class.php $

    @Author pengyong $

    @Date 2011-11-27 08:52:44 $
*************************************************************/
class ArticleAction extends CommonAction
{	
    public function index()
    {
		$article = D('ArticleView');
		import('@.ORG.Page');
		if(isset($_GET['typeid']))
		{
			$count = $article->where('article.typeid='.$_GET['typeid'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->where('article.typeid='.$_GET['typeid'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['status']))
		{
			$count = $article->where('status='.$_GET['status'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->where('status='.$_GET['status'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['istop']))
		{
			$count = $article->where('istop='.$_GET['istop'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->where('istop='.$_GET['istop'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}	
		elseif(isset($_GET['ishot']))
		{
			$count = $article->where('ishot='.$_GET['ishot'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->where('ishot='.$_GET['ishot'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['isflash']))
		{
			$count = $article->where('isflash='.$_GET['isflash'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->where('isflash='.$_GET['isflash'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['isimg']))
		{
			$count = $article->where('isimg='.$_GET['isimg'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list=$article->where('isimg='.$_GET['isimg'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['islink']))
		{
			$count=$article->where('islink='.$_GET['islink'])->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list=$article->where('islink='.$_GET['islink'])->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		elseif(isset($_GET['hits']))
		{
			$count = $article->order('hits desc')->count();
			$p = new Page($count,20); 
			$list = $article->order('hits desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		else
		{
			$count = $article->order('addtime desc')->count();
			$p = new Page($count,20); 
			$list = $article->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		}
		
		$p->setConfig('prev','上一页');
		$p->setConfig('header','篇文章');
		$p->setConfig('first','首 页');
		$p->setConfig('last','末 页');
		$p->setConfig('next','下一页');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>共<font color='#009900'><b>%totalRow%</b></font>篇文章 20篇/每页</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->moveop();//文章编辑option
		$this->jumpop();//快速跳转option
		$this->urlmode();
		$this->display('index');
    }
	
	  public function add()
    {
		$this->addop();//文章编辑option
		$this->jumpop();//快速跳转option
		$this->vote(0);
		$this->display('add');
    }
	
	 public function edit()
    {
		$type = M('article');
		$list = $type->where('aid='.$_GET['aid'])->find();
		$this->assign('list',$list);
		$this->editop();//文章编辑option
		$this->jumpop();//快速跳转option
		$this->vote($list['voteid']);
		$this->display();
    }
	
	
	public function doedit()
    {	
		if(empty($_POST['title']))
		{
			alert('标题不能为空!',1);
		}
		if(empty($_POST['typeid']))
		{
			alert('请选择栏目!',1);
		}
		if(isset($_POST['linkurl']))
		{
			$data['linkurl'] = trim($_POST['linkurl']);
		}
		if(isset($_POST['imgurl']))
		{
			$data['imgurl'] = trim($_POST['imgurl']);
		}
		if(!empty($_POST['TitleFontColor']))
		{
			$data['titlecolor'] = trim($_POST['TitleFontColor']);
		}
		$data['aid'] = $_POST['aid'];
		$data['voteid'] = $_POST['voteid'];
		$data['pagenum'] = $_POST['pagenum'];
		$data['content'] = stripslashes($_POST['content']);
		$data['title'] = trim($_POST['title']);
		$data['hits'] = trim($_POST['hits']);
		$data['typeid'] = trim($_POST['typeid']);
		empty($_POST['addtime']) ? $data['addtime'] = date('Y-m-d H:i:s') : $data['addtime'] = trim($_POST['addtime']);
		empty($_POST['author']) ? $data['author'] = '未知' : $data['author'] = trim($_POST['author']);
		empty($_POST['keywords']) ? $data['keywords'] = '' : $data['keywords'] = trim($_POST['keywords']);
		empty($_POST['description']) ? $data['description'] = '' : $data['description'] = trim($_POST['description']);
		empty($_POST['copyfrom']) ? $data['copyfrom'] = '' : $data['copyfrom'] = trim($_POST['copyfrom']);
		empty($_POST['islink']) ? $data['islink'] = '0' : $data['islink'] = trim($_POST['islink']);
		empty($_POST['istop']) ? $data['istop'] = '0' : $data['istop'] = trim($_POST['istop']);
		empty($_POST['isimg']) ? $data['isimg'] = '0' : $data['isimg'] = trim($_POST['isimg']);
		empty($_POST['ishot']) ? $data['ishot'] = '0' : $data['ishot'] = trim($_POST['ishot']);
		empty($_POST['isflash']) ? $data['isflash'] = '0' : $data['isflash'] = trim($_POST['isflash']);
		//过滤掉[wk_page]
		$notes = str_replace("[wk_page]","",$_POST['content']);
		empty($_POST['note']) ? $data['note'] = trim(strip_tags(msubstr($notes,0,130,'gb2312',false))).'...' : $data['note'] = $_POST['note'];
		$article = M('article');
		if($article->save($data))
		{
			alert('操作成功!',U('Article/index'));
		}
		alert('操作失败!',1);
    }
	
	
	public function doadd()
    {
		//验证
		if(empty($_POST['title']))
		{
			alert('标题不能为空!',1);
		}
		if(empty($_POST['typeid']))
		{
			alert('请选择栏目!',1);	
		}
		if(isset($_POST['linkurl']))
		{
			$data['linkurl'] = trim($_POST['linkurl']);
		}
		if(isset($_POST['imgurl']))
		{
			$data['imgurl'] = trim($_POST['imgurl']);
		}
		if(!empty($_POST['TitleFontColor']))
		{
			$data['titlecolor'] = trim($_POST['TitleFontColor']);
		}
		$data['status'] = 1;
		$data['voteid'] = $_POST['voteid'];
		$data['pagenum'] = $_POST['pagenum'];
	//使用stripslashes 反转义,防止服务器开启自动转义
		$data['content'] = stripslashes($_POST['content']);
		$data['title'] = trim($_POST['title']);
		$data['hits'] = trim($_POST['hits']);
		$data['typeid'] = trim($_POST['typeid']);
		empty($_POST['addtime']) ? $data['addtime'] = date('Y-m-d H:i:s') : $data['addtime'] = trim($_POST['addtime']);
		empty($_POST['author']) ? $data['author'] = '未知' : $data['author'] = trim($_POST['author']);
		empty($_POST['keywords']) ? $data['keywords'] = '' : $data['keywords'] = trim($_POST['keywords']);
		empty($_POST['description']) ? $data['description'] = '' : $data['description'] = trim($_POST['description']);
		empty($_POST['copyfrom']) ? $data['copyfrom'] = '' : $data['copyfrom'] = trim($_POST['copyfrom']);
		empty($_POST['islink']) ? $data['islink'] = '0' : $data['islink'] = trim($_POST['islink']);
		empty($_POST['istop']) ? $data['istop'] = '0' : $data['istop']=trim($_POST['istop']);
		empty($_POST['isimg']) ? $data['isimg'] = '0' : $data['isimg']=trim($_POST['isimg']);
		empty($_POST['ishot']) ? $data['ishot'] = '0' : $data['ishot']=trim($_POST['ishot']);
		empty($_POST['isflash']) ? $data['isflash'] = '0' : $data['isflash']=trim($_POST['isflash']);
		//过滤掉[wk_page]
		$notes = str_replace("[wk_page]","",$_POST['content']);
		empty($_POST['note']) ? $data['note'] = trim(strip_tags(msubstr($notes,0,130,'gb2312',false))) : $data['note']=trim($_POST['note']);
		$article = M('article');
		if($article->add($data))
		{
		 alert('操作成功!',U('Article/index'));
		}
		alert('操作失败!',1);
	}
		
		
	public function del()
    {
		$article=D('article');
		if($article->delete($_GET['aid']))
		{
		 alert('操作成功!',U('Article/index')); 
		}
		alert('操作失败!',1);
    }
	
	public function status(){
		$a = M('article');
		if($_GET['status'] == 0)
		{
			$a->where( 'aid='.$_GET['aid'] )-> setField( 'status',1);
		}
		elseif($_GET['status'] == 1)
		{
			$a->where( 'aid='.$_GET['aid'] )-> setField( 'status',0);
		}
		else
		{
			alert('非法操作',1);
		}
		$this->redirect('index');
	}


	public function delall()
	{
		$aid = $_REQUEST['aid'];  //获取文章aid
		$aids = implode(',',$aid);//批量获取aid
		$id = is_array($aid) ? $aids : $aid;
		$map['aid'] = array('in',$id);
		if(!$aid)
		{
			alert('请勾选记录!',1);
		}
		$article = D('article');
		
		if($_REQUEST['Del'] == '更新时间')
		{
			$data['addtime'] = date('Y-m-d H:i:s');
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '删除')
		{
			foreach($aid as $v)
			{
				$article->delete($v);
			}
			alert('操作成功!',U('Article/index'));
		}
		
		if($_REQUEST['Del'] == '批量未审')
		{
			$data['status'] = 0;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '批量审核')
		{
			$data['status'] = 1;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '推荐')
		{
			$data['ishot'] = 1;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '解除推荐')
		{
			$data['ishot'] = 0;
			if($article->where($map)->save($data)){
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '固顶')
		{
			$data['istop'] = 1;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '解除固顶')
		{
			$data['istop']=0;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}

		if($_REQUEST['Del'] == '幻灯')
		{
			$data['isflash'] = 1;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '解除幻灯')
		{
			$data['isflash']=0;
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
		
		if($_REQUEST['Del'] == '移动')
		{
			$data['typeid'] = $_REQUEST['typeid'];
			if($article->where($map)->save($data))
			{
				alert('操作成功!',U('Article/index'));
			}
			alert('操作失败!',1);
		}
	}
	
	//文章模块 批量移动option
	private function moveop()
	{
		$type = M('type');
		$oplist = $type->where('islink=0')->field("typeid,typename,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		foreach($oplist as $k=>$v)
		{
			if($v['fid'] == 0)
			{
				$count[$k] = '';
			}
			else
			{
				for($i = 0;$i < count(explode('-',$v['bpath'])) * 2;$i++)
				{
					$count[$k].='&nbsp;';
				}
			}
			$op.="<option value=\"{$v['typeid']}\">{$count[$k]}|-{$v['typename']}</option>";
		}
        $this->assign('op2',$op);
	}
	
	//文章模块 快速跳转栏目option
	private function jumpop()
	{
		$type = M('type');
		$oplist = $type->where('islink=0')->field("typeid,typename,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		foreach($oplist as $k=>$v)
		{
			if($v['fid'] == 0)
			{
				$count[$k]='';
			}
			else
			{
				for($i = 0;$i < count(explode('-',$v['bpath'])) * 2;$i++)
				{
					$count[$k].='&nbsp;';
				}
			}
			$op.="<option value=\"".U('Article/index?typeid='.$v['typeid'])."\">{$count[$k]}|-{$v['typename']}</option>";
		}
        $this->assign('op',$op);
	}
	
	//文章模块 添加-栏目option
	private function addop(){
		$type = M('type');
		//获取栏目option
		$list = $type->where('islink=0')->field("typeid,typename,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		foreach($list as $k=>$v)
		{
			if($v['fid'] == 0)
			{
				$count[$k] = '';
			}
			else
			{
				for($i = 0;$i < count(explode('-',$v['bpath'])) * 2;$i++)
				{
					$count[$k].='&nbsp;';
				}
			}
			$option.="<option value=\"{$v['typeid']}\">{$count[$k]}|-{$v['typename']}</option>";
		}
		$this->assign('option',$option);
	}
	
	//文章模块-编辑-栏目option
	private function editop()
	{
		$article = M('article');
		$a = $article->where('aid='.$_GET['aid'])->field('typeid')->find();
		$type = M('type');
		$list = $type->where('islink=0')->field("typeid,typename,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		foreach($list as $k=>$v)
		{
			if($v['fid'] == 0)
			{
				$count[$k]='';
			}
			else
			{
				for($i = 0;$i < count(explode('-',$v['bpath'])) * 2;$i++)
				{
					$count[$k].='&nbsp;';
				}
			}
			
			if($v['typeid'] == $a['typeid'])
			{
				$option.="<option value=\"{$v['typeid']}\" selected>{$count[$k]}|-{$v['typename']}</option>";
			}
			else
			{
				$option.="<option value=\"{$v['typeid']}\">{$count[$k]}|-{$v['typename']}</option>";
			}
		}
		$this->assign('option',$option);
	}
	
	//投票模块:for add()
	private function vote($vid){
		$vote = M('vote');
		$vo = $vote->where('status=1')->getField('id,title');
		if($vid == 0)
		{
			$votehtml = '<option value=\"0\" selected>不投票</option>';
		}
		else
		{
			$votehtml = '<option value=\"0\">不投票</option>';
		}
		foreach($vo as $k=>$v)
		{
			if($k == $vid)
			{
				$votehtml.="<option value=\"{$k}\" selected>{$v}</option>";
			}
			else
			{
				$votehtml.="<option value=\"{$k}\">{$v}</option>";
			}
		}
		$this->assign('votehtml',$votehtml);
		unset($votehtml);
	}
	//评论模块也调用此方法
	public function urlmode()
	{
		$config = F('basic','','./Web/Conf/');
		switch($config['urlmode'])
		{
			case 0:
				$urlmode = 'index.php/';
				break;
			case 1:
				$urlmode = '';
				break;
			case 2:
				$urlmode = 'index.php?s=';
		}
		switch ($config['suffix'])
		{
			case 0:
				$suffix = '.html';
				break;
			case 1:
				$suffix = '.htm';
				break;
			case 2:
				$suffix = '.shtml';
				break;
			case 3:
				$suffix = '.php';
				break;
			case 4:
				$suffix = '.asp';
				break;
			case 5:
				$suffix = '.aspx';
				break;
			case 6:
				$suffix = '.jsp';
				break;
		}
		$this->assign('urlmode',$urlmode);
		$this->assign('suffix',$suffix);
		unset($config);
	}

	public function search()
	{
		$article = D('ArticleView');
		import('@.ORG.Page');
		$map['title'] = array('like','%'.$_POST['keywords'].'%');
		$count = $article->where($map)->order('addtime desc')->count();
		$p = new Page($count,20); 
		$list = $article->where($map)->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('prev','上一页');
		$p->setConfig('header','篇文章');
		$p->setConfig('first','首 页');
		$p->setConfig('last','末 页');
		$p->setConfig('next','下一页');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>共<font color='#009900'><b>%totalRow%</b></font>篇文章 20篇/每页</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->moveop();//文章编辑option
		$this->jumpop();//快速跳转option
		$this->urlmode();
		$this->display('index');
	}
}
?>