<?php
/**
 * Site Object
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 **/

class Site {
  
  private static $config;
  private static $basePath;

  public static function initialize(){
    if(file_exists(ROOT_DIR.'/nova-config/site.php')){
      self::$config = JsonDB::read(ROOT_DIR.'/nova-config/site.php');
    } else {
      die('ERROR: missing site config file');
    }

    self::$basePath = parse_url(self::$config->url,  PHP_URL_PATH);
    $lastChar = substr(self::$basePath, -1);
    if($lastChar == '/'){
      self::$basePath = substr(self::$basePath, 0,-1);      
    }
  }

  public static function config($var = false) {
    if($var){
      if(isset(self::$config->$var)){
        if($var == 'footerText'){
          return self::$config->$var.'<br><br>Powered by <a href="http://novagallery.org">novaGallery</a>';
        }
        return self::$config->$var;
      } else {
        return null;
      }
    } else {
      return self::$config;
    }
  }

  public static function configJSON(){
    return (json_encode(self::$config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
  }

  public static function theme() {
    return self::$config->theme;
  }
  
  public static function basePath(){
    return self::$basePath;
  }

  public static function url(){
   return self::$config->url; 
  }

}
