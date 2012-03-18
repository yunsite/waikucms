<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
   
	@function ǰ̨������

    @Filename common.class.php $

    @Author pengyong $

    @Date 2011-11-23 14:56:54 $
*************************************************************/
/**
+----------------------------------------------------------
* ȡ��label������
* ʾ��:{:label(1);}
+----------------------------------------------------------
* @access label
+----------------------------------------------------------
* @param int $id ���
+----------------------------------------------------------
 */
	function label($id)
	{
		$label = M('label');
		$data = $label->where('id='.$id." AND status=1")->getField('content');
		if($data)
		{
			return $data;
		}
	}
 /**
+----------------------------------------------------------
* ȡ��article������:����/�ö�/�Ƽ�/ͼ��
* ʾ��:{:ShowArt([0],[10],[1],[0]);} [] ���������ʡ��
+----------------------------------------------------------
* @access article
+----------------------------------------------------------
* @param int $num ��ʼλ��[ȱʡΪ0]
* @param int $num2 ����λ��[ȱʡΪ10]
* @param int $target �򿪷�ʽ:0:ԭ����(Ĭ��),1:�´���
* @param int $max  0:����(orderby hits)1:�ö�(istop),2:�Ƽ�(ishot),3ͼ��(isimg)
+--------------------------------------------------------------------
 */
	function  ShowArt($num,$num2,$target,$conditions)
	{
		$article = M('article');
		$map['status'] = 1;
		if(!isset($target) or $target==0)
		{
			$tar='';
		}
		else
		{
			$tar="target=\"_blank\"";
		}
		if(!isset($num))$num = 0;
		if(!isset($num2))$num = 10;
		switch($conditions)
		{
			case 0:
				$field = 'aid,title';
				$data = 'hits desc';
				break;
			case 1:
				$map['ishot'] = 1;//�Ƽ�
				$field = 'aid,title';
				$data = 'addtime desc';
				break;
			case 2:
				$map['ishot'] = 1;//�Ƽ�
				$field = 'aid,title';
				$data = 'addtime desc';
				break;
			default:
				$map['isimg'] = 1;
				$field = 'aid,title,imgurl';
				$data = '';
			
		}
		$list = $article->where($map)->field($field)->order($data)->limit($num.','.$num2)->select();
		//�ͷ��ڴ�
		unset($map,$field,$num,$num2,$article);
		if(!$list)
		{
			return 'û������';
			exit;
		}
		$html='';
		$img='';
		foreach($list as $v)
		{
			if($conditions == 3)
			{
			$img = "<img src=\"{$v['imgurl']}\" width=\"160px\" height=\"126px\"><br>";
			}
			$url = U('articles/'.$v['aid']);
		$html.="\n<li><a href=\"{$url}\" {$tar}>{$img}{$v['title']}</a></li>";
		}
		//�ͷ��ڴ�
		unset($list);
		return $html;
	}



/**
+----------------------------------------------------------
* ȡ��Ad������
* ʾ��:{:Ad(1);}
+----------------------------------------------------------
* @access ad
+----------------------------------------------------------
* @param int $id ���
+----------------------------------------------------------
 */
	function Ad($id)
	{
		$ad = M('ad');
		$data['id'] = $id;
		$data['status'] = 1;
		$content = $ad->where($data)->getField('content');
		unset($ad);
		return $content;
	}
