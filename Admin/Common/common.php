<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
	
	@function ��̨������
	
    @Filename common.php $

    @Author pengyong $

    @Date 2011-11-14 08:45:20 $
*************************************************************/
//ɾ��Ŀ¼����
	function deldir($dirname)
	{
		if(file_exists($dirname))
		{
			$dir = opendir($dirname);
			while($filename = readdir($dir))
			{
				if($filename != "." && $filename != "..")
				{
					$file = $dirname."/".$filename;
					if(is_dir($file))
					{
						deldir($file); //ʹ�õݹ�ɾ����Ŀ¼	
					}
					else
					{
						@unlink($file);
					}
				}
			}
			closedir($dir);
			rmdir($dirname);
		}
	}
	//������Ϣ
	function alert($msg,$url)
	{
		header('Content-type: text/html; charset=gb2312');
		$msg = str_replace("'","\\'",$msg);
		$str = '<script>';
		$str.="alert('".$msg."');";
		switch($url)
		{
			case 1:
				$s = 'window.history.go(-1);';
				break;
			case 2:
				$s = 'window.history.go(-2);';
				break;
			case 3:
				$s = 'self.close();';
				break;
			default:
				$s = "location.href='{$url}';";
		}
		$str.=$s;
		$str.='</script>';
		exit($str);
	}
/*-----�ļ������ļ���������-----*/
//��ȡ�ļ�
function read_file($l1)
{
	return @file_get_contents($l1);
}
//д���ļ�
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
//�ݹ鴴���ļ�
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
// ���鱣�浽�ļ�
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";
	write_file($filename, $con);
}
	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
	{
		if(function_exists("mb_substr"))
			return mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			return iconv_substr($str,$start,$length,$charset);
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix) return $slice."��";
		return $slice;
	}
// ��ȡ�ļ��д�С
function getdirsize($dir)
{ 
	$dirlist = opendir($dir);
	while (false !==  ($folderorfile = readdir($dirlist)))
	{ 
		if($folderorfile != "." && $folderorfile != "..")
		{ 
			if (is_dir("$dir/$folderorfile"))
			{ 
				$dirsize += getdirsize("$dir/$folderorfile"); 
			}
			else
			{ 
				$dirsize += filesize("$dir/$folderorfile"); 
			}
		}    
	}
	closedir($dirlist);
	return $dirsize;
}
	function get_client_ip()
	{
	   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		   $ip = getenv("HTTP_CLIENT_IP");
	   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		   $ip = getenv("HTTP_X_FORWARDED_FOR");
	   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		   $ip = getenv("REMOTE_ADDR");
	   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		   $ip = $_SERVER['REMOTE_ADDR'];
	   else
		   $ip = "unknown";
	   return($ip);
	}
	//�滻�ɼ���ͨ��url������ֵ
	function wk_url_repalce($xmlurl,$order='asc')
	{
	if($order=='asc')
		{
			return str_replace(array('|','@','#'),array('/','=','&'),$xmlurl);
		}
		else
		{
			return str_replace(array('/','=','&'),array('|','@','#'),$xmlurl);
		}
	}
	/**
 +----------------------------------------------------------
 * �Բ�ѯ�������������
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list ��ѯ���
 * @param string $field ������ֶ���
 * @param array $sortby ��������
 * asc�������� desc�������� nat��Ȼ����
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
	function list_sort_by($list,$field, $sortby='asc')
	{
		if(is_array($list))
		{
			$refer = $resultSet = array();
			foreach ($list as $i => $data)
			{
				$refer[$i] = &$data[$field];
			}
			switch ($sortby) 
			{
				case 'asc': // ��������
					asort($refer);
					break;
				case 'desc':// ��������
					arsort($refer);
					break;
				case 'nat': // ��Ȼ����
					natcasesort($refer);
					 break;
			}
			foreach ( $refer as $key=> $val)
			{
				$resultSet[] = &$list[$key];
			}
				return $resultSet;
		}
		return false;
	}
	// ��ȡʱ����ɫ:24Сʱ��Ϊ��ɫ
	function getcolordate($type='Y-m-d H:i:s',$time,$color='red')
	{
		if((time()-$time)>86400)
		{
			return date($type,$time);
		}
		else
		{
			return '<font color="'.$color.'">'.date($type,$time).'</font>';
		}
	}
	/**
 +----------------------------------------------------------
 * �ֽڸ�ʽ�� ���ֽ�����ʽΪ B K M G T �����Ĵ�С
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function byte_format($size, $dec=2)
{
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		 $size /= 1024;
		   $pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}
//����д���ļ�
function testwrite($d){
	$tfile = '_wk.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs){
			return true;
		}else{
			return false;
		}
	}
}
//��ȡģ����������
function gettplname($filename)
{
	switch($filename)
	{
		case 'index.html':
			return '��վ��ҳģ��';
			break;
		case 'footer.html':
			return '��վ�ײ�ģ��';
			break;
		case 'head.html':
			return '��վͷ��ģ��';
			break;
		case 'search.html':
			return '����ҳģ��';
			break;
		case 'article_article.html':
			return '����ģ������ҳ';
			break;
		case 'article_list.html':
			return '����ģ���б�ҳ';
			break;
		case 'guestbook.html':
			return '���Ա�ģ��';
			break;
		case 'guestbook_nopl.html':
			return '���Ա��հ�xml';
			break;
		case 'guestbook_pl.html':
			return '���Ա�xml';
			break;
		case 'pl_pl.html':
			return '����ҳxml';
			break;
		case 'pl_nopl.html':
			return '����ҳ�հ�xml';
			break;
		case 'guestbook_pl.html':
			return '���Ա�xml';
			break;
		case 'vote.html':
			return 'ͶƱҳģ��';
			break;
	}
	$f = explode('.',$filename);
	switch($f[1])
	{
		case 'js':
			return 'js�ű��ļ�';
			break;
		case 'php':
			return 'php�ű��ļ�';
			break;
		case 'css':
			return '�����ʽ��';
			break;
		case 'jpg':
			return 'jpgͼƬ';
			break;
		case 'gif':
			return 'gifͼƬ';
			break;
		case 'png':
			return 'pngͼƬ';
			break;
		case 'zip':
			return 'zipѹ����';
			break;
		case 'rar':
			return 'rarѹ����';
			break;
		case 'html':
			return 'ģ���ļ�';
			break;
		case 'htm':
			return '��ҳ�ļ�';
			break;
		case 'ico':
			return 'icoͼ��';
			break;
		case 'wmv':
			return 'wmv��Ƶ�ļ�';
			break;
		case 'swf':
			return 'flash�ļ�';
			break;
		case 'wma':
			return 'wma��Ƶ�ļ�';
			break;
		case 'mp3':
			return 'mp3��Ƶ�ļ�';
			break;
		case 'flv':
			return 'flv��Ƶ�ļ�';
			break;
		case 'mp4':
			return 'mp4��Ƶ�ļ�';
			break;
		default:
			return 'δ֪�ļ�';
			break;
	}
}
?>