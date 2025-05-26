<?php
/**
 * novaPage - Basic Website Object
 * - router
 * - basic settings
 * - simple themes logic
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novafacile.com
 **/

namespace novafacile;
class novaPage {

  protected $config;
  protected $siteName = '';
  protected $seo = [];
  protected $data = [];

  protected $routes = [];
  protected $pathNotFound = null;
  protected $methodNotAllowed = null;
  protected $statusCode = null;

  protected $response = null;

  protected $theme = '';
  protected $template = '';

  function __construct(object $config) {
    $this->config = $config;

    // site basics
    if(isset($config->theme)) { $this->theme = $config->theme; }
    if(isset($config->siteName)) { $this->siteName = $config->siteName; }

    // SEO Basics
    $this->seo['metaTitle'] = $config->siteName;
    if(isset($config->metaDescription)) { $this->seo['metaDescription'] = $config->metaDescription; }
    if(isset($config->robots)) {
      $this->seo['robots'] = $config->robots;
    } else {
      $this->seo['robots'] = 'index,follow';
    }
  }


  /*************
   * data storages
   *************/
  public function config($key = false, $returnFalse = false) {
    if(!$key){
      return $this->config;
    }

    if(isset($this->config->$key)){
      return $this->config->$key;  
    } else {
      if($returnFalse){
        return false;
      } else {
        return null;
      }
    }      
  }

  public function setConfig($key, $value){
    $this->config->$key = $value;
  }

  public function data($key = null){
    if(is_null($key)){
      return $this->data;
    }

    if(isset($this->data[$key])){
      return $this->data[$key];
    } else {
      return null;
    }
  }

  public function setData($key, $value = null){
    $newValues = $key;
    if (is_array($key)) {
      $this->data = $this->data + $newValues;
    } else {
      $this->data[$key] = $value;
    }
    return $this;
  }

  public function response($element = false) {
    if($element){
      if(isset($this->response[$element])) {
        return $this->response[$element];  
      } else {
        return null;
      }
    }
    return $this->response;
  }

  /******************
   * Basic Info
   ******************/
  public function url() {
    $url = $this->config('url');
    return rtrim($url ?? '', '/');
  }

  /*************
   * SEO Methods
   *************/

  public function seo($var = null, $value = null) {
    // return all seo vars
    if(is_null($var)) {
      return $this->seo;
    }

    // return value of seo var
    if(is_null($value)) {
      if(isset($this->seo[$var])) {
        return $this->seo[$var]; 
      } else {
        return false;
      }
    }

    // set seo var with value
    $this->seo[$var] = $value;
    return $this->seo[$var];

  }

  public function noindex(bool $noindex = true) : string {
    if($noindex){
      $this->seo['robots'] = 'noindex,follow';
    } else {
      $this->seo['robots'] = 'index,follow';
    }
    return $noindex;
  }

  public function pageTitle(bool|string $pageTitle = false) : string {
    if($pageTitle){
      $this->pageTitle = $pageTitle;
      $this->seo('metaTitle', ucfirst($pageTitle).' | '.$this->siteName);
    } else {
      return ucfirst($this->pageTitle);
    }
  }

  /******************
   * Theme & Template
   ******************/

  public function template(string|bool $template = false) {
    if(!$template){
      return $this->template;
    }
    $this->template = $template;
  }

  public function theme() {
    return $this->theme;
  }

  public function themeUrl(){
    return $this->url().'/themes/'.$this->theme.'/';
  }

  // Error Pages (404, 405...)
  public function errorPage(int $errorCode = 500) {
    $this->statusCode($errorCode);
    $this->noindex(true);
    $this->template('error-'.$errorCode);
    return '';
  }
  

  /*******************
   * http status response code 
   * (e.g 200, 404, 500)
   *******************/
  public function statusCode(int|bool $statusCode = false) {
    if($statusCode){
      $this->statusCode = $statusCode;
    }
    return $this->statusCode;
  }



