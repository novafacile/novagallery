<?php
/**
 * novaGallery
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.2
 * @link https://novagallery.org
 **/

// define basics
define('VERSION', '2.0.2');
define('NOVA', true);
define('ROOT_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('DEBUG', false);

// set error handling for debug
if(DEBUG){
  $begin = microtime(true);
  // error handling
  ini_set("display_errors", 1);
  ini_set('display_startup_errors',0);
  ini_set("html_errors", 1);
  ini_set('log_errors', 0);
  error_reporting(E_ALL | E_STRICT | E_NOTICE);
}


// load app
require_once('core'.DS.'init.php');

// echo runtime on debug mode
if(DEBUG){
  $runtime = microtime(true) - $begin;
  echo '<div style="margin:20px auto;text-align:center;">Runtime: '.$runtime.' Sec. </div>';
}