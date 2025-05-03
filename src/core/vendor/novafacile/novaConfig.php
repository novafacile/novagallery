<?php
/**
 * novaConfig - Simple Config Object
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license MIT
 * @version 1.0.0
 * @link https://novafacile.com
 * @require novafacile\JsonDB
 */
namespace novafacile;
class novaConfig {

  protected $config;
  protected $storage;
  
  function __construct(string $file, bool $firstLine = false){
    $this->storage = new JsonDB();
    $this->config = $this->load($file, $firstLine);
  }

  public function load(string $file, bool $firstLine = false){
    if(file_exists($file)){
      return $this->storage->read($file, $firstLine);
    } else {
      trigger_error('missing config file: '.$file.' while loading config', E_USER_WARNING);
      return false;
    }
  }

  public function get($key = false) {
    if($key){
      if(isset($this->config->$key)){
        return $this->config->$key;
      } else {
        return null;
      }
    } else {
      return $this->config;
    }
  }

  public function set(string $key, $value){
    return $this->config->$key = $value;
  }
  
  public function configJSON(){
    return (json_encode($this->config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
  }

}