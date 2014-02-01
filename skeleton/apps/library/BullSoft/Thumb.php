<?php
/* Thumb.php --- 
 * 
 * Filename: Thumb.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Sat Jan 19 10:31:42 2013 (+0800)
 * Version: master
 * Last-Updated: Mon Dec 23 18:43:44 2013 (+0800)
 *           By: Gu Weigang
 *     Update #: 35
 * 
 */

/* Change Log:
 * 
 * 
 */

/* This program is part of "Baidu Darwin PHP Software"; you can redistribute it and/or
 * modify it under the terms of the Baidu General Private License as
 * published by Baidu Campus.
 * 
 * You should have received a copy of the Baidu General Private License
 * along with this program; see the file COPYING. If not, write to
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The Peaple's
 * Republic of China, 100085.
 */

/* Code: */

namespace BullSoft;

class Thumb
{
	private $image;

    private $types=array(
        'image/jpeg' => "imagecreatefromjpeg",
        'image/png'  => "imagecreatefrompng",
        'image/gif'  => "imagecreatefromgif",
    );
    
    private $create = array(
        'image/jpeg' => "imagejpeg",
        'image/png'  => "imagepng",
        'image/gif'  => "imagegif",
    );
    
    private $type;
    
    public function __construct() {}
    
    /**
     *
     * 用$operation函数打开$filename文件
     * 
     * @param $filename
     *
     * @param $operation
     *
     * @return obj
     *
     */
    public function openwith($filename,$operation)
    {
    	$this->image=$operation($filename);
    	return ($this->image?true:false);
	}
    
    /**
     *
     * 读取硬盘上的图像文件（自适应类型）
     * 
     * @param $filename
     *
     * @return 失败返回false
     *
     */
	public function readfile($filename, $supportTypes)
    {
		$this->image=null;
		$info=getimagesize($filename);
        $this->type = $info['mime'];
		if (!$info) {
			//XXX "找不到文件"
			return false;
		}
        $types = explode(",", $supportTypes);
		if (!in_array($info['mime'],$types)) {
			//XXX "不支持改文件格式！".$info['mime']
			return false;
		}
		$this->openwith($filename, $this->types[$info['mime']]);
		return ($this->image?true:false);
	}
    
    /**
     *
     * 访问上传的文件
     * 
     * @param $tagname
     *
     * @return 失败返回false
     *
     */
	public function uploaded($tagname)
    {
		$this->image=null;

		if($_FILES[$tagname]['size']){
			return $this->readfile($_FILES[$tagname]['tmp_name']);
		} else {
            //XXX
			return false;
		}
		return ($this->image?true:false);
	}
    
	/**
     *
	 * 查询Image对象的尺寸
	 * 
	 * @param $obj
     *
	 * @return array 尺寸
     *
	 */
	public function size($obj=null)
    {
		if ($obj==null) {
			$obj=&$this->image;
        }
		if ($obj) {
			return array('width'  => imagesx($obj),
                         'height' => imagesy($obj),);
		} else {
			return false;
		}
	}
    
	/**
     *
	 * 将图像转换成一个缩略图（通常来说，缩略图的尺寸比原尺寸小）
	 * 
	 * @param $width
     *
	 * @param $height
     *
	 * @param $type 有两种参数cut和fit，分别是【填满尺寸】和【保留全部】
     *
	 * @return 失败返回false
     *
	 */
	public function tothumb($width,$height,$type='scale')
    {
		if ($type!='scale'&&$type!='cut'&&$type!='fit') {
			return false;
		}
		$oldsize=$this->size();
		if (!$oldsize) {
			return false;
		}
		$newwidth=$width;
		$newheight=$height;
		$oldwidth=$oldsize['width'];
		$oldheight=$oldsize['height'];
		
		if ($type=='cut') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;

			$x0=0;$y0=0;$x1=$oldwidth;$y1=$oldheight;
			
			if ($x1>$y1*$newwidth/$newheight)
				$x1=$y1*$newwidth/$newheight;
			if ($y1>$x1*$newheight/$newwidth)
				$y1=$x1*$newheight/$newwidth;
			
			if ($x1>$y1*$newwidth/$newheight)
				$x1=$y1*$newwidth/$newheight;
			if ($y1>$x1*$newheight/$newwidth)
				$y1=$x1*$newheight/$newwidth;
				
			if ($x1<$oldwidth) {
				$x0+=($oldwidth-$x1)/2;
				$x0=round($x0);
				$x1=round($x1);
			}
			if ($y1<$oldheight) {
				$y0+=($oldheight-$y1)/2;
				$y0=round($y0);
				$y1=round($y1);
			}

			$thumb=imagecreatetruecolor($newwidth,$newheight);
            $this->transparent($thumb, $newwidth-1, $newheight-1);
			imagecopyresized($thumb,$this->image,0,0,$x0,$y0,$newwidth,$newheight,$x1,$y1);
		}

