<?php
/**
 * Initalize novaGallery App
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.0
 * @link https://novagallery.org
 **/

// define basics
define('NOVA', true);

$path = dirname(__FILE__);
$path = substr($path, 0, -10);
define('ROOT_DIR', $path);

// Load config
require ROOT_DIR.'/nova-config/config.php';

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

define('THEME_DIR', 'nova-themes/'.Site::theme());
define('THEME_PATH', BASE_PATH.'/nova-themes/'.Site::theme());

define('IMAGES_QUALITY', Site::config('imageQuality'));

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