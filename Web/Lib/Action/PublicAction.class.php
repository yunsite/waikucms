<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ǰ̨����	Action

    @Filename PublicAction.class.php $

    @Author pengyong $

    @Date 2011-11-21 08:24:19 $
*************************************************************/
class PublicAction extends Action
{
	Public function _empty()
	{
		alert('����������',3);
	}

     public function head()
    {
	//��ȡ���ݿ�ͻ���
		$type = M('type');
		$article = M('article');
		$config = F('basic','','./Web/Conf/');
		
	//��װ��վ����
		$this->assign('config',$config);
		
	//��������
		$data['status'] = 1;
		$data['typeid'] = $config['noticeid']; 
		$roll=$article->where($data)->field('aid,title')->order('addtime desc')->limit($config['rollnum'])->select();
		//�������:��ֹ�����������ҳ��
		foreach ($roll as $k=>$v)
		{
			$roll[$k]['title'] = msubstr($v['title'],0,20,'gb2312');
		}
		$this->assign('roll',$roll);
		
	//��վ����
		$menu = $type->where('ismenu=1')->order('drank asc')->select();
		foreach( $menu as $k=>$v)
		{
			$menuson[$k] = $type->where('fid='.$v['typeid'].' AND drank <> 0')->order('drank asc')->select();
		}
		$this->assign('menuson',$menuson);
		$this->assign('menu',$menu);
		
	//λ�õ���
		$nav = '<a href="__APP__">��ҳ</a>';
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
		//ƴװ�������ַ���
		foreach($typelist as $v)
		{
			if($v==0) continue;
			$s = $type->where('typeid='.$v)->getField('typename');
			$nav.="&nbsp;&gt;&nbsp;<a href=\"".U('lists/'.$v)."\">{$s}</a>";
		}
		$nav.="&nbsp;&gt;&nbsp;<a href=\"".U('lists/'.$typeid)."\">{$typename}</a>";
		$this->assign('nav',$nav);
	//�ͷ��ڴ�
		unset($type,$article);
		$this->assign('head',TMPL_PATH.$config['sitetpl'].'/head.html');
		$this->assign('footer',TMPL_PATH.$config['sitetpl'].'/footer.html');
    }
}
?>