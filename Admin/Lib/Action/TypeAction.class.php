<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
    @function ��Ŀ����ģ��

    @Filename TypeAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:33:18 $
*************************************************************/
class TypeAction extends CommonAction
{	
    public function index()
    {
		$type = M('type');
		$article = M('article');
		$list = $type->field("typeid,typename,ismenu,isindex,islink,isuser,drank,irank,fid,concat(path,'-',typeid) as bpath")->order('bpath,drank')->select();
		foreach($list as $k=>$v)
		{
			$list[$k]['count'] = count(explode('-',$v['bpath']));
			$list[$k]['total'] = $article->where('typeid='.$v['typeid'])->count();
			$str = '';
			if($v['fid'] <> 0)
			{
				for($i = 0;$i < $list[$k]['count'] * 2;$i++)
				{
					$str .= '&nbsp;';
				}
				$str .= '|-';
			}
			$list[$k]['space'] = $str;
		}
		$this->assign('list',$list);
		unset($type,$article,$list);
		$this->display('index');	
    }
	
	public function add()
    {
        $type = M('type');
		$list = $type->where('islink=0')->field("typeid,typename,ismenu,isindex,islink,isuser,drank,irank,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		foreach($list as $key=>$value)
		{
			$list[$key]['count'] = count(explode('-',$value['bpath']));
		}
		$this->assign('list',$list);
		unset($type,$list);
		$this->display('add');
    }
	
	public function doadd()
    {
		if(empty($_POST['typename']))
		{
			alert('��Ŀ���Ʋ���Ϊ��!',1);
		}
		
		$type = D('type');
		
		if($type->create())
		{
			if($type->add())
			{
				alert('�����ɹ�!',U('Type/index'));
			}
			alert('����ʧ��!',1);
		}
		$this->error($type->getError());
    }

	 public function edit()
    {
    	$type = M('type');
		$list = $type->where('typeid='.$_GET['typeid'])->find();
		//��ȡ��Ŀoption
		$olist = $type->where('islink=0')->field("typeid,typename,fid,concat(path,'-',typeid) as bpath")->order('bpath')->select();
		
		foreach($olist as $k=>$v)
		{
			$count[$k] = '';
			if($v['fid'] <> 0)
			{
				for($i = 0;$i < count(explode('-',$v['bpath'])) * 2;$i++)
				{
					$count[$k].='&nbsp;';
				}
			}
		
			if($v['typeid'] == $list['fid'])
			{
				$option.="<option value=\"{$v['typeid']}\" selected>{$count[$k]}|-{$v['typename']}</option>";
			}
			else
			{
				$option.="<option value=\"{$v['typeid']}\">{$count[$k]}|-{$v['typename']}</option>";
			}
		}

		if($list['fid'] == 0)
		{
			$option.='<option value=\"0\" selected>��Ϊ��������</option>';
		}
		else
		{
			$option.='<option value=\"0\">��Ϊ��������</option>';
		}
		$this->assign('option',$option);
        $this->assign('list',$list);
		unset($list,$type);
		$this->display('edit');
    }
	
//ִ�б༭
	public function doedit()
    {
	    if(empty($_POST['typename']))
		{
			alert('��Ŀ���Ʋ���Ϊ��!',1);
		}
		$type = D('type');
		$type->create();
		if($type->save())
		{
			alert('�����ɹ�!',U('Type/index'));
		}
		alert('����ʧ��!',1);
    }
	
	//ɾ����Ŀ&ִ��ɾ��
		public function del()
    {
		$type = M('type');
		$article = M('article');
		if($type->where('fid='.$_GET['typeid'])->select())
		{
			alert('����ɾ������Ŀ!',U('Type/index'));
		}
		if($article->where('typeid='.$_GET['typeid'])->select())
		{
			alert('���������Ŀ������!',U('Type/index'));
		}
		if($type->where('typeid='.$_GET['typeid'])->delete())
		{
			alert('ɾ���ɹ�!',U('Type/index'));
		}
    }

	public function status(){
		$a = M('type');
		$s = explode("-",$_GET['s']);
		if($s[1] == 0)
		{
			$a->where( 'typeid='.$_GET['typeid'] )-> setField($s[0],1);
		}
		elseif($s[1] == 1)
		{
			$a->where( 'typeid='.$_GET['typeid'] )-> setField($s[0],0);
		}
		else
		{
			alert('�Ƿ�����',1);
		}
		$this->redirect('index');
	}
}
?>