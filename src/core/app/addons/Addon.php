<?php
/**
 * Simple Addon Class
 */
namespace novafacile;
class Addon implements AddonInterface {

  protected $addonName = '';
  protected $addonDirName = '';
  protected $author = '';
  protected $enabled = true;
  protected $events = [];
  protected $version = '';
  protected $website = '';

  function __construct(){
    
  }

  public function addonName(){
    return $this->addonName;
  }

  public function addonDirName(){
    if(!$this->addonDirName){
      $this->getAddonDirName();
    }
    return $this->addonDirName;
  }

  public function addonPath(){
    return ADDONS_DIR.$this->addonDirName().DS;
  }

  public function addonUrl(){
    return ADDONS_URL.$this->addonDirName().DS;
  }

  public function author(){
    return $this->author; 
  }

  public function enabled(?bool $status = null){
    if(is_null($status)){
      return $this->enabled;
    } else {
      return $this->enabled = $status;
    }
  }

  public function includeCSS($file){
    return '  <link rel="stylesheet" type="text/css" href="'.$this->addonUrl().$file.'">'.PHP_EOL;
  }

  public function includeJS($file){
    return '  <script charset="utf-8" src="'.$this->addonUrl().$file.'"></script>'.PHP_EOL;
  }

  public function events(){
    return $this->events;
  }

  protected function getAddonDirName(){
    $reflector = new \ReflectionClass(get_class($this));
    return $this->addonDirName = basename(dirname($reflector->getFileName()));
  }


  public function webhook($URI=false, $returnsAfterURI=false, $fixed=true){
    // todo: currently just a copy - doesn't work
    
    global $url;

    if (empty($URI)) {
      return false;
    }

    // Check URI start with the webhook
    $startString = HTML_PATH_ROOT.$URI;
    $URI = $url->uri();
    $length = mb_strlen($startString, CHARSET);
    if (mb_substr($URI, 0, $length)!=$startString) {
      return false;
    }

    $afterURI = mb_substr($URI, $length);
    if (!empty($afterURI)) {
      if ($fixed) {
        return false;
      }
      if ($afterURI[0]!='/') {
        return false;
      }
    }

    if ($returnsAfterURI) {
      return $afterURI;
    }
    
    return true;
  }

  public function website(){
    return $this->website; 
  }

  public function version(){
    return $this->version;
  }
}