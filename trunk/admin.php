<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function 后台入口文件

    @Filename admin.php $

    @Author pengyong $

    @Date 2011-11-18 13:48:44 $
*************************************************************/
if(!file_exists(dirname(__FILE__).'/Web/Conf/basic.php')) header('Location:Install/index.php');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('THINK_PATH', './Core');
define('APP_NAME', 'Admin');
define('APP_PATH', './Admin'); 
define('NO_CACHE_RUNTIME', true);
// for development
//define('STRIP_RUNTIME_SPACE',false);
require(THINK_PATH."/Core.php");
App::run();
?>