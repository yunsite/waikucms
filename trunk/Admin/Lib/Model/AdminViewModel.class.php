<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com

	@function ����Ա��ͼģ�� Model

    @Filename AdminViewModel.class.php $

    @Author pengyong $

    @Date 2011-11-17 15:23:44 $
*************************************************************/
class AdminViewModel extends ViewModel
{
	protected $viewFields = array( 

		'admin' => array('id','username','lastlogintime','lastloginip','status','_type'=>'LEFT'), 

		'role_admin' => array('role_id', '_on' => 'admin.id=role_admin.user_id'), 
	); 
}
?>