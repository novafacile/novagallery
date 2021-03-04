<?php
/**
 * Page Object
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 **/

class Page {

  private static $metaTitle = '';
  private static $metaDescription = '';
  private static $title =  '';
  private static $subtitle =  '';
  private static $content = '';
  private static $seoIndex = '';
  private static $seoFollow = '';
  private static $data = array();
  
  public static function title($value = false) {
    if($value)
      self::$title = $value;
    return self::$title;
  }

  public static function subtitle($value = false) {
    if($value)
      self::$subtitle = $value;
    return self::$subtitle;
  }

  public static function content($value = false) {
    if($value)
     self::$content = $value;
    return self::$content;
  }

  public static function seoIndex($value = false) {
    if($value)
      self::$seoIndex = $value;
    return self::$seoIndex;
  }

  public static function seoFollow($value = false) {
    if($value)
      self::$seoFollow = $value;
    return self::$seoFollow;
  }  

  public static function metaTitle($value = false) {
    if($value)
      self::$metaTitle = $value;
    return self::$metaTitle;
  }

  public static function metaDescription($value = false) {
    if($value)
      self::$metaDescription = $value;
    return self::$metaDescription;
  }

  public static function addData($var, $content){
    self::$data[$var] = $content;
  }

  public static function data($var = false){
    if($var)
      return self::$data[$var];
    else
      return self::$data;
  }

}

?>