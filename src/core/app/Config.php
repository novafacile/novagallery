<?php
/**
 * Config for novaGallery
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 */
namespace novafacile;
class Config extends novaConfig {

  public function __construct(string $file, string $type = '') {
    parent::__construct($file, true);
    if(!is_object($this->config)){
      $this->config = (object) [];
    }
    $config = json_decode(json_encode($this->config), true);

    // choose default config
    switch ($type) {
      case 'app':
        $merge = array_replace_recursive($this->defaultApp(), $config);
        break;
      case 'addons':
      default:
        $merge = $config;
        break;
    }
    
    // set merged config
    $this->config = json_decode(json_encode($merge, JSON_FORCE_OBJECT));
  }

  protected function defaultApp() : array {
    $default = [
      'siteName' => 'novaGallery',
      'siteTitle' => 'The Simple PHP Image Gallery',
      'metaTitle' => 'novaGallery',
      'metaDescription' => 'novaGallery - beautiful php photo gallery - free and open source.',
      'footerText' => '',
      'theme' => 'novagallery',
      'url' => '',
      'language' => 'en',
      'imagesDirName' => 'galleries',
      'storageDirName' => 'storage',
      'imageCache' => true,
      'cacheFileListMaxAge' => 60,
      'imageSizes' => [
        'thumbnail'=> '400x400',
        'large'=> '2000'
      ],
      'imageQuality'=> '85',
      'useOriginalForLarge' => false,
      'useExifDate'=> true,
      'sortAlbums'=> 'dateDESC',
      'sortImages'=> 'dateDESC',
      'albumTitle' => [
        'enabled' => true,
        'transformation' => 'ucwords',
        'replace' => [
          '_' => ' ',
          '/' => ' &raquo; '
        ]
      ],
      'imageCaption' => [
        'enabled' => false,
        'transformation' => 'ucwords',
        'replace' => [
          '_' => ' '
        ],
        'showInAlbum' => false,
        'showInLightBox' => false
      ],
    ];
    return $default;
  }
  
}