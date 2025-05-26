<?php defined("NOVA") or die();
/***
 * Load Addons
 **/

require_once 'AddonInterface.php';
require_once 'Addon.php';

// Shortcut for Addons Class
class Addon extends \novafacile\Addon {  
}

// get classes before load addons classes
$currentDeclaredClasess = get_declared_classes();

// load all uploaded addons
foreach(new \FilesystemIterator(ADDONS_DIR, \FilesystemIterator::SKIP_DOTS) as $addon){
  if($addon->isDir()){
    $addonFile = ADDONS_DIR.$addon->getBasename().DS.'addon.php';
    if(file_exists($addonFile)){
      include_once($addonFile);
    }
  }
}

// Get addons classes loaded
$addonsDeclaredClasess = array_diff(get_declared_classes(), $currentDeclaredClasess);
foreach ($addonsDeclaredClasess as $addonClass) {
  $reflect = new ReflectionClass($addonClass);

  // continue if it's not an addon class
  if(!$reflect->implementsInterface('\novafacile\AddonInterface')){
      continue;
  }

  // build addon object
  $$addonClass = new $addonClass();
  
  // continue if addon name is missing
  $addonName = $$addonClass->addonName();
  if(!$addonName){
    continue;
  }

  // continue if addon is disabled
  if(is_null($addonsConfig->get($addonName)) || (isset($addonsConfig->get($addonName)->enabled) && !$addonsConfig->get($addonName)->enabled) ){ // disabled when no addonsConfig
    $$addonClass->enabled(false);
    continue;
  }

  // register events of addon
  $events = $$addonClass->events();
  foreach ($events as $event => $method) {
    $addons->addListener($event, [$$addonClass, $method]);
  }

  // add translation
  $translationFile = dirname($reflect->getFileName()).DS.'languages'.DS.$config->get('language').'.json';
  $L->addTranslation($translationFile);

}