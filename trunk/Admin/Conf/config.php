<?php
$config= require './Public/Config/config.ini.php';
$admin_config=array(
	'URL_MODEL'=>3,
	'APP_DEBUG'=>false,
	'TMPL_CACHE_TIME' => -1,// for ����ģʽ
	'USER_AUTH_ON'=>true,
	'USER_AUTH_TYPE'			=>2,		// Ĭ����֤���� 1 ��¼��֤ 2 ʵʱ��֤
	'USER_AUTH_KEY'			=>'authId',	// �û���֤SESSION���
    'ADMIN_AUTH_KEY'			=>'administrator',
	'USER_AUTH_MODEL'		=>'admin',	// Ĭ����֤���ݱ�ģ��
	'AUTH_PWD_ENCODER'		=>'md5',	// �û���֤������ܷ�ʽ
	'USER_AUTH_GATEWAY'	=>'/Public/login',	// Ĭ����֤����
	'NOT_AUTH_MODULE'		=>'Public',		// Ĭ��������֤ģ��
	'REQUIRE_AUTH_MODULE'=>'',		// Ĭ����Ҫ��֤ģ��
	'NOT_AUTH_ACTION'		=>'verify',		// Ĭ��������֤����
	'REQUIRE_AUTH_ACTION'=>'',		// Ĭ����Ҫ��֤����
    'GUEST_AUTH_ON'          => false,    // �Ƿ����ο���Ȩ����
    'GUEST_AUTH_ID'           =>    0,     // �ο͵��û�ID
    'DB_LIKE_FIELDS'=>'title|remark',
	'RBAC_ROLE_TABLE'=>'wk_role',
	'RBAC_USER_TABLE'	=>	'wk_role_admin',
	'RBAC_ACCESS_TABLE' =>	'wk_access',
	'RBAC_NODE_TABLE'	=> 'wk_node',
);
return array_merge($config,$admin_config);
?>