  /***************
   * Router
   * Method used to add a new route
   * @param string $expression    Route string or expression
   * @param callable $function    Function to call if route with allowed method is found
   * @param string|array $method  Either a string of allowed method or an array with string values
   * @link https://github.com/steampixel/simplePHPRouter
   ***************/
  public function bind($expression, $callback, $method = 'get'){
    array_push($this->routes, Array(
      'expression' => $expression,
      'callback' => $callback,
      'method' => $method
    ));
  }

  public function get($expression, $callback){
    $this->bind($expression, $callback, 'get');
  }

  public function post($expression, $callback){
    $this->bind($expression, $callback, 'post');
  }

  public function pathNotFound($callback) {
    $this->pathNotFound = $callback;
  }

  public function methodNotAllowed($callback) {
    $this->methodNotAllowed = $callback;
  }

  public function resetRouter(){
    $this->routes = [];
    return true;
  }

  public function removeRoute($expression, $method = 'get'){
    // Todo
  }

  public function getRoutes(){
    return $this->routes;
  }

  public function run($basepath = '', $case_matters = false, $trailing_slash_matters = false, $multimatch = false) {
    
    // The basepath never needs a trailing slash
    // Because the trailing slash will be added using the route expressions
    $basepath = rtrim($basepath, '/');

    // Parse current URL
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);

    $path = '/';

    // If there is a path available
    if (isset($parsed_url['path'])) {
      // If the trailing slash matters
      if ($trailing_slash_matters) {
        $path = $parsed_url['path'];
      } else {
        // If the path is not equal to the base path (including a trailing slash)
        if($basepath.'/'!=$parsed_url['path']) {
          // Cut the trailing slash away because it does not matters
          $path = rtrim($parsed_url['path'], '/');
        } else {
          $path = $parsed_url['path'];
        }
      }
    }

    $path = urldecode($path);

    // Get current request method
    $method = $_SERVER['REQUEST_METHOD'];
    $path_match_found = false;
    $route_match_found = false;

    foreach ($this->routes as $route) { 

      // If the method matches check the path

      // Add basepath to matching string
      if ($basepath != '' && $basepath != '/') {
        $route['expression'] = '('.$basepath.')'.$route['expression'];
      }

      // Add 'find string start' automatically
      $route['expression'] = '^'.$route['expression'];

      // Add 'find string end' automatically
      $route['expression'] = $route['expression'].'$';

      // Check path match
      if (preg_match('#'.$route['expression'].'#'.($case_matters ? '' : 'i'), $path, $matches)) {
        $path_match_found = true;

        // Cast allowed method to array if it's not one already, then run through all methods
        foreach ((array)$route['method'] as $allowedMethod) {
            // Check method match
          if (strtolower($method) == strtolower($allowedMethod)) {
            array_shift($matches); // Always remove first element. This contains the whole string

            if ($basepath != '' && $basepath != '/') {
              array_shift($matches); // Remove basepath
            }

            $this->response = call_user_func_array($route['callback'], $matches);
            $route_match_found = true;
            
            if($this->response === false){
              $this->statusCode(404);
              header('HTTP/1.0 404 Not Found');
              if ($this->pathNotFound) { // check if special method is set
                $this->response = call_user_func_array($this->pathNotFound, Array($path));
              }
            } else {
                $this->statusCode(200);
            }
            
            // Do not check other routes
            break;
          }
        }
      }
      // Break the loop if the first found route is a match
      if($route_match_found&&!$multimatch) {
        break;
      }
    }

    // No matching route was found
    if (!$route_match_found) {
      // But a matching path exists
      if ($path_match_found) {
        $this->statusCode(405);
        header('HTTP/1.0 405 Method Not Allowed');
        if ($this->methodNotAllowed) { // check if special method is set
          $this->response = call_user_func_array($this->methodNotAllowed, Array($path,$method));
        }
      } else {
        $this->statusCode(404);
        header('HTTP/1.0 404 Not Found');
        if ($this->pathNotFound) { // check if special method is set
          $this->response = call_user_func_array($this->pathNotFound, Array($path));
        }
      }

    }
  }

  public function redirect(string $url, int $code = 302, bool $external = false){
    if(!$external) {
      $url = BASE_PATH.$url;
    }
    header('Location: '.$url, true, $code);
  }

}