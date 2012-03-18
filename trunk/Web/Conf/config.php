<?php
$config= require './Public/Config/config.ini.php';
$web_config=array(
	'URL_MODEL'=>3,//rewrite模式改为2
	'URL_ROUTER_ON'=>true,
	'URL_HTML_SUFFIX'=>'.html',
	'URL_PATHINFO_DEPR'=>'_',
	'TMPL_FILE_DEPR'=>'_', 
	'DEFAULT_THEME'=>'default',
	'TMPL_CACHE_TIME' => -1,//部署模式 模板永久缓存
	'URL_CASE_INSENSITIVE' =>true,//URL不区分大小写
	'TMPL_PARSE_STRING'=> array( 
    '__WEB__'=>__ROOT__.'',
	'__ARTICLE__'=>__ROOT__.'/index.php?s=articles_',
	'__TYPE__'=>__ROOT__.'/index.php?s=lists_',
	'__VOTE__'=>__ROOT__.'/index.php?s=votes_',
	'__TPL__'=>__ROOT__.'/Web/Tpl',
), 
);
return array_merge($config,$web_config);
?>