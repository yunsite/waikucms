<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ǰ̨�б�ҳ Action

    @Filename ListAction.class.php $

    @Author pengyong $

    @Date 2011-11-18 08:42:11 $
*************************************************************/
class ListAction extends Action
{
	Public function _empty()
	{ 
		alert('����������',__APP__);
	} 
	
	public function index()
	{
		inject_check($_GET['typeid']);
		inject_check($_GET['p']);
	//��ȡ���ݿ�&�ж�
		$type = M('type');
		$list = $type->where('typeid='.intval($_GET['typeid']))->field('typename,fid,keywords,description,islink,url')->find();
		if(!$list)
		{
			alert('��Ŀ������!',__APP__);
		}
		if($list['islink'] == 1)
		{
			Header('Location:'.turl($list['url']));
		}
	//��Ŀ������Ϣ��װ
		$this->assign('type',$list);
	//��Ŀ����
		$config = F('basic','','./Web/Conf/');
		if($config['listshowmode'] == 1)
		{
			$map['fid'] = $list['fid'];
		}
		else
		{
			$map['fid'] = $_GET['typeid'];
		}
		$map['islink'] = 0;
		$nav = $type->where($map)->field('typeid,typename')->select();
		$this->assign('dh',$nav);
	//��һ���ͷ��ڴ�
		unset($list,$nav,$map);
	//��վͷ��
		R('Public','head');
	//��ѯ���ݿ�ͻ���
		$article = D('ArticleView');
	//��װ����
		$map['status'] = 1;
	//�����ҳ��
		import('@.ORG.Page');
	//׼������
		$data['fid'] = $_GET['typeid'];
		$tlist = $type->where($data)->field('typeid')->select();
		foreach ($tlist as $v)
		{
			$arr[] = $v['typeid'] ;
		}
		$arr[] = $_GET['typeid'];
		$map['article.typeid'] = array('in',$arr);	
	//��ҳ����
		$count=$article->where($map)->count();
		$p = new Page($count,$config['artlistnum']);
		$p->setConfig('prev','��һҳ'); 
		$p->setConfig('header','ƪ����');
		$p->setConfig('first','�� ҳ');
		$p->setConfig('last','ĩ ҳ');
		$p->setConfig('next','��һҳ');
		$p->setConfig('theme',"%first%%upPage%%linkPage%%downPage%%end%
		<li><span><select name='select' onChange='javascript:window.location.href=(this.options[this.selectedIndex].value);'>%allPage%</select></span></li><li><span>��<font color='#CD4F07'><b>%totalRow%</b></font>ƪ����".$config['artlistnum']."ƪ/ÿҳ</span></li>");
	//���ݲ�ѯ
		$alist = $article->where($map)->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
	//��װ����
		$this->assign('page',$p->show());
		$this->assign('list',$alist);
	//�ͷ��ڴ�
		unset($article,$type,$p,$tlist,$alist);
	//ģ�����
		$this->display(TMPL_PATH.$config['sitetpl'].'/article_list.html');
 }
 
}