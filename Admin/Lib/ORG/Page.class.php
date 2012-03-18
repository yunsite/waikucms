<?php
//�Ѹ�д for ��̨ ��ҳ pengyong 2011-11-14 18:00:47
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class Page extends Think {
    // ��ʼ����
    public $firstRow	;
    // �б�ÿҳ��ʾ����
    public $listRows	;
    // ҳ����תʱҪ���Ĳ���
    public $parameter  ;
    // ��ҳ��ҳ����
    protected $totalPages  ;
    // ������
    protected $totalRows  ;
    // ��ǰҳ��
    protected $nowPage    ;
    // ��ҳ��������ҳ��
    protected $coolPages   ;
    // ��ҳ��ÿҳ��ʾ��ҳ��
    protected $rollPage   ;
	// ��ҳ��ʾ����
    protected $config  =	array('header'=>'����¼','prev'=>'��һҳ','next'=>'��һҳ','first'=>'��һҳ','last'=>'���һҳ','theme'=>' %totalRow% %header% %nowPage%/%totalPage% ҳ %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

    /**
     +----------------------------------------------------------
     * �ܹ�����
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  �ܵļ�¼��
     * @param array $listRows  ÿҳ��ʾ��¼��
     * @param array $parameter  ��ҳ��ת�Ĳ���
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows,$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = C('PAGE_ROLLPAGE');
        $this->listRows = !empty($listRows)?$listRows:C('PAGE_LISTROWS');
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //��ҳ��
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[C('VAR_PAGE')])?$_GET[C('VAR_PAGE')]:1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     +----------------------------------------------------------
     * ��ҳ��ʾ���
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p = C('VAR_PAGE');
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //���·�ҳ�ַ���
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<li><a href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a></li>\n";
        }else{
            $upPage="<li><span>".$this->config['prev']."</span></li>\n";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<li><a href='".$url."&".$p."=$downRow'>".$this->config['next']."</a></li>\n";
        }else{
            $downPage="<li><span>".$this->config['next']."</span></li>\n";
        }
        // << < > >>
        if($nowCoolPage == 1){
			$preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<li><a href='".$url."&".$p."=$preRow' >��".$this->rollPage."ҳ</a></li>\n";
            $theFirst = "<li><a href='".$url."&".$p."=1' >".$this->config['first']."</a></li>\n";   
        }else{
             $theFirst ="<li><span>".$this->config['first']."</span></li>\n";
            $prePage ="<li><span>".$this->config['prev']."</span></li>\n";
			$theFirst = "<li><a href='".$url."&".$p."=1' >".$this->config['first']."</a></li>\n";
        }
        if($nowCoolPage == $this->coolPages){
			$nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<li><a href='".$url."&".$p."=$nextRow' >��".$this->rollPage."ҳ</a></li>\n";
            $theEnd = "<li><a href='".$url."&".$p."=$theEndRow' >".$this->config['last']."</a></li>\n";
        }else{
        $nextPage ="<li><span>".$this->config['next']."</span></li>\n";
        $theEnd="<li><span>".$this->config['last']."</span></li>\n"; 
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<li><a href='".$url."&".$p."=$page'>".$page."</a></li>\n";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .="<li><span>".$page."</span></li>\n";
                }
            }
        }
		//����ѡ���ҳЧ��
		$allPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $allPage .="<option value='".$url."&".$p."=$page'>".$page."</option>";
                }else{
                    break;
                }
            }else{
                    $allPage .="<option value='".$url."&".$p."=$page' selected='selected'>".$page."</option>";
            }
        }
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%allPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$allPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}
?>