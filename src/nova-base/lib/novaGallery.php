<?php
/**
 * Gallery - List Images and Albums
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.1.1
 * @link https://novagallery.org
 * to disable cache just set maxCacheAge to 'false' on initialization
 **/

class novaGallery {
  
  protected $dir = '';
  protected $images = array();
  protected $albums = array();
  protected $onlyWithImages = true;
  protected $maxCacheAge = 60;
  protected $cacheDir = 'cache';
  protected $cacheFile = 'files.php';

  function __construct($dir, $onlyWithImages = true, $maxCacheAge = 60){
    $this->dir = $dir;
    $this->maxCacheAge = $maxCacheAge;
    $this->onlyWithImages = $onlyWithImages;

    if(!$this->maxCacheAge || !$this->readCache($dir, $maxCacheAge)){
      $this->images = $this->getImages($dir);
      $this->albums = $this->getAlbums($dir);
      if($maxCacheAge) {
        $this->writeCache($dir);
      }
    }  

    if($onlyWithImages){
      $this->albums = $this->removeEmptyAlbums($this->albums);
    }

  }

  protected function getAlbums($dir){
    $dirs = glob($dir.'/'."*", GLOB_ONLYDIR);
    $albumList = $this->fileList($dirs);
    $albums = array();
    unset($albumList["$this->cacheDir"]); // remove cache dir from album list
    foreach ($albumList as $album => $image) {
      $albums[$album] = $this->getImages($dir.'/'.$album);
    }
    return $albums;
  }

  protected function getImages($dir){
    $images = glob($dir.'/*{jpg,jpeg,JPG,JPEG,png,PNG}', GLOB_BRACE);
    return $this->fileList($images, true);
  }


  // create array of files or dirs without path & with last modification date
  protected function fileList($list, $withCaptureDate = false){
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

  protected function getImageCaptureDate($file){
    if(!file_exists($file)) { return false;  }

    if(preg_match('/\.(JPEG|jpeg|JPG|jpg|png|PNG)$/', $file) === 0){
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

  protected function timestampFromExif($string){
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

  protected function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }
    
        $array = $new;
        return true;
  }
  
  protected function order($list, $order){
    switch ($order) {
      case 'oldest':
        asort($list);
        break;
      case 'newest':
        arsort($list);
        break;
      case 'random':
        $this->shuffle_assoc($list);
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
  protected function orderByName($list){
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

    return $orderedList;
  }


  protected function removeEmptyAlbums($albums){
    foreach ($albums as $album => $modDate) {
      if(!$this->hasImages($album)){
        // return false; // free version
        $subAlbum = new novaGallery($this->dir.'/'.$album); // only for version with sub albums
        if(!$subAlbum->hasAlbums()){ // only for version with sub albums
          unset($albums[$album]); // only for version with sub albums
        }
      }
    }
    return $albums;
  }

  
  protected function readCache($dir, $maxAge){
    $cacheFile = $dir.'/'.$this->cacheDir.'/'.$this->cacheFile;
    if(file_exists($cacheFile)){
      $age = time() - filemtime($cacheFile);
      if($age > $maxAge) {
        return false;
      }

      $content = file($cacheFile);
      unset($content[0]); // Remove first security line (<?php die();)
      $content = implode($content); // Regenerate JSON
      $content = json_decode($content, true);
      $this->images = $content['images'];
      $this->albums = $content['albums'];
      return true;
    } else {
      return false;
    }
  }

  protected function writeCache($dir){
    $cacheDir =  $dir.'/'.$this->cacheDir;
    if(!file_exists($cacheDir)){
      mkdir($cacheDir, 0777, true);
    }
    $cacheFile = $cacheDir.'/'.$this->cacheFile;
    $content = ['images' => $this->images, 'albums' => $this->albums];
    $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
    $data = '<?php die(); ?>'.PHP_EOL;
    $data .= $content;
    file_put_contents($cacheFile, $data, LOCK_EX); // LOCK_EX flag prevents that anyone else is writing to the file at the same time
    return true; // only true because if cache doesn't work, it also work (just only without cache)
  }


  public function albums($order = 'default'){

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
      // create array with all albums and all images orderd
    $albums = array();
    foreach ($orderedAlbums as $album => $value) {
      $albums[$album] = $orderedImages[$album];
    }

    return $albums;
  }

  public function images($order = 'default'){
    return $this->order($this->images, $order);
  }



  public function coverImage($album, $order = 'default'){
    if($this->hasImages($album)){
      $images = $this->order($this->albums["$album"], $order);  
      reset($images);
      return key($images);
    } 

    $subGallery = new novaGallery($this->dir.'/'.$album, $this->onlyWithImages, $this->maxCacheAge);
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

  public function hasAlbums(){
    if(empty($this->albums)){
      return false;
    } else {
      return true;
    }
  }

  public function hasImages($album = false){
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
  
  public function parentAlbum($album){
    $parent = strrpos($album, '/');
    return substr($album, 0, $parent);
  }

}

?>
