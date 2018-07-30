<?php

namespace core\library;

/**
 * Class Image
 * @package core\library
 */
class Image
{
	public $jpeg_quality = 80;
	public $image_replace = false;

	private $mem_types = array('','gif','jpeg','png');

    /**
     * @param $width
     * @param $height
     * @param bool $use_alpha
     * @return resource
     */
	private function blank( $width,$height,$use_alpha = false )
	{
		$image = imagecreatetruecolor($width, $height);
		if( $use_alpha ){
			imagesavealpha($image, true);
			$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
			imagefill($image, 0, 0, $transparent);
		}
		return $image;
	}

    /**
     * @param $ext
     * @param $file
     * @return string
     * @throws \Exception
     */
	public function createFrom( $ext,$file )
	{
		$func = 'imagecreatefrom'.$ext;
		if( !is_callable($func) ){ 
			throw new \Exception( 'gb functon '.$func.' no exists' );
			return '';
		}
		return $func($file);
	}

    /**
     * @param $img
     * @param $file_output
     * @param string $ext
     * @return bool
     */
	public function saveImage( $img,$file_output,$ext='jpeg' )
	{
		if ( $ext == 'jpeg' ) 
			return imagejpeg($img,$file_output,$this->jpeg_quality);
		$func = 'image'.$ext;
		return $func($img,$file_output);
	}

    /**
     * @param $path
     * @param $file_output
     * @param $size
     * @param string $org
     * @param bool $filter_add
     * @param bool $filter_use
     * @return bool
     */
	function joinAll( $path,$file_output,$size,$org = USE_HEIGHT,$filter_add = false,$filter_use = false )
	{
		if( file_exists( $path ) and ($this->image_replace or !file_exists( $file_output )) ){
			$this->size = 0;
			$this->size1 = $size;
			$this->org = $org;
			$this->each($path,function($Photo,$_this){
				list($w_i, $h_i, $type) = getimagesize($Photo);
				$_this->size+= ($_this->org == USE_HEIGHT)?round($w_i*($_this->size1/$h_i)):round($h_i*($_this->size1/$w_i));
			});
			$w = ($this->org == USE_HEIGHT)?$this->size:$this->size1;
			$h = ($this->org != USE_HEIGHT)?$this->size:$this->size1;
			$this->img_o = $this->blank( $w, $h );
			$this->x = 0;
			$this->y = 0;
			$this->each($path,function($Photo,$_this){
				$h = 0;
				$w = $_this->size1;
				$src = $_this->resize($Photo,$w,$h,$_this->org);
				imagecopy($_this->img_o, $src, $_this->x, $_this->y, 0, 0, $w,$h);
				imagedestroy($src);
				$_this->x+=($_this->org == USE_HEIGHT)?$w:0;
				$_this->y+=($_this->org != USE_HEIGHT)?$h:0;
			});
			if( $filter_use!==false ){
				imagefilter($this->img_o, $filter_use);
			}
			if( $filter_add!==false ){
				$img_o1 = $this->blank( $this->org == USE_HEIGHT?$this->size:$this->size1*2, $this->org != USE_HEIGHT?$this->size:$this->size1*2 );
				imagecopy($img_o1, $this->img_o, 0, 0, 0, 0, $this->org == USE_HEIGHT?$this->size:$this->size1*2, $this->org != USE_HEIGHT?$this->size:$this->size1*2);
				imagefilter($this->img_o, $filter_add);//IMG_FILTER_GRAYSCALE);
				imagecopy($img_o1, $this->img_o, $this->org == USE_HEIGHT?0:$this->size1, $this->org != USE_HEIGHT?0:$this->size1, 0, 0, $this->org == USE_HEIGHT?$this->size:$this->size1*2, $this->org != USE_HEIGHT?$this->size:$this->size1*2);
				imagedestroy($this->img_o);
				return $this->saveImage($img_o1,$file_output);
			}
			return $this->saveImage($this->img_o,$file_output);
		}
	}

    /**
     * @param $file
     * @param $width
     * @param bool $height
     * @param string $org
     * @param string $type
     * @return null|resource
     * @throws \Exception
     */
	function resize( $file,&$width,&$height=false,$org=USE_AUTO,&$type='jpeg' )
	{
		if( $this->isImage($file,$w_i,$h_i,$type) ){
			if( $org == USE_AUTO ){
				if($w_i > $h_i )
					$height = ($width/$w_i) * $h_i;
				else 
					$width = ($height/$h_i) * $w_i;
				if( $w_i<=$width )$width = $w_i;
				if( $h_i<=$height )$height = $h_i;
			}else if( $org==USE_WIDTH ){
				$height = ( $width/$w_i ) * $h_i;
			}else if( $org==USE_HEIGHT ){
				$height = $width;
				$width = ( $height/$h_i ) * $w_i;
			}
			$ext = $type;
			$img = $this->createFrom($ext,$file);
			$img_o = $this->blank($width, $height);
			imagecopyresampled($img_o, $img, 0, 0, 0, 0, $width, $height, $w_i, $h_i);
			imagedestroy($img);
			return $img_o;
		}
		return null;
	}

