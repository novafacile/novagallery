<?php
/**
 * Image Gallery - List Images and Albums
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 * to disable cache just set maxCacheAge to 'false' on initialization
 **/
namespace novafacile;

class novaGallery {
  
  protected string $dir = '';
  protected int|null $cacheTime = null;
  protected int|null $cacheAge = null;
  protected array $images = [];
  protected array $albums = [];
  protected bool $onlyWithImages = true;
  protected bool|int $maxCacheAge = 60;
  protected bool|string $cacheDir = false;
  protected string $cacheFile = 'filesCache.php';
  protected bool $useExif = true;
  protected bool $allowSubAlbums = true;
  protected array $filesCache = [];

  // Todo: config via config array or object
  function __construct(string $dir, bool $onlyWithImages = true, bool|int $maxCacheAge = 60, bool $useExif = true, bool|string $cacheDir = false, string $cacheFile = 'filesCache.php'){
    $this->dir = $dir;
    $this->onlyWithImages = $onlyWithImages;
    $this->maxCacheAge = $maxCacheAge;
    $this->cacheDir = $cacheDir;
    $this->cacheFile = $cacheFile;
    $this->useExif = $useExif;

    if($this->maxCacheAge){
      $cacheResult = $this->readCache($cacheDir?$cacheDir:$dir);
    }

    if(!$cacheResult || $this->cacheAge > $this->maxCacheAge){
      $this->images = $this->getImages($dir);
      $this->albums = $this->getAlbums($dir);
      if($maxCacheAge) {
        $this->writeCache($cacheDir?$cacheDir:$dir);
      }
    }

    if($onlyWithImages){
      $this->albums = $this->removeEmptyAlbums($this->albums);
    }
  }

  protected function getAlbums(string $dir) : array {
    $dirs = glob($dir.'/'."*", GLOB_ONLYDIR);
    $albumList = $this->fileList($dirs);
    $albums = array();
    foreach ($albumList as $album => $image) {
      $albums[$album] = $this->getImages($dir.'/'.$album);
    }
    return $albums;
  }

