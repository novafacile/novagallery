<?php
/**
 * novaGallery Addon
 * Add meta tag robots index,follow or noindex,follow
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license MIT
 * @version 1.0.0
 * @link https://novagallery.org
 */
class MetaRobotsTag extends Addon {

  protected $addonName = "Robots Meta Tag";
  protected $version = '1.0.0';
  protected $author = 'novafacile OÜ';
  protected $website = 'https://novagallery.org';

  protected $events = [
    'templateHead' => 'templateHead',
    'beforeTheme' => 'beforeTheme'
  ];

  public function beforeTheme() : void {
    global $addonsConfig, $app;
    if($addonsConfig->get($this->addonName) && !$addonsConfig->get($this->addonName)->allowIndex){
      $app->noindex();
    }
  }
  
  public function templateHead() : void {
    global $app;
    if($app->seo('robots') && $app->seo('robots')){
      echo '  <meta name="robots" content="'.$app->seo('robots').'">'.PHP_EOL;
    }
  }
}