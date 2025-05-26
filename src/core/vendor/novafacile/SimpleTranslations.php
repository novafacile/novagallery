<?php
/**
 * Simple Translations Class
 * works with a json file
 * requires novafacile\JsonDB
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license MIT
 * @link https://novagallery.org
 **/
namespace novafacile;
class SimpleTranslations {

  protected $translations;
  
  public function __construct(string $languageFile){
    $this->translations = (object)[];
    $this->translations = $this->loadLanguageFile($languageFile);
  }

  public function loadLanguageFile(string $languageFile) : object {
    if(file_exists($languageFile)){
      $storage = new JsonDB();
      return $storage->read($languageFile, false);
    } else {
      return (object)[];
    }
  }

  public function addTranslation(string $languageFile) : object {
    $newTranslation = $this->loadLanguageFile($languageFile);
    $this->translations = (object) array_merge((array) $this->translations, (array) $newTranslation);
    return $this->translations;
  }

  public function get(string $string){
    $key = strtolower($string);
    $key = str_replace(' ', '-', $key);
    $key = str_replace('.', '', $key);
    $key = str_replace(',', '', $key);
    $key = str_replace('"', '', $key);

    if(isset($this->translations->$key)){
      return $this->translations->$key;
    } else {
      return $string;
    }
  }

  public function g(string $string) {
    return $this->get($string);
  }

  public function print(string $string){
    echo $this->get($string);
  }

  public function p(string $string){
    $this->print($string);
  }

  public function all(){
    return $this->translations;
  }
}