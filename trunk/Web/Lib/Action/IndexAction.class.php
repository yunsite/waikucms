<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ǰ̨��ҳ	Action

    @Filename IndexAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 20:06:22 $
*************************************************************/
class IndexAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',__APP__);
	} 

     public function index()
    {
	//��վͷ��
		R('Public','head');
		
	//��ѯ���ݿ�,��ȡ����
		$type = M('type');
		$article = M('article');
		$config = F('basic','','./Web/Conf/');
		
	//��վ����
		$notice = $article->where('status=1 AND typeid='.$config['noticeid'])->field('aid,title')->order('addtime desc')->limit($config['noticenum'])->select();
		$this->assign('notice',$notice);
		unset($notice);
		
	//��ҳ�õ�����
		//��ģʽ�ж�
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
			//�жϴ���ͼƬ��ַ
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
		
	//��ҳtop 2
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
	//��ҳ��Ŀ����
		$list = $type->where('isindex=1')->order('irank asc')->field('typeid,typename,indexnum')->select();
		foreach ($list as $k=>$v)
		{
			$data['status'] = 1;
			$data['typeid'] = $v['typeid'];
			$k % 2 ==0 ? $list[$k]['i'] = 0 : $list[$k]['i'] = 1;
			//���㶨λ���,����p
			$list[$k]['p'] = $k;
			$list[$k]['article'] = $article->where($data)->order('addtime desc')->field('title,aid,titlecolor')->limit($v['indexnum'])->select();
		}
		$this->assign('list',$list);
		unset($list);
	//��ҳͶƱ
		$this->vote($config['indexvote']);
		
	//�ͷ��ڴ�
		unset($type,$article);
	//��������
		$link=M('link');
		$map['islogo'] = 0;
		$map['status'] = 1;
		$lk = $link->where($map)->field('url,title')->order('rank')->select();
		$map['islogo'] = 1;
		$logolk = $link->where($map)->field('url,title,logo')->order('rank')->select();
		$this->assign('link',$lk);
		$this->assign('logolink',$logolk);
		unset($link,$logolk,$map);
	//���ģ��
		$this->display(TMPL_PATH.$config['sitetpl'].'/index.html');
    }
	
	public function search()
	{
		if(empty($_POST['k'])){
			alert('������ؼ���!',1);
		}
		inject_check($_GET['k']);
		inject_check($_GET['p']);
		//��վͷ��
		R('Public','head');

		//��ѯ���ݿ�׼��
		$config = F('basic','','./Web/Conf/');
		$article = M('article');
		$map['status'] = 1;
		$keyword = $_POST['k'];
		$map['title'] = array('like','%'.$keyword.'%');
		
		//�����ҳ��
		import('@.ORG.Page');
		
		//��ҳ�������
		$count = $article->where($map)->count();
		$p = new Page($count,20);
		$p->setConfig('prev','��һҳ'); 
		$p->setConfig('header','ƪ����');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li><li><span>��<font color='#CD4F07'><b>%totalRow%</b></font>ƪ���� 20ƪ/ÿҳ</span></li>");
		
		//��ѯ���ݿ�
		$prefix = C('DB_PREFIX');
		$list = $article->join('left join '.$prefix.'type on '.$prefix.'article.typeid='.$prefix.'type.typeid')->where($map)->field("aid,title,addtime,".$prefix."article.typeid as typeid,typename")->limit($p->firstRow.','.$p->listRows)->order("addtime desc")->select();
		//echo $article->getLastSql(); ��֤sql
		
		//��װ����
		$this->assign('list',$list);
		$this->assign('num',$count);
		$this->assign('page',$p->show());
		$this->assign('keyword',$keyword);
		//ģ�����
			$this->display(TMPL_PATH.$config['sitetpl'].'/search.html');
	}

	//����ģ��
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
			$vo['title'] = 'ͶƱID������!������վ����!';
		}
		$this->assign('vtype',$vo['stype']);
		$this->assign('vid',$isvote);
		$this->assign('vtitle',$vo['title']);
		$this->assign('vdata',$data);
	}
	//��������
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
			echo "<br/><br/><br/><font color=red>��ӳɹ����ȴ���ˣ����ڹ�վ���ϱ�վ���ӡ�</font>";
		}
		else
		{
			echo "<br/><br/><br/><font color=red>���ʧ��,����ϵ����Ա!</font>";
		}

	}
}
?>