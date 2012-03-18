<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 前台 入口文件

    @Filename index.php $

    @Author pengyong $

    @Date 2011-11-18 13:48:13 $
*************************************************************/
if(!file_exists(dirname(__FILE__).'/Web/Conf/basic.php')) header('Location:Install/index.php');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('THINK_PATH','./Core');
define('APP_NAME','Web');
define('APP_PATH','./Web');
require(THINK_PATH."/Core.php");
define ('RUNTIME_ALLINONE', true); 
APP::run();