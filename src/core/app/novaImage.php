<?php
/**
 * Images Helper for novaGallery
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 * @uses SimpleImage by Cory LaViska <https://github.com/claviska/SimpleImage>
 */

namespace novafacile;
use \claviska\SimpleImage;

class novaImage {

  protected object $imageSizes;
  protected bool $imageCache;
  
  function __construct(object $imageSizes, bool $imageCache = true)  {
    $this->imageSizes = $imageSizes;
    $this->imageCache = $imageCache;
  }

  function resize(string $album, string $image, string $size, bool $noenlarge = true)  {
    // prepare
    if(!isset($this->imageSizes->$size)){
      $this->imageNotFound();
      return;
    }

    $album = rtrim($album ?? '', '/');
    $album = str_replace('\\', DS, $album);
    $image = str_replace('\\', DS, $image);
    
    if($album){
      $albumPath = $album.DS;
    } else {
      $albumPath = '';
    }

    $original = IMAGES_DIR.DS.$albumPath.$image;

    if(!file_exists($original)){
      $this->imageNotFound();
    }

    // get file & resize information
    $cacheDir = CACHE_DIR.DS.$albumPath.DS.$size;
    $cacheFile = $cacheDir.DS.$image;
    $dimension = $this->getSizeDimensions($size);
    $width = $dimension['width'];
    $height = $dimension['height'];

    // create cache dir
    if($this->imageCache && !file_exists($cacheDir)){
      mkdir($cacheDir, 0755, true);
    }

    // new image processing
    $img = new SimpleImage();
    $img
      ->fromFile($original)
      ->autoOrient();

    if($noenlarge && ($img->getWidth() <= $width || $img->getHeight() <= $height)){
      // do nothing
    } elseif($width && $height){
      $img->thumbnail($width, $height, 'center');
    } else {
      $img->resize($width, $height);
    }

    if(!$this->imageCache){
      $img->toScreen();
      exit;
    } else {
      $img->toScreen();
      $img->toFile($cacheFile, null, IMAGES_QUALITY);
      exit;
    }

  }

  // get dimensions based on width x height
  protected function getSizeDimensions(string $size) : array {
    $dimension = explode('x', $this->imageSizes->$size);
    $width = null;
    $height = null;

    if(isset($dimension[0]) && is_numeric($dimension[0])){
      $width = $dimension[0];
    }
    if(isset($dimension[1]) && is_numeric($dimension[1])){
      $height = $dimension[1];
    }

    return ['width' => $width, 'height' => $height];
  }


  public function imageNotFound(){
    header('HTTP/1.0 404 Not Found');
    echo 'error 404 - image not found';
    exit;
  }

}