<?php
// �Ѹ�д for ��̨��֤ pengyong 2011-11-14 18:00:11
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

/**
  +------------------------------------------------------------------------------
 * ͼ��������
  +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
  +------------------------------------------------------------------------------
 */
class Image extends Think {//�ඨ�忪ʼ

    /**
      +----------------------------------------------------------
     * ȡ��ͼ����Ϣ
     *
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $image ͼ���ļ���
      +----------------------------------------------------------
     * @return mixed
      +----------------------------------------------------------
     */

    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }

    /**
      +----------------------------------------------------------
     * ΪͼƬ���ˮӡ
      +----------------------------------------------------------
     * @static public
      +----------------------------------------------------------
     * @param string $source ԭ�ļ���
     * @param string $water  ˮӡͼƬ
     * @param string $$savename  ���ˮӡ���ͼƬ��
     * @param string $alpha  ˮӡ��͸����
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    static public function water($source, $water, $savename=null, $alpha=80) {
        //����ļ��Ƿ����
        if (!file_exists($source) || !file_exists($water))
            return false;

        //ͼƬ��Ϣ
        $sInfo = self::getImageInfo($source);
        $wInfo = self::getImageInfo($water);

        //���ͼƬС��ˮӡͼƬ��������ͼƬ
        if ($sInfo["width"] < $wInfo["width"] || $sInfo['height'] < $wInfo['height'])
            return false;

        //����ͼ��
        $sCreateFun = "imagecreatefrom" . $sInfo['type'];
        $sImage = $sCreateFun($source);
        $wCreateFun = "imagecreatefrom" . $wInfo['type'];
        $wImage = $wCreateFun($water);

        //�趨ͼ��Ļ�ɫģʽ
        imagealphablending($wImage, true);

        //ͼ��λ��,Ĭ��Ϊ���½��Ҷ���
        $posY = $sInfo["height"] - $wInfo["height"];
        $posX = $sInfo["width"] - $wInfo["width"];

        //���ɻ��ͼ��
        imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);

        //���ͼ��
        $ImageFun = 'Image' . $sInfo['type'];
        //���û�и��������ļ�����Ĭ��Ϊԭͼ����
        if (!$savename) {
            $savename = $source;
            @unlink($source);
        }
        //����ͼ��
        $ImageFun($sImage, $savename);
        imagedestroy($sImage);
    }

    function showImg($imgFile, $text='', $x='10', $y='10', $alpha='50') {
        //��ȡͼ���ļ���Ϣ
        //2007/6/26 ����ͼƬˮӡ�����$textΪͼƬ������·������
        $info = Image::getImageInfo($imgFile);
        if ($info !== false) {
            $createFun = str_replace('/', 'createfrom', $info['mime']);
            $im = $createFun($imgFile);
            if ($im) {
                $ImageFun = str_replace('/', '', $info['mime']);
                //ˮӡ��ʼ
                if (!empty($text)) {
                    $tc = imagecolorallocate($im, 0, 0, 0);
                    if (is_file($text) && file_exists($text)) {//�ж�$text�Ƿ���ͼƬ·��
                        // ȡ��ˮӡ��Ϣ
                        $textInfo = Image::getImageInfo($text);
                        $createFun2 = str_replace('/', 'createfrom', $textInfo['mime']);
                        $waterMark = $createFun2($text);
                        //$waterMark=imagecolorallocatealpha($text,255,255,0,50);
                        $imgW = $info["width"];
                        $imgH = $info["width"] * $textInfo["height"] / $textInfo["width"];
                        //$y	=	($info["height"]-$textInfo["height"])/2;
                        //����ˮӡ����ʾλ�ú�͸����֧�ָ���ͼƬ��ʽ
                        imagecopymerge($im, $waterMark, $x, $y, 0, 0, $textInfo['width'], $textInfo['height'], $alpha);
                    } else {
                        imagestring($im, 80, $x, $y, $text, $tc);
                    }
                    //ImageDestroy($tc);
                }
                //ˮӡ����
                if ($info['type'] == 'png' || $info['type'] == 'gif') {
                    imagealphablending($im, FALSE); //ȡ��Ĭ�ϵĻ�ɫģʽ
                    imagesavealpha($im, TRUE); //�趨���������� alpha ͨ����Ϣ
                }
                Header("Content-type: " . $info['mime']);
                $ImageFun($im);
                @ImageDestroy($im);
                return;
            }

            //����ͼ��
            $ImageFun($sImage, $savename);
            imagedestroy($sImage);
            //��ȡ���ߴ���ͼ���ļ�ʧ�������ɿհ�PNGͼƬ
            $im = imagecreatetruecolor(80, 30);
            $bgc = imagecolorallocate($im, 255, 255, 255);
            $tc = imagecolorallocate($im, 0, 0, 0);
            imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
            imagestring($im, 4, 5, 5, "no pic", $tc);
            Image::output($im);
            return;
        }
    }

    /**
      +----------------------------------------------------------
     * ��������ͼ
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $image  ԭͼ
     * @param string $type ͼ���ʽ
     * @param string $thumbname ����ͼ�ļ���
     * @param string $maxWidth  ���
     * @param string $maxHeight  �߶�
     * @param string $position ����ͼ����Ŀ¼
     * @param boolean $interlace ���ø���ɨ��
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     */
    static function thumb($image, $thumbname, $type='', $maxWidth=200, $maxHeight=50, $interlace=true) {
        // ��ȡԭͼ��Ϣ
        $info = Image::getImageInfo($image);
        if ($info !== false) {
            $srcWidth = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type) ? $info['type'] : $type;
            $type = strtolower($type);
            $interlace = $interlace ? 1 : 0;
            unset($info);
            $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight); // �������ű���
            if ($scale >= 1) {
                // ����ԭͼ��С��������
                $width = $srcWidth;
                $height = $srcHeight;
            } else {
                // ����ͼ�ߴ�
                $width = (int) ($srcWidth * $scale);
                $height = (int) ($srcHeight * $scale);
            }

            // ����ԭͼ
            $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
            $srcImg = $createFun($image);

            //��������ͼ
            if ($type != 'gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            // ����ͼƬ
            if (function_exists("ImageCopyResampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            if ('gif' == $type || 'png' == $type) {
                //imagealphablending($thumbImg, false);//ȡ��Ĭ�ϵĻ�ɫģʽ
                //imagesavealpha($thumbImg,true);//�趨���������� alpha ͨ����Ϣ
                $background_color = imagecolorallocate($thumbImg, 0, 255, 0);  //  ָ��һ����ɫ
                imagecolortransparent($thumbImg, $background_color);  //  ����Ϊ͸��ɫ����ע�͵������������ɫ��ͼ
            }

            // ��jpegͼ�����ø���ɨ��
            if ('jpg' == $type || 'jpeg' == $type)
                imageinterlace($thumbImg, $interlace);

            //$gray=ImageColorAllocate($thumbImg,255,0,0);
            //ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
            // ����ͼƬ
            $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
            $imageFun($thumbImg, $thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }

    /**
      +----------------------------------------------------------
     * ���ݸ������ַ�������ͼ��
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $string  �ַ���
     * @param string $size  ͼ���С width,height ���� array(width,height)
     * @param string $font  ������Ϣ fontface,fontsize ���� array(fontface,fontsize)
     * @param string $type ͼ���ʽ Ĭ��PNG
     * @param integer $disturb �Ƿ���� 1 ����� 2 �߸��� 3 ���ϸ��� 0 �޸���
     * @param bool $border  �Ƿ�ӱ߿� array(color)
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static function buildString($string, $rgb=array(), $filename='', $type='png', $disturb=1, $border=true) {
        if (is_string($size))
            $size = explode(',', $size);
        $width = $size[0];
        $height = $size[1];
        if (is_string($font))
            $font = explode(',', $font);
        $fontface = $font[0];
        $fontsize = $font[1];
        $length = strlen($string);
        $width = ($length * 9 + 10) > $width ? $length * 9 + 10 : $width;
        $height = 22;
        if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
            $im = @imagecreatetruecolor($width, $height);
        } else {
            $im = @imagecreate($width, $height);
        }
        if (empty($rgb)) {
            $color = imagecolorallocate($im, 102, 104, 104);
        } else {
            $color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
        }
        $backColor = imagecolorallocate($im, 255, 255, 255);    //����ɫ�������
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //�߿�ɫ
        $pointColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));                 //����ɫ

        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        @imagestring($im, 5, 5, 3, $string, $color);
        if (!empty($disturb)) {
            // ��Ӹ���
            if ($disturb = 1 || $disturb = 3) {
                for ($i = 0; $i < 25; $i++) {
                    imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointColor);
                }
            } elseif ($disturb = 2 || $disturb = 3) {
                for ($i = 0; $i < 10; $i++) {
                    imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $pointColor);
                }
            }
        }
        Image::output($im, $type, $filename);
    }

    /**
      +----------------------------------------------------------
     * ����ͼ����֤��
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $length  λ��
     * @param string $mode  ����
     * @param string $type ͼ���ʽ
     * @param string $width  ���
     * @param string $height  �߶�
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static function buildImageVerify($length=4, $mode=1, $type='png', $width=48, $height=22, $verifyName='verify') {
        import('@.ORG.String');
        $randval = String::rand_string($length, $mode);
        $_SESSION[$verifyName] = md5($randval);
        $width = ($length * 10 + 10) > $width ? $length * 10 + 10 : $width;
        if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
            $im = @imagecreatetruecolor($width, $height);
        } else {
            $im = @imagecreate($width, $height);
        }
        $r = Array(225, 255, 255, 223);
        $g = Array(225, 236, 237, 255);
        $b = Array(225, 236, 166, 125);
        $key = mt_rand(0, 3);

        $backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);    //����ɫ�������
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //�߿�ɫ
        $pointColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));                 //����ɫ

        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        $stringColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        // ����
        for ($i = 0; $i < 10; $i++) {
            $fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $fontcolor);
        }
        for ($i = 0; $i < 25; $i++) {
            $fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointColor);
        }
        for ($i = 0; $i < $length; $i++) {
            imagestring($im, 14, $i * 15 + 5, 1, $randval{$i}, $stringColor);
        }
//        @imagestring($im, 5, 5, 3, $randval, $stringColor);
        Image::output($im, $type);
    }

    // ������֤��
    static function GBVerify($length=4, $type='png', $width=180, $height=50, $fontface='simhei.ttf', $verifyName='verify') {
        import('@.ORG.String');
        $code = String::rand_string($length, 4);
        $width = ($length * 45) > $width ? $length * 45 : $width;
        $_SESSION[$verifyName] = md5($code);
        $im = imagecreatetruecolor($width, $height);
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //�߿�ɫ
        $bkcolor = imagecolorallocate($im, 250, 250, 250);
        imagefill($im, 0, 0, $bkcolor);
        @imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        // ����
        for ($i = 0; $i < 15; $i++) {
            $fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $fontcolor);
        }
        for ($i = 0; $i < 255; $i++) {
            $fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $fontcolor);
        }
        if (!is_file($fontface)) {
            $fontface = dirname(__FILE__) . "/" . $fontface;
        }
        for ($i = 0; $i < $length; $i++) {
            $fontcolor = imagecolorallocate($im, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120)); //������֤�����������ɫ���
            $codex = msubstr($code, $i, 1);
            imagettftext($im, mt_rand(16, 20), mt_rand(-60, 60), 40 * $i + 20, mt_rand(30, 35), $fontcolor, $fontface, $codex);
        }
        Image::output($im, $type);
    }

    /**
      +----------------------------------------------------------
     * ��ͼ��ת�����ַ���ʾ
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $image  Ҫ��ʾ��ͼ��
     * @param string $type  ͼ�����ͣ�Ĭ���Զ���ȡ
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static function showASCIIImg($image, $string='', $type='') {
        $info = Image::getImageInfo($image);
        if ($info !== false) {
            $type = empty($type) ? $info['type'] : $type;
            unset($info);
            // ����ԭͼ
            $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
            $im = $createFun($image);
            $dx = imagesx($im);
            $dy = imagesy($im);
            $i = 0;
            $out = '<span style="padding:0px;margin:0;line-height:100%;font-size:1px;">';
            set_time_limit(0);
            for ($y = 0; $y < $dy; $y++) {
                for ($x = 0; $x < $dx; $x++) {
                    $col = imagecolorat($im, $x, $y);
                    $rgb = imagecolorsforindex($im, $col);
                    $str = empty($string) ? '*' : $string[$i++];
                    $out .= sprintf('<span style="margin:0px;color:#%02x%02x%02x">' . $str . '</span>', $rgb['red'], $rgb['green'], $rgb['blue']);
                }
                $out .= "<br>\n";
            }
            $out .= '</span>';
            imagedestroy($im);
            return $out;
        }
        return false;
    }

    /**
      +----------------------------------------------------------
     * ���ɸ߼�ͼ����֤��
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $type ͼ���ʽ
     * @param string $width  ���
     * @param string $height  �߶�
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static function showAdvVerify($type='png', $width=180, $height=40, $verifyName='verifyCode') {
        $rand = range('a', 'z');
        shuffle($rand);
        $verifyCode = array_slice($rand, 0, 10);
        $letter = implode(" ", $verifyCode);
        $_SESSION[$verifyName] = $verifyCode;
        $im = imagecreate($width, $height);
        $r = array(225, 255, 255, 223);
        $g = array(225, 236, 237, 255);
        $b = array(225, 236, 166, 125);
        $key = mt_rand(0, 3);
        $backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //�߿�ɫ
        imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        $numberColor = imagecolorallocate($im, 255, rand(0, 100), rand(0, 100));
        $stringColor = imagecolorallocate($im, rand(0, 100), rand(0, 100), 255);
        // ��Ӹ���
        /*
          for($i=0;$i<10;$i++){
          $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
          imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
          }
          for($i=0;$i<255;$i++){
          $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
          imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
          } */
        imagestring($im, 5, 5, 1, "0 1 2 3 4 5 6 7 8 9", $numberColor);
        imagestring($im, 5, 5, 20, $letter, $stringColor);
        Image::output($im, $type);
    }

    /**
      +----------------------------------------------------------
     * ����UPC-A������
      +----------------------------------------------------------
     * @static
      +----------------------------------------------------------
     * @param string $type ͼ���ʽ
     * @param string $type ͼ���ʽ
     * @param string $lw  ��Ԫ���
     * @param string $hi   ����߶�
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static function UPCA($code, $type='png', $lw=2, $hi=100) {
        static $Lencode = array('0001101', '0011001', '0010011', '0111101', '0100011',
    '0110001', '0101111', '0111011', '0110111', '0001011');
        static $Rencode = array('1110010', '1100110', '1101100', '1000010', '1011100',
    '1001110', '1010000', '1000100', '1001000', '1110100');
        $ends = '101';
        $center = '01010';
        /* UPC-A Must be 11 digits, we compute the checksum. */
        if (strlen($code) != 11) {
            die("UPC-A Must be 11 digits.");
        }
        /* Compute the EAN-13 Checksum digit */
        $ncode = '0' . $code;
        $even = 0;
        $odd = 0;
        for ($x = 0; $x < 12; $x++) {
            if ($x % 2) {
                $odd += $ncode[$x];
            } else {
                $even += $ncode[$x];
            }
        }
        $code.= ( 10 - (($odd * 3 + $even) % 10)) % 10;
        /* Create the bar encoding using a binary string */
        $bars = $ends;
        $bars.=$Lencode[$code[0]];
        for ($x = 1; $x < 6; $x++) {
            $bars.=$Lencode[$code[$x]];
        }
        $bars.=$center;
        for ($x = 6; $x < 12; $x++) {
            $bars.=$Rencode[$code[$x]];
        }
        $bars.=$ends;
        /* Generate the Barcode Image */
        if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($lw * 95 + 30, $hi + 30);
        } else {
            $im = imagecreate($lw * 95 + 30, $hi + 30);
        }
        $fg = ImageColorAllocate($im, 0, 0, 0);
        $bg = ImageColorAllocate($im, 255, 255, 255);
        ImageFilledRectangle($im, 0, 0, $lw * 95 + 30, $hi + 30, $bg);
        $shift = 10;
        for ($x = 0; $x < strlen($bars); $x++) {
            if (($x < 10) || ($x >= 45 && $x < 50) || ($x >= 85)) {
                $sh = 10;
            } else {
                $sh = 0;
            }
            if ($bars[$x] == '1') {
                $color = $fg;
            } else {
                $color = $bg;
            }
            ImageFilledRectangle($im, ($x * $lw) + 15, 5, ($x + 1) * $lw + 14, $hi + 5 + $sh, $color);
        }
        /* Add the Human Readable Label */
        ImageString($im, 4, 5, $hi - 5, $code[0], $fg);
        for ($x = 0; $x < 5; $x++) {
            ImageString($im, 5, $lw * (13 + $x * 6) + 15, $hi + 5, $code[$x + 1], $fg);
            ImageString($im, 5, $lw * (53 + $x * 6) + 15, $hi + 5, $code[$x + 6], $fg);
        }
        ImageString($im, 4, $lw * 95 + 17, $hi - 5, $code[11], $fg);
        /* Output the Header and Content. */
        Image::output($im, $type);
    }

    static function output($im, $type='png', $filename='') {
        header("Content-type: image/" . $type);
        $ImageFun = 'image' . $type;
        if (empty($filename)) {
            $ImageFun($im);
        } else {
            $ImageFun($im, $filename);
        }
        imagedestroy($im);
    }

}

//�ඨ�����
?>