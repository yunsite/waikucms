<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ��̨���

    @Filename IndexAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:18:41 $
*************************************************************/
class IndexAction extends CommonAction
{	
//��ҳ��ʾ�������index
    public function index()
    {
       $this->display('index');
    }
	
//��ҳ��ʾ�����leftҳ��
	public function left()
    {
        $this->display('left');
    }
	
//��ҳ��ʾ�����headͷ��ҳ��
	public function head()
    {
        $this->display('head');
    }
//��ҳ��ʾ�����bottom�ײ�ҳ��
	public function bottom()
    {
        $this->display('bottom');
    }
//��ҳ��ʾ�����centerҳ�������left��main
	public function center()
    {
        $this->display('center');
    }
//��ҳ��ʾ������Ҳ���ҳ��
	public function main()
    {
	    $count = array();
		$article = M('article');
		$type = M('type');
		$link = M('link');
		$hd = M('flash');
		$ping = M('pl');
		$guest = M('guestbook');
	//��������
		$count['article'] = $article->count();
	//δ�����������
		$count['narticle'] = $article->where('status=0')->count();
	//��������	
		$count['guestbook'] = $guest->count();
	//δ�����������	
		$count['nguestbook'] = $guest->where('status=0')->count();
	//��Ŀ����	
		$count['type'] = $type->count();
	//��������
		$count['link'] = $link->count();
	//�õ�����	
		$count['hd'] = $hd->count();
	//��������
		$count['ping'] = $ping->count();
	//δ�������
		$count['nping'] = $ping->where('status=0')->count();
        $this->assign('count',$count);
		unset($article,$type,$link,$hd,$ping,$guest);
		$info = array(
            '����ϵͳ' => PHP_OS,
            '���л���' => $_SERVER["SERVER_SOFTWARE"],
            'PHP���з�ʽ' => php_sapi_name(),
            '�ϴ���������' => ini_get('upload_max_filesize'),
            'ִ��ʱ������' => ini_get('max_execution_time').'��',
            '������ʱ��' => date("Y��n��j�� H:i:s"),
            '����ʱ��' => gmdate("Y��n��j�� H:i:s",time() + 8 * 3600),
            '����������/IP' => $_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            'ʣ��ռ�' => round((@disk_free_space(".") / (1024 * 1024)),2).'M',
            'register_globals' => get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
		$this->display('main');
    }
//�ײ���Ȩ����ҳ
	public function copy()
	{
		$this->display('copy');
	}
}
?>