        if ($type=='scale') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;
			$x0=0;
            $y0=0;
            $x1=$oldwidth;
            $y1=$oldheight;
            $a0=0;
            $b0=0;
            $a1=$newwidth;
            $b1=$newheight;

            if($a1 > $b1*$x1/$y1) {
                $a1 = $b1*$x1/$y1;
                $b1 = $a1*$y1/$x1;
            } else {
                $b1 = $a1*$y1/$x1;
                $a1 = $b1*$x1/$y1;
            }
            
            if($a1 < $width) {
                $a0 += ($newwidth-$a1)/2;
                $a0 = floor($a0);
                $a1 = ceil($a1);
            }

            if($b1 < $height) {
                $b0 += ($newheight - $b1)/2;
                $b0 = floor($b0);
                $b1 = ceil($b1);
            }

            $thumb=imagecreatetruecolor($newwidth,$newheight);
            
            $this->transparent($thumb, $newwidth-1, $newheight-1);

            // imagecopyresized($thumb,$this->image,0,0,$x0,$y0,$newwidth,$newheight,$x1,$y1);
			imagecopyresized($thumb,$this->image,$a0,$b0,0,0,$a1,$b1,$oldwidth,$oldheight);
		}
				
		if ($type=='fit') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;
			if ($newwidth>$newheight*$oldwidth/$oldheight)
				$newwidth=$newheight*$oldwidth/$oldheight;
			if ($newheight>$newwidth*$oldheight/$oldwidth)
				$newheight=$newwidth*$oldheight/$oldwidth;
				
			$newwidth=round($newwidth);
			$newheight=round($newheight);

			$thumb=imagecreatetruecolor($newwidth,$newheight);
            $this->transparent($thumb, $newwidth-1, $newheight-1);
			imagecopyresized($thumb,$this->image,0,0,0,0,$newwidth,$newheight,$oldwidth,$oldheight);
		}
		$this->image=$thumb;
		return true;
	}
    
	/**
     *
	 * 作为字符串输出
	 * 
	 * @return string
     *
	 */
	public function output ($quality)
    {
		if (empty($this->image)) return false;
		ob_start();
        $method = $this->create[$this->type];
        if ($method == "imagepng") {
            $quality = min(9, $quality/10);
        }
		$method($this->image, "", $quality);                
		$strTemp=ob_get_contents();
		ob_end_clean();
		return $strTemp;
	}
    
	/**
     *
	 * 将图像转换成一个缩略图（通常来说，缩略图的尺寸比原尺寸小）
	 * 
	 * @param $width
	 * @param $height
	 * @param $type 有三种参数scale、cut和fit，分别是「比例缩放」、「填满尺寸」和「保留全部」
	 * @return 失败返回false
     *
	 */
	public function tothumbHD($width,$height,$type='scale')
    {
		if ($type!='scale'&&$type!='cut'&&$type!='fit') {
			return false;
		}
		$oldsize=$this->size();
		if (!$oldsize) {
			return false;
		}
		$newwidth=$width;
		$newheight=$height;
		$oldwidth=$oldsize['width'];
		$oldheight=$oldsize['height'];

        if ($type=='cut') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;

			$x0=0;$y0=0;$x1=$oldwidth;$y1=$oldheight;
			
			if ($x1>$y1*$newwidth/$newheight)
				$x1=$y1*$newwidth/$newheight;
			if ($y1>$x1*$newheight/$newwidth)
				$y1=$x1*$newheight/$newwidth;
			
			if ($x1>$y1*$newwidth/$newheight)
				$x1=$y1*$newwidth/$newheight;
			if ($y1>$x1*$newheight/$newwidth)
				$y1=$x1*$newheight/$newwidth;
				
			if ($x1<$oldwidth) {
				$x0+=($oldwidth-$x1)/2;
				// $x1+=$x0;
				$x0=round($x0);
				$x1=round($x1);
			}
				
			if ($y1<$oldheight) {
				$y0+=($oldheight-$y1)/2;
				// $y1+=$y0;
				$y0=round($y0);
				$y1=round($y1);
			}

			$thumb=imagecreatetruecolor($newwidth,$newheight);
            $this->transparent($thumb, $newwidth-1, $newheight-1);
			imagecopyresampled($thumb,$this->image,0,0,$x0,$y0,$newwidth,$newheight,$x1,$y1);
		}
                
		if ($type=='scale') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;
			$x0=0;
            $y0=0;
            $x1=$oldwidth;
            $y1=$oldheight;
            $a0=0;
            $b0=0;
            $a1=$newwidth;
            $b1=$newheight;

            if($a1 > $b1*$x1/$y1) {
                $a1 = $b1*$x1/$y1;
                $b1 = $a1*$y1/$x1;
            } else {
                $b1 = $a1*$y1/$x1;
                $a1 = $b1*$x1/$y1;
            }
            
            if($a1 < $width) {
                $a0 += ($newwidth-$a1)/2;
                $a0 = floor($a0);
                $a1 = ceil($a1);
            }

            if($b1 < $height) {
                $b0 += ($newheight - $b1)/2;
                $b0 = floor($b0);
                $b1 = ceil($b1);
            }

			$thumb=imagecreatetruecolor($newwidth,$newheight);
            $this->transparent($thumb, $newwidth-1, $newheight-1);
			imagecopyresampled($thumb,$this->image,$a0,$b0,0,0,$a1,$b1,$oldwidth,$oldheight);
		}
				
		if ($type=='fit') {
			if ($newwidth>$oldwidth)
				$newwidth=$oldwidth;
			if ($newheight>$oldheight)
				$newheight=$oldheight;
				
			if ($newwidth>$newheight*$oldwidth/$oldheight)
				$newwidth=$newheight*$oldwidth/$oldheight;
			if ($newheight>$newwidth*$oldheight/$oldwidth)
				$newheight=$newwidth*$oldheight/$oldwidth;
				
			$newwidth=round($newwidth);
			$newheight=round($newheight);

			$thumb=imagecreatetruecolor($newwidth,$newheight);
            $this->transparent($thumb, $newwidth-1, $newheight-1);
			imagecopyresampled($thumb,$this->image,0,0,0,0,$newwidth,$newheight,$oldwidth,$oldheight);
			
			//锐化
			$image=$thumb;
			$thumb=imagecreatetruecolor($newwidth,$newheight);
			$cnt=0;
			$SHARP = '0.4';
			for ($x=0; $x<$newwidth; $x++){
                for ($y=0; $y<$newheight; $y++) {
                    $src_clr1 = imagecolorsforindex($thumb, imagecolorat($image, $x-1, $y-1));
                    $src_clr2 = imagecolorsforindex($thumb, imagecolorat($image, $x, $y));
                    $r = intval($src_clr2["red"]+$SHARP*($src_clr2["red"]-$src_clr1["red"]));
                    $g = intval($src_clr2["green"]+$SHARP*($src_clr2["green"]-$src_clr1["green"]));
                    $b = intval($src_clr2["blue"]+$SHARP*($src_clr2["blue"]-$src_clr1["blue"]));
                    $r = min(255, max($r, 0));
                    $g = min(255, max($g, 0));
                    $b = min(255, max($b, 0));
                    if (($DST_CLR=imagecolorexact($image, $r, $g, $b))==-1) {
                        $DST_CLR = imagecolorallocate($image, $r, $g, $b);
                    }
                    $cnt++;
                    imagesetpixel($thumb, $x, $y, $DST_CLR);
                }
			}
			
		}
		$this->image=$thumb;
		return true;
	}
	
	/**
     *
	 * 作为字符串输出(高质量)
	 * 
	 * @return string
     *
	 */
	public function outputHD ($quality)
    {
		if (empty($this->image)) return false;
		ob_start();
        $method = $this->create[$this->type];
        if ($method == "imagepng") {
            $quality = min(9, $quality/10);
        }
		$method($this->image, "", $quality);        
		$strTemp=ob_get_contents();
		ob_end_clean();
		return $strTemp;
	}

    /**
     *
     * 透明处理
     *
     * @var new_image GD image handler
     *
     * @var $width int New picture width
     * 
     * @var $height int New picture height
     *
     * @return bool ture
     *
     */
    protected function transparent(&$new_image, $width, $height)
    {
        // Preserve transparency
        $transparent_index = imagecolortransparent($this->image);
        if ($transparent_index >= 0) { // GIF
            imagepalettecopy($this->image, $new_image);
            imagefill($new_image, 0, 0, $transparent_index);
            imagecolortransparent($new_image, $transparent_index);
            imagetruecolortopalette($new_image, true, 256);
        } else { // PNG JPEG
            imagealphablending($new_image, false);
            imagesavealpha($new_image,true);
            
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagecolortransparent($new_image, $transparent);
            imagefilledrectangle($new_image, 0, 0, $width,$height, $transparent);
        }
        return true;
    }
    
	/**
     *
	 * 作为文件输出
	 * 
	 * @param $filename
     *
	 * @return 失败返回false
     *
	 */
	public function writefile ($filename, $quality)
    {
		if (empty($this->image)) return false;
        $method = $this->create[$this->type];
        if ($method == "imagepng") {
            $quality = min(9, $quality/10);
        }
		$method($this->image, $filename, $quality);
		return true;
	}

    public function getType()
    {
        return end(explode("/", $this->type));
    }
}

/* Thumb.php ends here */