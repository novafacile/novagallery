<?php
/**
 * Images
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 * @uses GImage by Jose Quintana <https://git.io/joseluisq>
 * to disable cache just set cache to 'false' on initialization
 **/

class Image {

  private static $filePath;
  private static $original;
  private static $cache;
  private static $cacheDirRoot;
  private static $cacheDir;
  private static $cacheFile;
  private static $width = false;
  private static $height = false;

  private static function initialize($album, $image, $size = false, $cache = true){
    // set path & name vars    
    self::$filePath = IMAGES_DIR.'/'.$album;
    self::$original = self::$filePath.'/'.$image;
    self::$cache = $cache;
    self::$cacheDirRoot = self::$filePath.'/cache';
    self::$cacheDir = self::$cacheDirRoot.'/'.$size;
    self::$cacheFile = self::$cacheDir.'/'.$image;

    // set size
    if($size){
      $size = explode('x', $size);
      if(isset($size[0]) && is_numeric($size[0])){
        self::$width = $size[0];
      }

      if(isset($size[1]) && is_numeric($size[1])){
        self::$height = $size[1];
      }
    }


  }

  /************
   * method to get image url based on size
   * @param string $album - name of the album
   * @param string $image - file name of image
   * @param numeric $width - optional resize width 
   * @param numeric $height - optional resize height
   *
   * @return string URL - url of (resized) image
   ************/
  public static function url($album, $image, $size = false){
    // split album name if is in sub dir because slash should't be encoded with rawurlencode
    if(strpos($album, '/')){
      $pathArray = explode('/', $album);
      $path = '';
      foreach ($pathArray as $value) {
        if($path){
          $path = rawurlencode($value);
        } else {
          $path = $path.'/'.rawurlencode($value);
        }
      }
    } else {
      $album = rawurlencode($album);
    }

    

    // split image name if is in sub dir because contains sub dirs
    if(strpos($image, '/')){
      $pathArray = explode('/', $image);
      $image = array_pop($pathArray); // remove last entry from array because, it's the file
      foreach ($pathArray as $value) {
        $album .= '/'.rawurlencode($value);
      }
    }

    $url = IMAGES_URL.'/'.$album.'/cache/';

    if($size){
      $url .= $size.'/';
    }

    if(!$image){
      $image = 'noimage';
    }

    return $url.rawurlencode($image);

  }

  public static function originalUrl($album, $image){
    return IMAGES_URL.'/'.rawurlencode($album).'/'.rawurlencode($image);
  }


  public static function name($image){
    $name = pathinfo($image, PATHINFO_FILENAME);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('+', ' ', $name);
    $name = str_replace('-', ' ', $name);
    $name = ucwords($name);
    return $name;
  }


  /******
   * method to get (resized) image
   * check if image already exists else create image
   * this method uses GImage from José Luis Quintana <https://git.io/joseluisq>
   * 
   * @param string $album - album name
   * @param string $image - image file name
   * @param string $size (optional) - size
   * @return file stream - image as file stream
   *
   *****/
  public static function render($album, $image, $size = false, $cache = true, $hide404image = true){
    self::initialize($album, $image, $size, $cache);

    // check of original exisits
    if(!file_exists(self::$original)){
      self::notFound($hide404image);
      exit;  
      }
        
    // if no size, stream original file
    if(!$size){
      $img = new GImage\Image();
      $img  ->load(self::$original)
            ->output();
      exit;
    }

    // if cache file already exists, stream cache file directly
    if(file_exists(self::$cacheFile)){
      $img = new GImage\Image();
      $img  ->load(self::$cacheFile)
            ->output();
      exit;
    }

    // create cache dir
    if($cache && !file_exists(self::$cacheDir)){
      mkdir(self::$cacheDir, 0777, true);
    }

    // load image processing
    $img = new GImage\Image();
    $img  ->load(self::$original)
          ->setQuality(IMAGES_QUALITY);
    

    if(self::$width && !self::$height){
      // resize to width
      $img->resizeToWidth(self::$width);
    } elseif (!self::$width && self::$height) {
      // resize to height
      $img->resizeToHeight(self::$height);      
    } elseif (self::$width && self::$height) {
      // resize both with cover/cropCenter
      $img->centerCrop(self::$width, self::$height);
    }

    // if cache is disabled, only stream
    if(!$cache){
      $img  ->output();
      exit;
    }

    $img  ->preserve()
          ->output()
          ->preserve(false)
          ->save(self::$cacheFile);

  }
  
  public static function writeCache($album, $image, $size){
    self::initialize($album, $image, $size);

    // check if original exists
    if(!file_exists(self::$original)){
      return false;
    }

    // check if cache file already exists
    if(file_exists(self::$cacheFile)){
      return true;
    }

    // create cache dir
    if(!file_exists(self::$cacheDir)){
      mkdir(self::$cacheDir, 0777, true);
    }

    // perform image processing
    $img = new GImage\Image();
    $img  ->load(self::$original)
          ->setQuality(IMAGES_QUALITY);

    if(self::$width && !self::$height){
      // resize to width
      $img->resizeToWidth(self::$width);
    } elseif (!self::$width && self::$height) {
      // resize to height
      $img->resizeToHeight(self::$height);      
    } elseif (self::$width && self::$height) {
      // resize both with cover/cropCenter
      $img->centerCrop(self::$width, self::$height);
    }

    return $img ->save(self::$cacheFile);

  }


  private static function notfound($hide404image = true){
    header('HTTP/1.0 404 Not Found');
    if($hide404image){
      echo '404 Not Found';
      exit;
    }

    // use cache
    if(self::$cache){
      $cacheFile = self::$cacheDirRoot.'/404-not-found.jpg';

      if(file_exists($cacheFile)){
        $img = new GImage\Image();
        $img  ->load($cacheFile)
              ->output();
        exit;
      }

      if(!file_exists(self::$cacheDirRoot)){
        mkdir(self::$cacheDirRoot, 0777, true);
      }
    }
    

    $text = new GImage\Text('404 / Image not found');
    $text
        ->setWidth(500)
        ->setHeight(500)
        ->setAlign('center')
        ->setValign('center')
        ->setSize(22)
        ->setOpacity(0.5)
        ->setFontface(ROOT_DIR.'/lib/GImage/Lato.ttf');

    // Used as layout
    $layout = new GImage\Figure(500, 500);
    $layout
        ->setBackgroundColor(180, 180, 180)
        ->create();

    $canvas = new GImage\Canvas($layout);
    $canvas
        ->append($text)
        ->tojpg()
        ->setQuality(IMAGES_QUALITY)
        ->draw();

    if(self::$cache){
      $canvas ->preserve()
              ->output()
              ->preserve(false)
              ->save($cacheFile);
    } else {
      $canvas->output();
    }       
    exit;
  }

}

?>