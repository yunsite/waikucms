<?php
@set_time_limit(0);
error_reporting(E_ALL || ~E_NOTICE);
$verMsg = ' V1.0 RC GBK';
$s_lang = 'gb2312';
$dfDbname = 'wkcmsv1.0gbk';
$errmsg = '';
$insLockfile = dirname(__FILE__).'/install_lock.txt';
$rnd_cookieEncode = chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).mt_rand(1000,9999).chr(mt_rand(ord('A'),ord('Z')));
define('WKROOT',preg_replace("#[\\\\\/]Install#", '', dirname(__FILE__)));
define('WKCONFIG',dirname(__FILE__).'/../Public/Config/');
define('WKBASIC',dirname(__FILE__).'/../Web/Conf/');
header("Content-Type: text/html; charset={$s_lang}");
require_once('functions.php');
foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
    foreach($$_request as $_k => $_v) ${$_k} = RunMagicQuotes($_v);
}
if(file_exists($insLockfile))
{
    exit(" ���������а�װ�������ȷ��Ҫ���°�װ�����ȴ�FTP��ɾ�� Install/install_lock.txt��");
}
if(empty($step))
{
    $step = 1;
}
/*------------------------
ʹ��Э����
function _1_Agreement()
------------------------*/
if($step==1)
{
    include('./templates/step-1.html');
    exit();
}
/*------------------------
��������
function _2_TestEnv()
------------------------*/
else if($step==2)
{
	$phpv =PHP_VERSION;
    $sp_os = PHP_OS;
    $sp_gd = gdversion();
    $sp_server = $_SERVER['SERVER_SOFTWARE'];
    $sp_host = (empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']);
    $sp_name = $_SERVER['SERVER_NAME'];
    $sp_max_execution_time = ini_get('max_execution_time');
    $sp_allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
    $sp_allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
    $sp_safe_mode = (ini_get('safe_mode') ? '<font color=red>[��]On</font>' : '<font color=green>[��]Off</font>');
    $sp_gd = ($sp_gd>0 ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
    $sp_mysql = (function_exists('mysql_connect') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');

    if($sp_mysql=='<font color=red>[��]Off</font>')
    $sp_mysql_err = TRUE;
    else
    $sp_mysql_err = FALSE;
	$sp_testdirs = array(
        '/',
		'/Core/*',
        '/Public/*',
        '/Admin/*',
        '/Web/*',
        '/Install/*',
    );
    include('./templates/step-2.html');
    exit();
}elseif($step==3){
if(!empty($_SERVER['REQUEST_URI']))
    $scriptName = $_SERVER['REQUEST_URI'];
    else
    $scriptName = $_SERVER['PHP_SELF'];

    $basepath = preg_replace("#\/Install(.*)$#i", '', $scriptName);

    if(!empty($_SERVER['HTTP_HOST']))
        $baseurl = 'http://'.$_SERVER['HTTP_HOST'].$basepath;
    else
        $baseurl = "http://".$_SERVER['SERVER_NAME'].$basepath;
include('./templates/step-3.html');
    exit();
}/*------------------------
��ͨ��װ
function _4_Setup()
------------------------*/
else if($step==4)
{
    $conn = mysql_connect($dbhost,$dbuser,$dbpwd) or die("<script>alert('���ݿ���������¼������Ч��\\n\\n�޷��������ݿ⣬�������趨��');history.go(-1);</script>");

    mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbname."`;",$conn);
    
    mysql_select_db($dbname) or die("<script>alert('ѡ�����ݿ�ʧ�ܣ���������ûȨ�ޣ���Ԥ�ȴ���һ�����ݿ⣡');history.go(-1);</script>");

		//�����̨��ַ Ĭ��admin.php
	$nowurl = WKROOT.'/'.$adminurl;
	$lasturl = WKROOT."/admin.php";
	@chmod(WKROOT,0777);
	if(!file_exists($lasturl))
	{
		die("<script>alert('����ļ�admin.php������,�����Ѿ�������!');history.go(-1);</script>");
	}
	if($lasturl <> $nowurl)
	{
		if(!rename($lasturl,$nowurl))
		{
		die("<script>alert('����ļ�admin.php��дȨ��!');history.go(-1);</script>");
		}
	}

    //������ݿ�汾��Ϣ
    $rs = mysql_query("SELECT VERSION();",$conn);
    $row = mysql_fetch_array($rs);
    $mysqlVersions = explode('.',trim($row[0]));
    $mysqlVersion = $mysqlVersions[0].".".$mysqlVersions[1];
    mysql_query("SET NAMES '$dblang',character_set_client=binary,sql_mode='';",$conn);
	
    $fp = fopen(dirname(__FILE__)."/config.ini.php","r");
    $configStr1 = fread($fp,filesize(dirname(__FILE__)."/config.ini.php"));
    fclose($fp);

    //config.ini.php
    $configStr1 = str_replace("~dbhost~",$dbhost,$configStr1);
    $configStr1 = str_replace("~dbname~",$dbname,$configStr1);
    $configStr1 = str_replace("~dbuser~",$dbuser,$configStr1);
    $configStr1 = str_replace("~dbpwd~",$dbpwd,$configStr1);
    $configStr1 = str_replace("~dbprefix~",$dbprefix,$configStr1);
    $configStr1 = str_replace("~dbcookie~",$cookieencode,$configStr1);
	$configStr1 = str_replace("~dblang~",$dblang,$configStr1);
	
    @chmod(WKCONFIG,0777);
    $fp = fopen(WKCONFIG."/config.ini.php","w") or die("<script>alert('д������ʧ�ܣ�����../Public/ConfigĿ¼�Ƿ��д�룡');history.go(-1);</script>");
    fwrite($fp,$configStr1);
    fclose($fp);
	
	$fp = fopen(dirname(__FILE__)."/basic.php","r");
    $configStr2 = fread($fp,filesize(dirname(__FILE__)."/basic.php"));
    fclose($fp);
	
	//basic.php
    $configStr2 = str_replace("~sitetitle~",$sitetitle,$configStr2);
	$configStr2 = str_replace("~sitetitle2~",$sitetitle2,$configStr2);
    $configStr2 = str_replace("~baseurl~",$baseurl,$configStr2);
	
	@chmod(WKBASIC,0777);
    $fp = fopen(WKBASIC."/basic.php","w") or die("<script>alert('д������ʧ�ܣ�����../Web/ConfĿ¼�Ƿ��д�룡');history.go(-1);</script>");
    fwrite($fp,$configStr2);
    fclose($fp);

    if($mysqlVersion >= 4.1)
    {
        $sql4tmp = "MyISAM DEFAULT CHARSET=".$dblang;
    }
  
    //�������ݱ�
  
    $query = '';
    $fp = fopen(dirname(__FILE__).'/sql-dftables.txt','r');
    while(!feof($fp))
    {
        $line = rtrim(fgets($fp,1024));
        if(preg_match("#;$#", $line))
        {
            $query .= $line."\n";
            $query = str_replace('#@__',$dbprefix,$query);
            if($mysqlVersion < 4.1)
            {
                $rs = mysql_query($query,$conn);
            } else {
                if(preg_match('#CREATE#i', $query))
                {
                    $rs = mysql_query(preg_replace("#MyISAM#i",$sql4tmp,$query),$conn);
                }
                else
                {
                    $rs = mysql_query($query,$conn);
                }
            }
            $query='';
        } else if(!preg_match("#^(\/\/|--)#", $line))
        {
            $query .= $line;
        }
    }
    fclose($fp);
    
    //����Ĭ������
    $query = '';
    $fp = fopen(dirname(__FILE__).'/sql-dfdata.txt','r');
    while(!feof($fp))
    {
        $line = rtrim(fgets($fp, 1024));
        if(preg_match("#;$#", $line))
        {
            $query .= $line;
            $query = str_replace('#@__',$dbprefix,$query);
            if($mysqlVersion < 4.1) $rs = mysql_query($query,$conn);
            else $rs = mysql_query(str_replace('#~lang~#',$dblang,$query),$conn);
            $query='';
        } else if(!preg_match("#^(\/\/|--)#", $line))
        {
            $query .= $line;
        }
    }
    fclose($fp);

    //��������
    $cquery = "Update `{$dbprefix}config` set sitetitle='{$sitetitle}' where id=1;";
    mysql_query($cquery,$conn);
    $cquery = "Update `{$dbprefix}config` set sitetitle2='{$sitetitle2}' where id=1;";
    mysql_query($cquery,$conn);
    $cquery = "Update `{$dbprefix}config` set siteurl='{$baseurl}' where id=1;";
    mysql_query($cquery,$conn);
    
    //���ӹ���Ա�ʺ�
    $adminquery = "INSERT INTO `{$dbprefix}admin` (`id`, `username`, `password`, `lastlogintime`, `lastloginip`, `status`) VALUES
(1, '$adminuser', '".md5('wk'.$adminpwd.'cms')."', ".time().", '127.0.0.1', 1);";
    mysql_query($adminquery,$conn);

	//��װ��������
    if($installdemo == 1)
    {
		$query = '';
		$fp = fopen(dirname(__FILE__).'/sql-dfdemo.txt','r');
		while(!feof($fp))
		{
			$line = rtrim(fgets($fp, 1024));
			if(preg_match("#;$#", $line))
			{
				$query .= $line;
				$query = str_replace('#@__',$dbprefix,$query);
				if($mysqlVersion < 4.1) $rs = mysql_query($query,$conn);
				else $rs = mysql_query(str_replace('#~lang~#',$dblang,$query),$conn);
				$query='';
			} else if(!preg_match("#^(\/\/|--)#", $line))
			{
				$query .= $line;
			}
		}
		fclose($fp);
	}
	else
	{
	//�������԰幦��
		$gbquery = "INSERT INTO `{$dbprefix}type` (`typeid`,`typename`, `keywords`, `description`, `ismenu`, `isindex`, `indexnum`, `islink`, `url`, `isuser`, `target`, `readme`, `drank`, `irank`, `fid`, `path`) VALUES
('1', '���Ա�', '', '������Ա�', 1, 0, 10, 1, '__WEB__/index.php?m=Guestbook', 1, 1, '', 6, 10, 0, '0');";
		mysql_query($gbquery,$conn);
	}
    //������װ����
        $fp = fopen($insLockfile,'w');
        fwrite($fp,'ok');
        fclose($fp);
        include('./templates/step-5.html');
        exit();
}/*------------------------
������ݿ��Ƿ���Ч
function _10_TestDbPwd()
------------------------*/
else if($step==10)
{
    header("Pragma:no-cache\r\n");
    header("Cache-Control:no-cache\r\n");
    header("Expires:0\r\n");
    $conn = @mysql_connect($dbhost,$dbuser,$dbpwd);
    if($conn)
    {
		if(empty($dbname)){
			echo "<font color='green'>��Ϣ��ȷ</font>";
		}else{
			$info = mysql_select_db($dbname,$conn)?"<font color='red'>���ݿ��Ѿ����ڣ�ϵͳ���������ݿ�</font>":"<font color='green'>���ݿⲻ����,ϵͳ���Զ�����</font>";
			echo $info;
		}
    }
    else
    {
        echo "<font color='red'>���ݿ�����ʧ�ܣ�</font>";
    }
    @mysql_close($conn);
    exit();
}
?>