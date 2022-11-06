<?php
/**
 * Simple Template Object
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 **/

class Template {
  public static function render($template){
    if($template == 404) {
      header('HTTP/1.0 404 Not Found');
    }
    require THEME_DIR.'/index.php';
  }

}
