<?php
/****************
��װ�� ������
$id pengyong.info
2011-11-12 21:01:33
****************/
function RunMagicQuotes(&$str)
{
    if(!get_magic_quotes_gpc()) {
        if( is_array($str) )
            foreach($str as $key => $val) $str[$key] = RunMagicQuotes($val);
        else
            $str = addslashes($str);
    }
    return $str;
}

function gdversion()
{
  //û����php.ini����������������GDĬ������2.0���ϰ汾
  if(!function_exists('phpinfo'))
  {
      if(function_exists('imagecreate')) return '2.0';
      else return 0;
  }
  else
  {
    ob_start();
    phpinfo(8);
    $module_info = ob_get_contents();
    ob_end_clean();
    if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)) {   $gdversion_h = $matches[1];  }
    else {  $gdversion_h = 0; }
    return $gdversion_h;
  }
}
function TestWrite($d)
{
    $tfile = '_wkt.txt';
    $d = preg_replace("#\/$#", '', $d);
    $fp = @fopen($d.'/'.$tfile,'w');
    if(!$fp) return false;
    else
    {
        fclose($fp);
        $rs = @unlink($d.'/'.$tfile);
        if($rs) return true;
        else return false;
    }
}
function GetBackAlert($msg,$isstop=0)
{
    global $s_lang;
    $msg = str_replace('"','`',$msg);
    if($isstop==1) $msg = "<script>\r\n<!--\r\n alert(\"{$msg}\");\r\n-->\r\n</script>\r\n";
    else $msg = "<script>\r\n<!--\r\n alert(\"{$msg}\");history.go(-1);\r\n-->\r\n</script>\r\n";
    $msg = "<meta http-equiv=content-type content='text/html; charset={$s_lang}'>\r\n".$msg;
    return $msg;
}
?>