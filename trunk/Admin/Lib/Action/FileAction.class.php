<?php
/***********************************************************
    [WaiKuCms] (C)2011 - 2011 waikucms.com
    
	@function �ļ��ϴ�

    @Filename FileAction.class.php $

    @Author pengyong $

    @Date 2011-11-23 10:11:22 $
*************************************************************/
import('@.ORG.Waikucms');
import('@.ORG.UploadFile');
class FileAction extends Waikucms
{
	//Ĭ���ϴ��õ�
	public function hd()
	{
		$this->display('hd');
	}
	//�ϴ�logo
	public function logo()
	{
		$this->display('logo');
	}
	//�ϴ�ˮӡͼ
	public function watermark()
	{
		$this->display('watermark');
	}
	//�ϴ����ͼƬ
	public function ad()
	{
		$this->display('ad');
	}
	//�ϴ�����ͼƬ
	public function link()
	{
		$this->display('link');
	}
	//�ϴ�ͼƬ����ͼ
	public function thumb()
	{
		$this->display('thumb');
	}
	//�ϴ�����
	public function attach()
	{
		$this->display('attach');
	}
	//�ϴ��õ�ͼƬ
	public function upload()
	{
		$this->upmethod('up','c');
	}

	//�ϴ�logoͼƬ
	public function uploadlogo()
	{
		$this->upmethod('uplogo','l');
	}
	//�ϴ����ͼƬ
	public function uploadad()
	{
		$this->upmethod('upad','a');
	}
	//�ϴ���������ͼƬ
	public function uploadlink()
	{
		$this->upmethod('uplink','k');
	}
	//�ϴ�����ͼ
	public function uploadthumb()
	{
		$this->upmethod('upthumb','t');
	}
	//�ϴ�����
	public function uploadattach()
	{
	$this->upmethod('upattach','at');
	}
	//�ϴ�ˮӡͼƬ
	public function uploadwatermark()
	{
		$this->upmethod('upwatermark','w');
	}