  protected function getImages(string $dir) : array {
    if(defined('GLOB_BRACE')){
      $imageOnly = '*.{[jJ][pP][gG],[jJ][pP][eE][gG],[pP][nN][gG],[gG][iI][fF],[wW][eE][bB][pP]}';
      $images = glob($dir.'/'.$imageOnly, GLOB_BRACE);
    } else {
      // get list for every file type if GLOB_BRACE is not available e.g. Solaris, non GNU Linux
      $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];  
      $images = [];  
      foreach ($extensions as $ext) {  
        $images = array_merge(
          $images,  
          glob($dir.'/*.'.$ext),  
          glob($dir.'/*.'.strtoupper($ext)) 
        );
    }
  }
    return $this->fileList($images, true);
  }


  // create array of files or dirs without path & with last modification date
  protected function fileList(array $list, bool $withCaptureDate = false) : array {
    $fileList = array();
    foreach ($list as $element) {
      if($withCaptureDate){ // add modification date if requested
        $value = $this->getImageCaptureDate($element);
      } else {
        $value = array(); // else add as array for sub files
      }
      $element = strrchr($element, '/');
      $element = substr($element, 1);
      $fileList[$element] = $value;
    }
    return $fileList;
  }

  protected function getImageCaptureDate(string $file) : int{
    if(!file_exists($file)) { return false;  }

    if(!$this->useExif){
      return filemtime($file);
    }

    if(preg_match('/\.(jpeg|jpg|png)$/i', $file) === 0){
      return filemtime($file); // use filetime, if no image
    }

    // Get the photo's EXIF tags
    try {
      @$exif_data = exif_read_data($file);
      if($exif_data === false) {
        return filemtime($file); // use filemtime, if no exif data
      }
    } catch (Exception $e) {
      return filemtime($file); // use filemtime, if exif data error
    }
    

    // default value, which represents no date
    $date = false;
    // Array of EXIF date tags to check
    $date_tags = [
      'DateTimeOriginal',
      'DateTimeDigitized',
      'DateTime',
      //'FileDateTime'
    ];

    // Check for the EXIF date tags, in the order specified above. First value wins.
    foreach($date_tags as $date_tag){
      if(isset($exif_data[$date_tag])){
        $date = $exif_data[$date_tag];
        $date = $this->timestampFromExif($date);
        break;
      }
    }

    // If no date tags were found use filemtime
    if(!$date) { return filemtime($file);}

    //If the date that was extracted is a string, convert it to an integer
    if( is_string($date) ) $date = strtotime($date);

    return $date;
  }

  protected function timestampFromExif(string $string) : int {
    if (!(preg_match('/\d\d\d\d:\d\d:\d\d \d\d:\d\d:\d\d/', $string))) {
      return $string; // wrong date
    }

    $iTimestamp = mktime(
            substr( $string, 11, 2 ), 
            substr( $string, 14, 2 ), 
            substr( $string, 17, 2 ), 
            substr( $string, 5, 2 ), 
            substr( $string, 8, 2 ), 
            substr( $string, 0, 4 ));
    return $iTimestamp;
  }


  protected function shuffle_assoc(array $array) : array {
        $keys = array_keys($array);
        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        return $new;
  }

  protected function order(array $list, string $order) : array {
    switch ($order) {
      case 'dateASC':
        asort($list);
        break;
      case 'dateDESC':
        arsort($list);
        break;
      case 'nameASC':
        $list = $this->orderByName($list);
        break;
      case 'nameDESC':
        $list = $this->orderByName($list, true);
        break;
      case 'random':
        $list = $this->shuffle_assoc($list);
        break;
      default:
        // order by name
        $list = $this->orderByName($list);
        break;
    }
    return $list;
  }

  // sort array by natcasesort with german umlaute
  // solution based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
  protected function orderByName(array $list, bool $desc = false) : array {
    // swap key (name) value (timestamp) for order operations
    $nameList = array();
    foreach ($list as $album => $value) {
      array_push($nameList, $album);
    }

    // sort based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
    $aOriginal = $nameList;
    if (count($aOriginal) == 0) { return $aOriginal; }
    $aModified = array();
    $aReturn   = array();
    $aSearch   = array("Ä","ä","Ö","ö","Ü","ü","ß","-");
    $aReplace  = array("A","a","O","o","U","u","ss"," ");
    foreach($aOriginal as $key => $val) {
      $aModified[$key] = str_replace($aSearch, $aReplace, $val);
    }
    natcasesort($aModified);
    foreach($aModified as $key => $val) {
      $aReturn[$key] = $aOriginal[$key];
    }

    // swap back to have a orderd list with the correct key (album) value (timestamp) format
    $orderedList = array();
    foreach ($aReturn as $value) {
      $orderedList[$value] = $list[$value];

    }

    if($desc){
      return array_reverse($orderedList, true);
    } else {
      return $orderedList;
    }
  }


  protected function removeEmptyAlbums(array $albums) : array {
    foreach ($albums as $album => $modDate) {
      if(!$this->hasImages($album) && $this->allowSubAlbums){
        $subAlbum = new novaGallery($this->dir.'/'.$album, $this->onlyWithImages, $this->maxCacheAge, $this->useExif, $this->cacheDir.'/'.$album, $this->cacheFile);
        if(!$subAlbum->hasAlbums()){
          unset($albums[$album]);
        }
      } elseif(!$this->hasImages($album)){
          unset($albums[$album]);
        }
    }
    return $albums;
  }

  
  protected function readCache(string $dir) : bool {
    $cacheFile = $dir.'/'.$this->cacheFile;

    if(!file_exists($cacheFile)){
      return false;
    }

    // read cache content
    $content = file($cacheFile);
    unset($content[0]); // Remove first security line (<?php die();)
    $content = implode($content); // Regenerate JSON
    $content = json_decode($content, true); // JSON to array

    // check if keys exists for compatibility with older versions
    if(!array_key_exists('cacheTime', $content) || !array_key_exists('albums', $content) || !array_key_exists('images', $content)){
      return false;
    }

    // read cache
    $this->cacheTime = $content['cacheTime'];
    $this->images = $content['images'];
    $this->albums = $content['albums'];
    $this->cacheAge = time() - $this->cacheTime;
    return true;
  }

  protected function writeCache(string $dir) : bool {
    if(!file_exists($dir)){
      mkdir($dir, 0775, true);
    }
    $cacheFile = $dir.'/'.$this->cacheFile;
    $content = [
      'cacheTime' => time(),
      'images' => $this->images,
      'albums' => $this->albums
    ];
    $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
    $data = '<?php die(); ?>'.PHP_EOL;
    $data .= $content;
    $savedCache = file_put_contents($cacheFile, $data, LOCK_EX); // LOCK_EX flag prevents that anyone else is writing to the file at the same time
    if($savedCache === false){
      trigger_error('can not save cache file: '.$cacheFile, E_USER_WARNING);
    }
    return true; // only true because if cache doesn't work, it also work (just only without cache)
  }


  public function albums(string $order = 'default') : array {

    // order images in albums
    $orderedImages = array();
    foreach ($this->albums as $album => $images) {
      $orderedImages[$album] = $this->order($images, $order);
    }

    // order albums based on first image
    $orderedAlbums = array();
      // create array with albums and timestamp of first image
    foreach ($orderedImages as $album => $images) {
      if(!empty($images)){
        $orderedAlbums[$album] = array_values($images)[0];  
      } else {
        $orderedAlbums[$album] = '';
      }
    }
    $orderedAlbums = $this->order($orderedAlbums, $order);
    // create array with all albums and all images ordered
    $albums = array();
    foreach ($orderedAlbums as $album => $value) {
      $albums[$album] = $orderedImages[$album];
    }

    return $albums;
  }

  public function images(string $order = 'default') : array {
    return $this->order($this->images, $order);
  }



  public function coverImage(string $album, string $order = 'default') : string {
    if($this->hasImages($album)){
      $images = $this->order($this->albums["$album"], $order);  
      reset($images);
      return key($images);
    } 
    if(!$this->allowSubAlbums){
      return false;
    }

    $subGallery = new novaGallery($this->dir.'/'.$album, $this->onlyWithImages, $this->maxCacheAge, $this->useExif, $this->cacheDir.'/'.$album, $this->cacheFile);
    if($subGallery->hasAlbums()){
      $albums = $subGallery->albums($order);
      $firstAlbum = array_key_first($albums);
      $coverImage = $subGallery->coverImage($firstAlbum, $order);
      if($coverImage){
        return $firstAlbum.'/'.$coverImage;
      } else {
        return $firstAlbum;
      }
    }
    // else return false
    return false;
  }

  public function hasAlbums() : bool {
    if(empty($this->albums)){
      return false;
    } else {
      return true;
    }
  }

  public function hasImages(bool|string $album = false) : bool {
    // choose correct image array
    if($album){
      $imageList = &$this->albums[$album];
    } else {
      $imageList = &$this->images;
    }

    // check if empty
    if(empty($imageList)){
      return false;
    } else {
      return true;
    }
  }
  
  public function parentAlbum(string $album) : string {
    $parent = strrpos($album, '/');
    return substr($album, 0, $parent);
  }

}