    /**
     * @param $file_input
     * @param $file_output
     * @param string $crop
     * @return bool
     * @throws \Exception
     */
	function crop($file_input, $file_output, $crop = 'square')
	{
		if( !$this->isImage( $file_input,$w_i, $h_i, $ext ) )return false;
		if ( $crop == 'square' ) {
			$min = $w_i;
			if ($w_i > $h_i) $min = $h_i;
			$w_o = $h_o = $min;
		} else {
			list($x_o, $y_o, $w_o, $h_o) = $crop;
			if ($percent) {
				$w_o *= $w_i / 100;
				$h_o *= $h_i / 100;
				$x_o *= $w_i / 100;
				$y_o *= $h_i / 100;
			}
	    	if ($w_o < 0) $w_o += $w_i;
		    $w_o -= $x_o;
		   	if ($h_o < 0) $h_o += $h_i;
			$h_o -= $y_o;
		}
		$img = $this->createFrom($ext,$file_input);
		$img_o = $this->blank($w_o, $h_o);
		imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
		$this->saveImage( $img_o,$file_output,$ext );
		return true;
	}

    /**
     * @param $directory
     * @param int $countfile
     * @param int $countdir
     */
	function clearDir ($directory,&$countfile=0,&$countdir=0)
	{
		$dir = opendir($directory);
		while(($file = readdir($dir))){
			if ( is_file ($directory."/".$file)){
				unlink ($directory."/".$file);
				$countfile++;
			}else if ( is_dir ($directory."/".$file) && ($file != ".") && ($file != "..")){
				clearDir ($directory."/".$file,$countfile,$countdir); 
				unlink($directory."/".$file);			
				$countdir++;
			}
		}
		closedir ($dir);
	}

    /**
     * @param $path
     * @param $callback
     * @param string $ext
     * @param bool $open_dir
     */
	function each($path,$callback,$ext = 'jpg', $open_dir = false)
	{
		if( file_exists( $path ) and is_dir($path) and is_callable($callback) ){
			$dir = opendir($path);
			while(($file = readdir($dir))){
				if ( is_file ($path."/".$file)){
					$fi = pathinfo($path."/".$file);
					$obj = isset($fi["extension"])?strtolower($fi["extension"]):'';
					if( in_array($obj,explode(',',$ext)) )
						$callback( $path."/".$file,$this,$obj,$fi );
				}else if ( $open_dir and is_dir($path."/".$file) and ($file != ".") and ($file != "..") ){
					$this->each($path."/".$file,$callback,$ext,true); 
				}
			}
		}else throw new Exception( 'path '.$path.' no exists' );
	}

    /**
     * @param $filename
     * @param int $w
     * @param int $h
     * @param string $type
     * @return bool
     */
	public function isImage( $filename,&$w=0,&$h=0,&$type='' )
	{
		if(!is_file($filename))return false;
		list($w, $h, $type1) = getimagesize($filename);
		$type = @$this->mem_types[$type1];
		return ( !empty($type) and $w and $h and isset($this->mem_types[$type1]) );
	}

    /**
     * @param $file
     * @param bool $filter
     * @param null $arg1
     * @param null $arg2
     * @param null $arg3
     * @param null $arg4
     */
	public function filter($file,$filter=false, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
	{
		if($this->isImage($file,$w,$h,$type)){
			$func = 'imagecreatefrom'.$type;
			$img = $func($file);
			if( is_callable($filter) ){
				$img = $filter($img,$file,$type,$w,$h);
			}else if( $filter!==false ){
				imagefilter($img, $filter,$arg1,$arg2,$arg3,$arg4);
			}
			$this->saveImage($img,$file.'.'.$type);
			if($this->image_replace){
				unlink($file);
				rename($file.'.'.$type,$file);
			}
		}
		
	}

    /**
     * @param $file
     * @param $thumb
     * @param $width
     * @param bool $height
     * @param string $org
     * @return mixed
     * @throws \Exception
     */
	function thumb( $file,$thumb,$width,$height=false,$org=USE_AUTO )
	{
		if($this->image_replace or !file_exists( $thumb ) and $this->isImage($file)){
			$img = $this->resize($file,$width,$height,$org,$ext);
			$this->saveImage( $img,$thumb,$ext );
			imagedestroy($img);
		}
		return $thumb;
	}

    /**
     * @param $file
     * @param $width
     * @param bool $height
     * @param string $org
     * @return string
     * @throws \Exception
     */
	function _thumb( $file,$width,$height=false,$org=USE_HOWSET )
	{
		$out = 'files/thumb/'.$width.'x'.$height.'x'.basename(ROOT.$file);
		if(!file_exists(ROOT.$out))
			$this->thumb(ROOT.$file,ROOT.$out,$width,$height,$org );
		return '/'.$out;
	}
}
