<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
   
    @function ����ģ��
 
    @Filename ArticleAction.class.php $

    @Author pengyong $

    @Date 2011-11-21 08:24:25 $
*************************************************************/
class ArticleAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',__APP__);
	} 
	
	public function index()
	{
		if(!isset($_GET['aid']))
		{
			alert('�Ƿ�����',__APP__);
		}
		inject_check($_GET['aid']);
		inject_check($_GET['p']);
	//��ȡ���ݿ�ͻ���
		$article = M('article');
		$config = F('basic','','./Web/Conf/');
	//����ж�
		$alist = $article->where('aid='.intval($_GET['aid']))->find();
		if(!$alist)
		{
			alert('���²����ڻ���ɾ��!',__APP__);
		}
		if($alist['islink'] == 1)
		{
			Header('Location:'.$alist['linkurl']);
		}
		if($alist['status'] == 0)
		{
			alert('����δ���!',__APP__);
		}
	//��վͷ��
		R('Public','head');
	//ͳ�ƴ���
		if($alist['status'] == 1)
		{
			$map['hits'] = $alist['hits']+1;
			$article->where('aid='.$_GET['aid'])->save($map);
		}
	//ע��map
		unset($map);
		$alist['hits'] += 1;
	//�ؼ����滻
		$alist['content'] = $this->key($alist['content']);
	//����ֹ�ͼƬ
		if($config['mouseimg'] == 1)
		{
			$alist['content'] = $this->mouseimg($alist['content']);
		}
	//�����ڷ�ҳ����
		if($alist['pagenum']==0)
		{
		//�ֶ���ҳ
			$alist['content'] = $this->diypage($alist['content']);
		}
		else
		{
		//�Զ���ҳ
			$alist['content'] = $this->autopage($alist['pagenum'],$alist['content']);
		}
	//������ͶƱ
		$this->vote($alist['voteid']);
	//����ͶƱ
		$url = __ROOT__;//��������js�ĸ�·������
		$this->assign('url',$url);
	//��������ƪ
		$map['status'] = 1;
		$map['typeid'] = $alist['typeid'];
		$map['addtime'] = array('lt',$alist['addtime']);
		$up = $article->where($map)->field('aid,title')->order('addtime desc')->limit(1)->find();
		//dump($article->getLastsql());
		if(!$up)
		{
			$updown = '��һƪ��<span>��</span>';
		}
		else
		{
			$up['title'] = msubstr($up['title'],0,20,'gb2312');
			$updown = '��һƪ��<span><a href="'.U('articles/'.$up['aid']).'">'.$up['title'].'</a></span>';
		}
		$map['addtime'] = array('gt',$alist['addtime']);
		$down =$article->where($map)->field('aid,title')->order('addtime desc')->limit(1)->find();
		if(!$down)
		{
			$updown .= '��һƪ��<span>��</span>';
		}
		else
		{
			$dowm['title'] = msubstr($down['title'],0,20,'gb2312');
			$updown .= '��һƪ��<span><a href="'.U('articles/'.$down['aid']).'">'.$down['title'].'</a></span>';
		}
		$this->assign('updown',$updown);
	//�ͷ�����ڴ�
		unset($updown,$up,$down,$map);
	//�������
		$map['status'] = 1;
		$keywords=explode(",",$alist['keywords']);
		foreach ($keywords as $k=>$v)
		{
			if($k == 0)
			{
				$map['_string'] = "(keywords like '%{$v}%')";
			}
			else
			{
				$map['_string'] = " OR (keywords like '%{$v}%')";
			}
		}
		$klist = $article->where($map)->field('aid,title')->limit(6)->select();
	//��װ����
		$this->assign('keylist',$klist);
		$this->assign('article',$alist);
	//�ͷ��ڴ�
		unset($article,$alist,$klist,$map);
	//ģ�����
		$this->display(TMPL_PATH.$config['sitetpl'].'/article_article.html');
	}
	
	//ͶƱģ��
	private function vote($id)
	{
		$vote = M('vote');
		$vo = $vote->where('id='.$id)->find();
		if($vo)
		{
			$strs = explode("\n",trim($vo['vote']));
			for($i = 0;$i < count($strs);$i++)
			{
				$s = explode('=',$strs[$i]);
				$data[$i]['num'] = $s[1];
				$data[$i]['title'] = $s[0];
			}
		}
		else
		{
			$vo['title'] = 'ͶƱID������!������վ����!';
		}
	//��װ����
		$this->assign('votetype',$vo['stype']);
		$this->assign('voteid',$vo['id']);
		$this->assign('votetitle',$vo['title']);
		$this->assign('votedata',$data);
	//�ͷ��ڴ�
		unset($vote,$vo,$data);
	}
	
	
//�ؼ����滻
	private function key($content)
	{
		import('@.ORG.KeyReplace');
		$key = M('key');
		$keys = $key->field('url,title,num')->select();
		$map = array();
		foreach ($keys as $k=>$v)
		{
			$map[$k]['Key'] = $v['title'];
			$map[$k]['Href'] ="<a href=\"{$v['url']}\" target=\"_blank\">{$v['title']}</a>";
			$map[$k]['ReplaceNumber'] = $v['num'];
		}
		$a = new KeyReplace($map,$content);
		$a->KeyOrderBy();
		$a->Replaces();
		return $a->HtmlString;
	}
	
	//��������ֿ���ͼƬ��С�ĺ���
	private function mouseimg($content)
	{
		$pattern = "/<img.*?src=(\".*?\".*?\/)>/is";
		$content = preg_replace($pattern,"<img src=\${1} onload=\"javascript:resizeimg(this,575,600)\">",$content);
		return $content;
	}
	
	//�������ݷ�ҳ-�Զ����ҳ
	private function diypage($content)
	{
		$str = explode('[wk_page]',$content);
		$num = count($str);
		if($num == 1)
		{
			return $content;
			exit;
		}
		import('@.ORG.Page');
		$p = new Page($num,1);
		$p->setConfig('prev','��һҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%upPage%%linkPage%%downPage%");
		$this->assign('page',$p->show());
		$this->assign('nowpage',$p->nowPage);
		$nowpage = $p->nowPage - 1;
	//�ͷ��ڴ�
		unset($p);
		return $str[$nowpage];
	}
	
	
	//�����Զ���ҳ
	private function autopage($pagenum,$content)
	{
		if($pagenum == 0)
		{
			return $content;
		}
		if(strlen($content) < $pagenum)
		{
			return $content;
		}
	//�����ҳ��ͺ�����
		import('@.ORG.Page');
		$num = ceil(strlen($content) / $pagenum);
		$p = new Page($num,1);
		$p->setConfig('prev','��һҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%upPage%%linkPage%%downPage%");
		$this->assign('page',$p->show());
		$this->assign('nowpage',$p->nowPage);
		$content = msubstr($content,($p->nowPage-1) * $pagenum,$pagenum);
	//�ͷ��ڴ�
		unset($p);
		return $content;
	}
}