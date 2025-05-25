<?php
/**
 * novaGallery Web App
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 */
namespace novafacile;
class app extends novaPage {

  public $album = '';
  public $gallery;
  public $order = '';

  protected $addons;

  function __construct(object $config){
    parent::__construct($config); // Lets the parent handle construction
    $this->footerText();
  }

  public function addAddons(object $addons) : void {
    $this->addons = &$addons;
    return;
  }

  public function footerText(){
    global $config;
    $config->set('footerTextOriginal',$config->get('footerText'));
    $branding = '<br>Powered by <a href="http://novagallery.org" target="_blank">novaGallery</a><br><br>';
    if($config->get('footerText')){
      $config->set('footerTextOriginal', $config->get('footerText'));
      $config->set('footerText', $config->get('footerText').$branding);
    } else {
      $config->set('footerText', $branding);
    }
  }

  public function page(string $page) : void {
    // add shortcuts
    $addons = &$this->addons;

    // load page
    $addons->dispatch('beforePage');
    require 'pages'.DS.$page.'.php';
    $addons->dispatch('afterPage');
    return;
  }

  public function cachedImage(string $album, string $size, string $image) : void {
    // add shortcuts
    $addons = &$this->addons;

    // build correct values
    $this->album = $this->removeBadSigns(rawurldecode($album));
    $size = $this->removeBadSigns(rawurldecode($size));
    $image = $this->removeBadSigns(rawurldecode($image));
    $noenlarge = true;
    if($size == 'thumbnail') {
      $noenlarge = false;
    }

    // load image
    if(isset($this->config('imageSizes')->$size)){
      $this->addons->dispatch('beforeImage');
      require 'novaImage.php';
      $imageObject = new novaImage($this->config->imageSizes, $this->config->imageCache);
      $imageObject->resize($this->album, $image, $size, $noenlarge);
      $this->addons->dispatch('afterImage');
    } else {
      $this->imageNotFound();
      exit;
    }
    
    return;
  }

  public function album(string $album) : void {
    // add shortcuts
    $addons = &$this->addons;

    // build correct values
    $album = rawurldecode($album);

    // small protection for album names
    $this->album = $this->removeBadSigns($album);

    // load album
    $this->addons->dispatch('beforePage');
    $this->addons->dispatch('beforeAlbum');
    require 'pages'.DS.'album.php';
    $this->addons->dispatch('afterAlbum');
    $this->addons->dispatch('afterPage');
  }

  public function basePath() : string {
    return BASE_PATH;
  }

  public function transformFilename($filename, array $replace = [], bool $ucwords = true) : string {
    // remove extension
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    foreach ($replace as $key => $value) {
      $filename = str_replace($key, $value, $filename);
    }

    if($ucwords){
      $filename = ucwords($filename);
    }

    return $filename;
  }

  public function transformString(string $string, array $replace = [], string $transformation = '', $onlyBasename = false) : string {
    // remove extension
    if($onlyBasename){
      $string = pathinfo($string, PATHINFO_FILENAME);
    }
    // replace token
    foreach ($replace as $key => $value) {
      $string = str_replace($key, $value, $string);
    }
    // transform words
    switch ($transformation) {
      case 'ucwords':
        $string = ucwords($string);
        break;
      case 'ucfirst':
        $string = ucfirst($string);
        break;
      case 'uppercase':
        $string = strtoupper($string);
        break;
      case 'lowercase':
        $string = strtolower($string);
        break;
      default:
        // do nothing
        break;
    }
    return $string;
  }

  // get image caption based on config if not set
  public function imageCaption(string $image, array $replace = [], string $transformation = '') : string {
    if(!$this->imageCaptionEnabled()){
      return '';
    }
    global $config;
    if(empty($replace) && $config->get('imageCaption')->replace){
      $replace = (array) $config->get('imageCaption')->replace;
    }
    if(!$transformation && $config->get('imageCaption')->transformation){
      $transformation = $config->get('imageCaption')->transformation;
    }
    return $this->transformString($image, $replace, $transformation, true);
  }

