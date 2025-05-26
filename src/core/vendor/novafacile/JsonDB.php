<?php
/**
 * Simple class for saving and reading files in JSON
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license MIT
 * @link https://novafacile.com
 * optional: Add a first line for simple php protection to prentend direct access.
 *  - Recommended if storage file is in public webspace. 
 *  - To use this feature it's also required, that file extension is '.php' and used constant is defined before reading file
 **/

namespace novafacile;

class JsonDB {
  
  protected $firstLine = '<?php defined("NOVA") or die(); ?>';

  public function __construct() {
    // do nothing
  }

  public function read(string $file, bool $firstLine = false) {
    if (file_exists($file)) {

      // Read JSON file as array
      $content = file($file);

      // Remove first security line
      if ($firstLine) {
        unset($content[0]);
      }

      // Return JSON as object
      $content = implode($content);
      return json_decode($content);
    } else {
      return false;
    }
  }


  public function save(object|array $content, string $file, bool $firstLine = false) : bool {
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
      $data = '';
      if($firstLine) { $data = $this->firstLine.PHP_EOL; }
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