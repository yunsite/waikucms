<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ���¹���

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
		
		$p->setConfig('prev','��һҳ');
		$p->setConfig('header','ƪ����');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>ƪ���� 20ƪ/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->moveop();//���±༭option
		$this->jumpop();//������תoption
		$this->urlmode();
		$this->display('index');
    }
	
	  public function add()
    {
		$this->addop();//���±༭option
		$this->jumpop();//������תoption
		$this->vote(0);
		$this->display('add');
    }
	
	 public function edit()
    {
		$type = M('article');
		$list = $type->where('aid='.$_GET['aid'])->find();
		$this->assign('list',$list);
		$this->editop();//���±༭option
		$this->jumpop();//������תoption
		$this->vote($list['voteid']);
		$this->display();
    }
	
	
	public function doedit()
    {	
		if(empty($_POST['title']))
		{
			alert('���ⲻ��Ϊ��!',1);
		}
		if(empty($_POST['typeid']))
		{
			alert('��ѡ����Ŀ!',1);
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
		empty($_POST['author']) ? $data['author'] = 'δ֪' : $data['author'] = trim($_POST['author']);
		empty($_POST['keywords']) ? $data['keywords'] = '' : $data['keywords'] = trim($_POST['keywords']);
		empty($_POST['description']) ? $data['description'] = '' : $data['description'] = trim($_POST['description']);
		empty($_POST['copyfrom']) ? $data['copyfrom'] = '' : $data['copyfrom'] = trim($_POST['copyfrom']);
		empty($_POST['islink']) ? $data['islink'] = '0' : $data['islink'] = trim($_POST['islink']);
		empty($_POST['istop']) ? $data['istop'] = '0' : $data['istop'] = trim($_POST['istop']);
		empty($_POST['isimg']) ? $data['isimg'] = '0' : $data['isimg'] = trim($_POST['isimg']);
		empty($_POST['ishot']) ? $data['ishot'] = '0' : $data['ishot'] = trim($_POST['ishot']);
		empty($_POST['isflash']) ? $data['isflash'] = '0' : $data['isflash'] = trim($_POST['isflash']);
		//���˵�[wk_page]
		$notes = str_replace("[wk_page]","",$_POST['content']);
		empty($_POST['note']) ? $data['note'] = trim(strip_tags(msubstr($notes,0,130,'gb2312',false))).'...' : $data['note'] = $_POST['note'];
		$article = M('article');
		if($article->save($data))
		{
			alert('�����ɹ�!',U('Article/index'));
		}
		alert('����ʧ��!',1);
    }
	
	
	public function doadd()
    {
		//��֤
		if(empty($_POST['title']))
		{
			alert('���ⲻ��Ϊ��!',1);
		}
		if(empty($_POST['typeid']))
		{
			alert('��ѡ����Ŀ!',1);	
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
	//ʹ��stripslashes ��ת��,��ֹ�����������Զ�ת��
		$data['content'] = stripslashes($_POST['content']);
		$data['title'] = trim($_POST['title']);
		$data['hits'] = trim($_POST['hits']);
		$data['typeid'] = trim($_POST['typeid']);
		empty($_POST['addtime']) ? $data['addtime'] = date('Y-m-d H:i:s') : $data['addtime'] = trim($_POST['addtime']);
		empty($_POST['author']) ? $data['author'] = 'δ֪' : $data['author'] = trim($_POST['author']);
		empty($_POST['keywords']) ? $data['keywords'] = '' : $data['keywords'] = trim($_POST['keywords']);
		empty($_POST['description']) ? $data['description'] = '' : $data['description'] = trim($_POST['description']);
		empty($_POST['copyfrom']) ? $data['copyfrom'] = '' : $data['copyfrom'] = trim($_POST['copyfrom']);
		empty($_POST['islink']) ? $data['islink'] = '0' : $data['islink'] = trim($_POST['islink']);
		empty($_POST['istop']) ? $data['istop'] = '0' : $data['istop']=trim($_POST['istop']);
		empty($_POST['isimg']) ? $data['isimg'] = '0' : $data['isimg']=trim($_POST['isimg']);
		empty($_POST['ishot']) ? $data['ishot'] = '0' : $data['ishot']=trim($_POST['ishot']);
		empty($_POST['isflash']) ? $data['isflash'] = '0' : $data['isflash']=trim($_POST['isflash']);
		//���˵�[wk_page]
		$notes = str_replace("[wk_page]","",$_POST['content']);
		empty($_POST['note']) ? $data['note'] = trim(strip_tags(msubstr($notes,0,130,'gb2312',false))) : $data['note']=trim($_POST['note']);
		$article = M('article');
		if($article->add($data))
		{
		 alert('�����ɹ�!',U('Article/index'));
		}
		alert('����ʧ��!',1);
	}
		
		
	public function del()
    {
		$article=D('article');
		if($article->delete($_GET['aid']))
		{
		 alert('�����ɹ�!',U('Article/index')); 
		}
		alert('����ʧ��!',1);
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
			alert('�Ƿ�����',1);
		}
		$this->redirect('index');
	}


	public function delall()
	{
		$aid = $_REQUEST['aid'];  //��ȡ����aid
		$aids = implode(',',$aid);//������ȡaid
		$id = is_array($aid) ? $aids : $aid;
		$map['aid'] = array('in',$id);
		if(!$aid)
		{
			alert('�빴ѡ��¼!',1);
		}
		$article = D('article');
		
		if($_REQUEST['Del'] == '����ʱ��')
		{
			$data['addtime'] = date('Y-m-d H:i:s');
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == 'ɾ��')
		{
			foreach($aid as $v)
			{
				$article->delete($v);
			}
			alert('�����ɹ�!',U('Article/index'));
		}
		
		if($_REQUEST['Del'] == '����δ��')
		{
			$data['status'] = 0;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '�������')
		{
			$data['status'] = 1;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '�Ƽ�')
		{
			$data['ishot'] = 1;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '����Ƽ�')
		{
			$data['ishot'] = 0;
			if($article->where($map)->save($data)){
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '�̶�')
		{
			$data['istop'] = 1;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '����̶�')
		{
			$data['istop']=0;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}

		if($_REQUEST['Del'] == '�õ�')
		{
			$data['isflash'] = 1;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '����õ�')
		{
			$data['isflash']=0;
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
		
		if($_REQUEST['Del'] == '�ƶ�')
		{
			$data['typeid'] = $_REQUEST['typeid'];
			if($article->where($map)->save($data))
			{
				alert('�����ɹ�!',U('Article/index'));
			}
			alert('����ʧ��!',1);
		}
	}
	
	//����ģ�� �����ƶ�option
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
	
	//����ģ�� ������ת��Ŀoption
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
	
	//����ģ�� ���-��Ŀoption
	private function addop(){
		$type = M('type');
		//��ȡ��Ŀoption
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
	
	//����ģ��-�༭-��Ŀoption
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
	
	//ͶƱģ��:for add()
	private function vote($vid){
		$vote = M('vote');
		$vo = $vote->where('status=1')->getField('id,title');
		if($vid == 0)
		{
			$votehtml = '<option value=\"0\" selected>��ͶƱ</option>';
		}
		else
		{
			$votehtml = '<option value=\"0\">��ͶƱ</option>';
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
	//����ģ��Ҳ���ô˷���
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
		$p->setConfig('prev','��һҳ');
		$p->setConfig('header','ƪ����');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li>\n<li><span>��<font color='#009900'><b>%totalRow%</b></font>ƪ���� 20ƪ/ÿҳ</span></li>");
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->moveop();//���±༭option
		$this->jumpop();//������תoption
		$this->urlmode();
		$this->display('index');
	}
}
?>