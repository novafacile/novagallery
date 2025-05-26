<?php

/**
 * novaGallery Addon
 * Protected Site
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license MIT
 * @version 1.0.0
 * @link https://novagallery.org
 */
class ProtectedSite extends Addon {

  protected $addonName = 'Password Protection';
  protected $version = '1.0.0';
  protected $author = 'novafacile OÜ';
  protected $website = 'https://novagallery.org';
  
  protected $events = [
    'beforeRouting' => 'beforeRouting',
    'templateHead' => 'addCss',
    'templateNavigationEnd' => 'logoutLinkNavi',
    'templateFooterEnd' => 'logoutLinkFooter'
  ];

  protected $passwordHash = '';
  protected $templateBase = '';

  public function __construct(){
    $this->passwordHash = $this->getPasswordHash();
    $this->templateBase = __DIR__.DS.'template'.DS;
  }

  public function beforeRouting(){
    // skip if no password hash is set
    if(!$this->passwordHash){
      return;
    }

    if(!$this->isLoggedIn()){
      return $this->requireLogin();
    } 

    // load app and set routes
    global $app;
     $app->bind('/logout', function() use ($app) {
      require 'pages/logout.php';
    }, ['get', 'post']);

    // add route to login
    $app->bind('/login', function() use ($app) {
      require 'pages/login.php';
    }, ['get', 'post']);

  }

  public function addCss(){

    // load custom css if exists
    if(file_exists(THEME_DIR.DS.'password-protection'.DS.'style.css')){
      echo $this->includeCSS(THEME_URL.'password-protection/style.css');
    } else {
      echo $this->includeCSS('template/style.css');
    }
  }

  public function logoutLink(){
    global $app, $L;
    return '<a href="'.$app->url().'/logout">'.$L->get('Logout').'</a>';
  }

  public function logoutLinkNavi(){
    if($this->isLoggedIn()){
      echo $this->logoutLink();
    }
  }

  public function logoutLinkFooter(){
    if($this->isLoggedIn()){
      echo PHP_EOL.'<br>'.$this->logoutLink().'<br>'.PHP_EOL;
    }
  }

  protected function getPasswordHash(){
    global $addonsConfig;
    if(!is_null($addonsConfig->get($this->addonName)) && isset($addonsConfig->get($this->addonName)->passwordHash)){
      return $addonsConfig->get($this->addonName)->passwordHash;
    } else {
      return false;
    }
  }

  protected function isLoggedIn(){
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if(isset($_SESSION['visitorLoggedIn']) && $_SESSION['visitorLoggedIn'] == true){
      return true;
    } else {
      return false;
    }
  }

  protected function requireLogin(){
    global $app;
    $app->resetRouter();

    // add route to login
    $app->bind('/login', function() use ($app) {
      require 'pages/login.php';
    }, ['get', 'post']);

    // redirect everything else to login
    $app->bind('(.*)', function($uri) use ($app) {
      $this->setRedirectUri();
      $app->redirect('/login', 302);
    }, ['get', 'post']);
  }

  protected function redirectUri(){
    if(isset($_SESSION['redirectUri'])){
      $uri = $_SESSION['redirectUri'];
    }

    // some protections, "://" is check if contains protocol (bad redirect)
    if(!isset($uri) || !is_string($uri) || $uri == '' || strpos($uri, '://') || $uri == BASE_PATH.'/login' || $uri == BASE_PATH.'/logout' ){
      $uri = BASE_PATH.'/';
    }
    return $uri;
  }

  protected function setRedirectUri(){
    // get url path for redirect after login and store in session
    $uri = $_SERVER['REQUEST_URI'];

    if(!$uri) {
      $uri = '/';
    }
    $_SESSION['redirectUri'] = $uri;
  }

  protected function performLogout(){
    global $app;
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['visitorLoggedIn'] = false;
    session_destroy();
    $app->redirect('/', 302);
  }
}