<?php
//已改写 for 后台 分页 pengyong 2011-11-14 18:00:47
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
    // 起始行数
    public $firstRow	;
    // 列表每页显示行数
    public $listRows	;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页栏每页显示的页数
    protected $rollPage   ;
	// 分页显示定制
    protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows,$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = C('PAGE_ROLLPAGE');
        $this->listRows = !empty($listRows)?$listRows:C('PAGE_LISTROWS');
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
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
     * 分页显示输出
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
        //上下翻页字符串
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
            $prePage = "<li><a href='".$url."&".$p."=$preRow' >上".$this->rollPage."页</a></li>\n";
            $theFirst = "<li><a href='".$url."&".$p."=1' >".$this->config['first']."</a></li>\n";   
        }else{
             $theFirst ="<li><span>".$this->config['first']."</span></li>\n";
            $prePage ="<li><span>".$this->config['prev']."</span></li>\n";
			$theFirst = "<li><a href='".$url."&".$p."=1' >".$this->config['first']."</a></li>\n";
        }
        if($nowCoolPage == $this->coolPages){
			$nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<li><a href='".$url."&".$p."=$nextRow' >下".$this->rollPage."页</a></li>\n";
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
		//下拉选择分页效果
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