/**
+----------------------------------------------------------
* ȡ��article������ ͨ��������Ŀ�б�ҳ
* ʾ��:{:lists(1,0,'list');} [] �����ȱʡ
+----------------------------------------------------------
* @access article,type
+----------------------------------------------------------
* @param int $typeid ��Ŀid
* @param int $mode ��ѯģʽ
* 0:��ѯ����Ŀ�ͱ���Ŀ 1:����ѯ����Ŀ 2:����ѯ����Ŀ
* @param int/string $limit ȡ�����ݵ�����
* ����������:10,�����ѯǰ10��
* �������ַ���:"'1,10'",����ӵ�1��ȡ����10��
* @param string $param д��ģ�庯���� ��'list'
+----------------------------------------------------------
 */
	function lists($typeid,$mode,$limit,$param)
	{
	//��ѯ���ݿ�
		$article = D('ArticleView');
		$type = M('type');
	//��װ����
		$map['status'] = 1;
	//׼������
		$data['fid'] = $typeid;
		$tlist = $type->where($data)->field('typeid')->select();
		foreach ($tlist as $v)
		{
			$arr[] = $v['typeid'] ;
		}
	//ģʽ�ж�
		switch($mode)
		{
			case 0:
				$arr[] = $typeid;
				$map['article.typeid'] = array('in',$arr);
				break;
			case 1:
				$map['article.typeid'] = $typeid;
				break;
			case 2:
				$map['article.typeid'] = array('in',$arr);
				break;
		}
		$alist = $article->where($map)->order('addtime desc')->limit($limit)->select();
	//��װ����
		$this->assign($param,$alist);
	//�ͷ��ڴ�
		unset($article,$type,$tlist,$alist);
	}
	//���ܺ��� just for gb2312
	function unescape($str) 
	{ 
		$str = rawurldecode($str); 
		preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
		$ar = $r[0]; 
		foreach($ar as $k=>$v)
		{ 
			if(substr($v,0,2) == "%u") 
			$ar[$k] = mb_convert_encoding(pack("H4",substr($v,-4)),"gb2312","UCS-2");
			elseif(substr($v,0,3) == "&#x") 
			$ar[$k] = mb_convert_encoding(pack("H4",substr($v,3,-1)),"gb2312","UCS-2");
			elseif(substr($v,0,2) == "&#")
			{ 
			$ar[$k] = mb_convert_encoding(pack("H4",substr($v,2,-1)),"gb2312","UCS-2");
			} 
		} 
		return join("",$ar); 
	}
	
	//ʱ��Ƚ�
	function cptime($time1,$time2)
	{
		if(strtotime($time1) > strtotime($time2))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
/**
+----------------------------------------------------------
*javascript ������Ϣ
* Actionʾ��:alert('����ʧ��!',1);
+----------------------------------------------------------
* @access null
+----------------------------------------------------------
* @param string $msg ������Ϣ
* @param int $url ��תurl
+----------------------------------------------------------
**/
	function alert($msg,$url)
	{
		header('Content-type: text/html; charset=gb2312');
		$str = "<script language=\"javascript\">";
		$str.="alert('{$msg}');";
		switch($url)
		{
			case 1:
				$s="window.history.go(-1);";
				break;
			case 2:
				$s="window.history.go(-2);";
				break;
			case 3:
				$s="self.close();";
				break;
			default:
			$s="location.href='{$url}';";
		}
		$str.=$s;
		$str.="</script>";
		exit($str);
	}
/**
+----------------------------------------------------------
*�Զ���·��url����
* ģ��ʾ��:{$typeid|url=lists,###}
+----------------------------------------------------------
* @access null
+----------------------------------------------------------
* @param string $model �Զ���·������
* @param int $id ·�ɱ���,ͨ����:$aid,$typeid,$voteid
+----------------------------------------------------------
**/
	function url($model,$id)
	{
		return U($model.'/'.$id);
	}
	/**
+----------------------------------------------------------
*�Զ���ģ�峣��·��ת��
* ACTION��ʾ��:Header('Location:'.turl($list['url']));
+----------------------------------------------------------
* @access null
+----------------------------------------------------------
* @param string $str ��ת����url;���Զ���ģ��·��ת��
+----------------------------------------------------------
**/
	function turl($str)
	{
		$article ="__ARTICLE__";
		$type ="__TYPE__";
		$web ="__WEB__";
		$revote ="__VOTE__";
		$rearticle = __ROOT__."/index.php?s=articles";
		$retype = __ROOT__."/index.php?s=lists";
		$reweb = __ROOT__."/index.php?s=";
		$revote = __ROOT__."/index.php?s=votes";
		$str = str_replace($article,$rearticle,$str);
		$str = str_replace($type,$retype,$str);
		$str = str_replace($web,$reweb,$str);
		$str = str_replace($vote,$revote,$str);
		return $str;
	}
	//��ֹsqlע��
	function inject_check($str)
	{
		$tmp=eregi('select|insert|update|and|or|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $str);
		if($tmp)
		{
		alert("�Ƿ�����!",3);
		}
		else
		{
			return $str;
		}
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
?>