<?php
/**
 * Initalize novaGallery App
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.1.1
 * @link https://novagallery.org
 **/

// define basics
define('NOVA', true);

$path = dirname(__FILE__);
$path = substr($path, 0, -10);
define('ROOT_DIR', $path);

// Load Vendors in lib
spl_autoload_register(function($class){
  $class = str_replace('\\', '/', $class);
    if (file_exists(ROOT_DIR . '/nova-base/lib/' . $class . '.php')) {
      require_once(ROOT_DIR . '/nova-base/lib/' . $class . '.php');
    }
});


// load JSON DB as local files
require 'core/JsonDB.php';

// load site
require 'core/Site.php';

Site::initialize();

// define constants
$imagesDirName = Site::config('imagesDirName');
if(is_null($imagesDirName)){
  $imagesDirName = 'galleries';
}

define('BASE_PATH', Site::basePath());

define('IMAGES_DIR', ROOT_DIR.'/'.$imagesDirName);
define('IMAGES_URL', BASE_PATH.'/'.$imagesDirName);
define('IMAGES_QUALITY', Site::config('imageQuality'));

define('THEME_DIR', 'nova-themes/'.Site::theme());
define('THEME_PATH', BASE_PATH.'/nova-themes/'.Site::theme());

// load basics
require 'core/Router.php';
require 'core/Page.php';
require 'core/Template.php';

// Language
require 'core/Language.php';
L::initialize(Site::config('language'));

// load routes
require 'app/router.php';

?>