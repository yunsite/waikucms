<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function ���CMS����--��дAction

    @Filename WaikucmsAction.class.php $

    @Author pengyong $

    @Date 2011-11-14 08:45:20 $
*************************************************************/
//��дAction for FileAction 
class Waikucms extends Action{
	 /**
     +----------------------------------------------------------
     * Ĭ����ת���� ֧�ִ��������ȷ��ת
     * ����ģ����ʾ Ĭ��ΪpublicĿ¼�����successҳ��
     * ��ʾҳ��Ϊ������ ֧��ģ���ǩ
     +----------------------------------------------------------
     * @param string $message ��ʾ��Ϣ
     * @param Boolean $status ״̬
     * @param Boolean $ajax �Ƿ�ΪAjax��ʽ
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function _dispatch_jump($message,$status=1,$ajax=false)
    {
        // �ж��Ƿ�ΪAJAX����
        if($ajax || $this->isAjax()) $this->ajaxReturn($ajax,$message,$status);
        // ��ʾ����
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //��������˹رմ��ڣ�����ʾ��Ϻ��Զ��رմ���
        if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
        $this->assign('status',$status);   // ״̬
        $this->assign('message',$message);// ��ʾ��Ϣ
        //��֤������ܾ�̬����Ӱ��
        C('HTML_CACHE_ON',false);
        if($status) { //���ͳɹ���Ϣ
            // �ɹ�������Ĭ��ͣ��1��
            if(!$this->get('waitSecond'))    $this->assign('waitSecond',"1");
            // Ĭ�ϲ����ɹ��Զ����ز���ǰҳ��
            if(!$this->get('jumpUrl')) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
            $this->display('null');
        }else{
            //��������ʱ��Ĭ��ͣ��3��
            if(!$this->get('waitSecond'))    $this->assign('waitSecond',"3");
            // Ĭ�Ϸ�������Ļ��Զ�������ҳ
            if(!$this->get('jumpUrl')) $this->assign('jumpUrl',"javascript:history.back(-1);");
            $this->display('null');
        }
        if(C('LOG_RECORD')) Log::save();
        // ��ִֹ��  �����������ִ��
        exit ;
    }
    }
?>