  // get image caption for lightbox
  public function imageCaptionLightbox(string $image, array $replace = [], string $transformation = '') : string {
    if(!$this->imageCaptionInLightboxEnabled()){
      return '';
    }
    return $this->imageCaption($image, $replace, $transformation);
  }

  // get album title based on config if not set
  public function albumTitle(string $album, array $replace = [], string $transformation = '') : string {
    global $config;
    if(empty($replace) && $config->get('albumTitle')->replace){
      $replace = (array) $config->get('albumTitle')->replace;
    }
    if(!$transformation && $config->get('albumTitle')->transformation){
      $transformation = $config->get('albumTitle')->transformation;
    }
    return $this->transformString($album, $replace, $transformation);
  }

  // get album uri
  public function albumUri ($album, $parent = null){
    return $this->pathencode($parent ? $parent.'/'.$album : $album);
  }

  // get image url, depends on size
  public function imageUrl($album, $image, $size = false) : string {

    // double decode encode to prevent double encode, if cover image url is requested
    $album = rawurldecode($album);
    $album = $this->pathencode($album);

    // split image name if is in sub dir because contains sub dirs
    if(strpos($image, '/')){
      $pathArray = explode('/', $image);
      $image = array_pop($pathArray); // remove last entry from array because, it's the file
      $album .= '/' . implode('/', array_map('rawurlencode', $pathArray));
    }

    // get image type based on extension
    $imageType = strtolower(pathinfo($image, PATHINFO_EXTENSION));


    // define base image url for all images to prevent errors
    global $config;
    if($size && !($config->get('useOriginalForLarge') && $size == 'large')){
      $url = CACHE_URL.'/'.$album.'/'.$size.'/';
    } else {
      $url = IMAGES_URL.'/'.$album.'/';
    }


    // change url based on image type (extension) to prevent removing animation
    switch ($imageType) {
      case 'gif':
        if($size == 'large'){
          $url = IMAGES_URL.'/'.$album.'/';
        }
        break;
      case 'webp':
        $albumDir = str_replace('/',DS, rawurldecode($album));  // get album dir from album url
        $imageFile = IMAGES_DIR.DS.$albumDir.DS.$image;         // create image file path
        if($this->isWebpAnimated($imageFile)){                  // check if it's animated webp
          $url = IMAGES_URL.'/'.$album.'/';
        }
        break;
    }

    $this->addons->dispatch('imageUrl', $url);
    return $url.rawurlencode($image);

  }

  protected function removeBadSigns(string $value) : string {
    $value = str_replace('/../', '', $value);
    $value = str_replace('<', '&lt;', $value);
    $value = str_replace('>', '&gt;', $value);
    return $value;
  }

  // helper to check if image is an animated webp
  public function isWebpAnimated(string $file) : bool {
    $webpContents = file_get_contents($file);
    $where = strpos($webpContents, "ANMF");
    if ($where !== FALSE){ // animated
        $isAnimated = true;
    }
    else{ // non animated or no webp
        $isAnimated = false;
    }
    return $isAnimated;
  }

  // helper for rawurlencode except slash
  public function pathencode(string $string) : string {
    if (strpos($string, '/')) {
      $string = implode('/', array_map('rawurlencode', explode('/', $string)));
    } else {
      $string = rawurlencode($string);
    }
    return $string;
  }

  public function pathdecode(string $string) : string {
    return implode('/', array_map('rawurldecode', explode('/', $string)));
  }

  // shortcuts
  public function albumData() : string {
    return $this->data('album');
  }
  public function gallery() : object {
    return $this->data('gallery');
  }
  public function order() : string {
    return $this->data('order');
  }
  public function albumTitleEnabled() : bool {
    global $config;
    return $config->get('albumTitle')->enabled;
  }
  public function imageCaptionEnabled() : bool {
    global $config;
    return $config->get('imageCaption')->enabled;
  }
  public function imageCaptionInLightboxEnabled() : bool {
    global $config;
    return $config->get('imageCaption')->showInLightbox;  
  }
  public function imageCaptionInAlbumEnabled() : bool {
    global $config;
    return $config->get('imageCaption')->showInAlbum;  
  }


}