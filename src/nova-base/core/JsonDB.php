<?php
/**
 * Simple class for saving and reading config files in JSON
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 * optional: first line with simple php protection to prentend direct access
 **/
class JsonDB {
  
  private static $firstLine = '<?php defined("NOVA") or die(); ?>';

  public static function read($file, $firstLine = true) {
    if (file_exists($file)) {

      // Read JSON file as array
      $content = file($file);

      // Remove first security line
      if ($firstLine) {
        unset($content[0]);
      }

      // Regenerate JSON
      $content = implode($content);

      return json_decode($content);
    } else {
      return false;
    }
  }


  public static function save($content, $file, $firstLine = true) {
    // checkTyp
    if(is_array($content) || is_object($content)) {
      $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
    }

    // check if json is valid
    $json = json_decode($content);
    if($json === null){
      return false;
    } else {
      // save to file LOCK_EX flag prevents that anyone else is writing to the file at the same time
      $data = self::$firstLine.PHP_EOL;
      $data .= $content;
      if (file_put_contents($file, $data, LOCK_EX)) {
        // on success
        return true;
      } else {
        // on error
        return false;
      }
    }
  }
}
