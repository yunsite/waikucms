<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ϵͳRBAC��֤

    @Filename CommonAction.class.php $

    @Author pengyong $

    @Date 2011-11-17 14:57:09 $
*************************************************************/
class CommonAction extends Action
{
	function _initialize() 
	{
		//�ȼ��cookie
		if(!Cookie::is_set($_SESSION['cookietime']))
		{
			redirect (PHP_FILE .C('USER_AUTH_GATEWAY'));
		}
		else
		{
			//����cookie��Ϣ
			Cookie::set($_SESSION['cookietime'],'1',60*60*3);
		}
		
		// �û�Ȩ�޼��
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) 
		{
			import ('@.ORG.RBAC');
			if (!RBAC::AccessDecision()) 
			{
				//�����֤ʶ���
				if (! $_SESSION [C('USER_AUTH_KEY')]) 
				{
					//��ת����֤����
					redirect (PHP_FILE .C('USER_AUTH_GATEWAY'));
				}
				// û��Ȩ�� �׳�����
				if (C('RBAC_ERROR_PAGE')) 
				{
					// ����Ȩ�޴���ҳ��
					redirect (C('RBAC_ERROR_PAGE') );
				}
				else
				{
					if (C('GUEST_AUTH_ON')) 
					{
						$this->assign ( 'jumpUrl', PHP_FILE .C('USER_AUTH_GATEWAY') );
					}
					// ��ʾ������Ϣ
					$this->error (L('_VALID_ACCESS_'));
				}
			}
		}
	}
}
?>