<?php
/**
 * Initalize novaGallery App
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 **/
namespace novafacile;
defined("NOVA") or die();

spl_autoload_register(function($class){
  $class = str_replace('\\', DS, $class);
    if (file_exists(ROOT_DIR . DS. 'core'.DS.'vendor' . DS . $class . '.php')) {
      require_once(ROOT_DIR . DS. 'core'.DS.'vendor' . DS . $class . '.php');
    }
});

// load configs
require 'app'.DS.'Config.php';
$config = new Config(ROOT_DIR.DS.'config'.DS.'site.php', 'app');
$addonsConfig = new Config(ROOT_DIR.DS.'config'.DS.'addons.php', 'addons');

// get images storage name
$imagesDirName = $config->get('imagesDirName');
if(is_null($imagesDirName)){
  $imagesDirName = 'galleries';
}

// get data storage name
$storageDirName = $config->get('storageDirName');
if(is_null($storageDirName)){
  $storageDirName = 'storage';
}

// set global vars
$basepath = parse_url($config->get('url'),  PHP_URL_PATH) ?? ''; // empty string if null
define('BASE_PATH', rtrim($basepath, '/'));

define('IMAGES_DIR_NAME', $imagesDirName);
define('IMAGES_DIR', ROOT_DIR.DS.IMAGES_DIR_NAME);
define('IMAGES_URL', BASE_PATH.'/'.IMAGES_DIR_NAME);
define('IMAGES_QUALITY', $config->get('imageQuality'));

define('STORAGE_DIR_NAME', $storageDirName);
define('STORAGE_DIR', ROOT_DIR.DS.$storageDirName);
define('STORAGE_URL', BASE_PATH.'/'.STORAGE_DIR_NAME);

define('CACHE_DIR_NAME', 'cache');
define('CACHE_DIR', ROOT_DIR.DS.STORAGE_DIR_NAME.DS.CACHE_DIR_NAME);
define('CACHE_URL', BASE_PATH.'/'.STORAGE_DIR_NAME.'/'.CACHE_DIR_NAME);

define('THEME_DIR', 'themes'.DS.$config->get('theme'));
define('THEME_URL', BASE_PATH.'/themes/'.$config->get('theme'));

define('ADDONS_DIR', ROOT_DIR.DS.'addons'.DS);
define('ADDONS_URL', BASE_PATH.'/addons/');

// translations
$language = $config->get('language');
$languageFile = ROOT_DIR.DS.'languages'.DS.$language.'.json';
$L = new SimpleTranslations($languageFile);

// load addons
$addons = new SimpleEventDispatcher();
require 'app'.DS.'addons'.DS.'addons.php';

// load app
require_once 'app'.DS.'App.php';
$app = new app($config->get());

// add addons to app (as pointer)
$app->addAddons($addons);

// addons hook: before all
$addons->dispatch('beforeAll', $app);

// load routes
require 'app'.DS.'router.php';

// run app and get response
$app->run(BASE_PATH);

// set shortcuts for templates
$gallery = &$app->gallery;
$album = &$app->album;
$order = &$app->order;

// load theme & print output
$addons->dispatch('beforeTheme');
require(ROOT_DIR.DS.'themes'.DS.$app->theme().DS.'index.php');

// addons hook: after all
$addons->dispatch('afterAll');