	//�õƴ��봦��
	private function c($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			$js.="<script language=javascript>parent.document.myform.Pic.value='hd_".$data[0]['savename']."';</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//logo���봦��
	private function l($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			$js.="<script language=javascript>parent.frm.oSiteLogo.value='".$data[0]['savename']."';</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//ˮӡ���봦��
	private function w($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			$js.="<script language=javascript>parent.frm.owatermarkimg.value='".$data[0]['savename']."';</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//�����봦��
	private function a($data)
	{
		if(!empty($data[0]['savename']))
		{
			$js='';
			$type = M('config');
			$siteurl = $type->where('id=1')->getField('siteurl');
			$js.="<script language=javascript>parent.document.myform.Content.value=parent.document.myform.Content.value+'<img src=\"".__PUBLIC__."/Uploads/ad/".$data[0]['savename']."\"/>';</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//�������봦��
	private function k($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			$js.="<script language=javascript>parent.document.myform.LogoUrl.value='".__PUBLIC__."/Uploads/link/{$data[0]['savename']}';</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//����ͼ���봦��
	private function t($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			$js.="<script language=javascript>parent.document.myform.Images.value='".__PUBLIC__."/Uploads/thumb/thumb_{$data[0]['savename']}';</script>";
			$js.="<script language=javascript>parent.document.myform.Useimg.checked=true;</script>";
			$js.="<script language=javascript>parent.KE.insertHtml('Content', '<div align=\"center\"><img src=\"".__PUBLIC__."/Uploads/thumb/thumb_{$data[0]['savename']}\"/></div>');</script>";
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}
	//�������봦��
	private function at($data)
	{
		$js='';
		if(!empty($data[0]['savename']))
		{
			switch($data[0]['extension'])
			{
				case 'zip':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/zip.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'tar.gz':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/zip.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case '7z':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/zip.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'rar':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/rar.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'doc':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/doc.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'docx':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/doc.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'ppt':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/ppt.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'pptx':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/ppt.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'cls':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/cls.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'clsx':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/cls.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'txt':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/txt.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'pdf':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/pdf.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				case 'swf':
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<br>��������:<img src=\"".__PUBLIC__."/Editor/mini/swf.gif\"/><a href=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\">".$data[0]['savename']."</a></br>');</script>";
					break;
				default:
					$js.="<script language=javascript>parent.KE.insertHtml('Content', '<img src=\"".__PUBLIC__."/Uploads/attach/".$data[0]['savename']."\" />');</script>";
				//default Ĭ��Ϊgif,png,jpg��ͼƬ
			}
			$this->assign('js',$js);
			return true;
		}
		else
		{
			return false;
		}
	}


	//*********************������ִ���ϴ��ķ���**************************

	//�õ��ϴ�����
	private function up()
	{
		$upload=new UploadFile();
		$upload->maxSize='204800';  
		$upload->savePath='./Public/Uploads/';       
		$upload->saveRule= time;   
		$upload->uploadReplace=true;     
		$upload->allowExts=array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes=array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->imageClassPath = '@.ORG.Image';
		$upload->thumb=true;   //�Ƿ���ͼƬ�ļ�����,true��ʾ����
		$upload->thumbMaxWidth='300';  //���ִ���ʽ�����������ϣ���ж�����Ǿ��ڴ˴�����,�ָ�д�϶������
		$upload->thumbMaxHeight='280';	
		$upload->thumbPrefix='hd_';//����ͼ�ļ�ǰ׺
		$upload->thumbPath='./Public/Uploads/hd/' ; 
		$upload->thumbRemoveOrigin=1;  
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			$config = F('basic','','./Web/Conf/');
			if($config['watermark'] == 1)
			{
				Image::water($info[0]['savepath'].'/hd/hd_'.$info[0]['savename'], './Public/Uploads/logo/'.$config['watermarkimg']);
			}
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}
	//logo�ϴ�����
	private function uplogo()
	{
		$upload=new UploadFile();
		$upload->maxSize='204800';  
		$upload->savePath='./Public/Uploads/logo/';       
		$upload->saveRule='logo_'.date('YmdHis');   
		$upload->uploadReplace=true;     
		$upload->allowExts=array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes=array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->autoSub=false; //�Ƿ�ʹ����Ŀ¼���б����ϴ��ļ�
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}

	//ˮӡ�ϴ�����
	private function upwatermark()
	{
		$upload=new UploadFile();
		$upload->maxSize='204800';  
		$upload->savePath='./Public/Uploads/logo/';       
		$upload->saveRule='watermark_'.date('YmdHis');   
		$upload->uploadReplace=true;     
		$upload->allowExts=array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes=array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->autoSub=false; //�Ƿ�ʹ����Ŀ¼���б����ϴ��ļ�
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}
	private function upad()
	{
		$upload=new UploadFile();
		$upload->maxSize= '204800';  
		$upload->savePath= './Public/Uploads/ad/'; 
		$upload->saveRule= time;   
		$upload->uploadReplace= true;     
		$upload->allowExts= array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes= array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->autoSub=false; //�Ƿ�ʹ����Ŀ¼���б����ϴ��ļ�
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			$config = F('basic','','./Web/Conf/');
			if($config['watermark'] == 1)
			{
				import('@.ORG.Image');
				Image::water($info[0]['savepath'].$info[0]['savename'], './Public/Uploads/logo/'.$config['watermarkimg']);
			}
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}


	private function uplink()
	{
		$upload=new UploadFile();
		$upload->maxSize='204800';  
		$upload->savePath='./Public/Uploads/link/'; 
		$upload->saveRule= time;   
		$upload->uploadReplace=true;     
		$upload->allowExts=array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes=array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->autoSub=false; //�Ƿ�ʹ����Ŀ¼���б����ϴ��ļ�
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}


	private function upthumb()
	{
		$upload=new UploadFile();
		$upload->maxSize='204800';  
		$upload->savePath='./Public/Uploads/';       
		$upload->saveRule= time;   
		$upload->uploadReplace=true;     
		$upload->allowExts=array('jpg','jpeg','png','gif');     //׼���ϴ����ļ���׺
		$upload->allowTypes=array('image/jpeg','image/pjpeg','image/png','image/gif','image/x-png');//׼���ϴ����ļ�����
		$upload->imageClassPath = '@.ORG.Image';
		$upload->thumb=true;   //�Ƿ���ͼƬ�ļ�����,true��ʾ����
		$upload->thumbMaxWidth='500';  //���ִ���ʽ�����������ϣ���ж�����Ǿ��ڴ˴�����,�ָ�д�϶������
		$upload->thumbMaxHeight='400';	
		$upload->thumbPrefix='thumb_';//����ͼ�ļ�ǰ׺
		$upload->thumbPath='./Public/Uploads/thumb/' ; 
		$upload->thumbRemoveOrigin=1;  		
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			$config = F('basic','','./Web/Conf/');
			if($config['watermark'] == 1)
			{
				Image::water($info[0]['savepath'].'/thumb/thumb_'.$info[0]['savename'], $info[0]['savepath'].'/logo/'.$config['watermarkimg']);
			}
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}


	//�ϴ���������
	private function upattach()
	{
		$upload=new UploadFile();
		$upload->maxSize='2048000';  
		$upload->savePath='./Public/Uploads/attach/';       
		$upload->saveRule= time;   
		$upload->uploadReplace = true; 
		$upload->allowExts = array('zip','rar','txt','ppt','pptx','cls','clsx','doc','docx','swf','jpg','png','gif','tar.gz','.7z');     //׼���ϴ����ļ���׺
		if($upload->upload())
		{
			$info=$upload->getUploadFileInfo();
			if($info[0]['extension'] == 'jpg' || $info[0]['extension']=='png' || $info[0]['extension']=='gif')
			{
				$config = F('basic','','./Web/Conf/');
				if($config['watermark']== 1)
				{
					import('@.ORG.Image');
					Image::water($info[0]['savepath'].$info[0]['savename'], './Public/Uploads/logo/'.$config['watermarkimg']);
				}
			}
			return $info;
		}
		else
		{
			$this->error($upload->getErrorMsg());
		}
	}

	//�ϴ�����,��ȡ��������
	private function upmethod($upload,$method)
	{
		if(empty($_FILES))
		{
			$this->error('����ѡ���ϴ��ļ�');
			
		}
		$a=$this->$upload();
		if(isset($a))
		{
			if($this->$method($a))
			{
				$this->success('�ϴ��ɹ�');
			}
			else
			{
				$this->error('�����ı���ʧ��');
			}
		}
		else
		{
			$this->error('�ϴ��ļ����쳣����ϵͳ����Ա��ϵ');
		}
		
	}
}
?>