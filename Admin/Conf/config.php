<?php
$config= require './Public/Config/config.ini.php';
$admin_config=array(
	'URL_MODEL'=>3,
	'APP_DEBUG'=>false,
	'TMPL_CACHE_TIME' => -1,// for 部署模式
	'USER_AUTH_ON'=>true,
	'USER_AUTH_TYPE'			=>2,		// 默认认证类型 1 登录认证 2 实时认证
	'USER_AUTH_KEY'			=>'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>'administrator',
	'USER_AUTH_MODEL'		=>'admin',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'	=>'/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'		=>'Public',		// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
	'NOT_AUTH_ACTION'		=>'verify',		// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
    'GUEST_AUTH_ON'          => false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'           =>    0,     // 游客的用户ID
    'DB_LIKE_FIELDS'=>'title|remark',
	'RBAC_ROLE_TABLE'=>'wk_role',
	'RBAC_USER_TABLE'	=>	'wk_role_admin',
	'RBAC_ACCESS_TABLE' =>	'wk_access',
	'RBAC_NODE_TABLE'	=> 'wk_node',
);
return array_merge($config,$admin_config);
?>