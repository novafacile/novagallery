<?php defined("NOVA") or die();

// SEO settings
$this->setData('pageTitle', $this->config('siteTitle')); // on home: page title = home title
$this->seo('metaTitle', $this->data('pageTitle').' | '.$this->config('metaTitle'));
$this->seo('metaDescription', $this->config('metaDescription'));

// load images and albums from root dir
$addons->dispatch('beforeLoadGallery');
$this->gallery = new novafacile\novaGallery(IMAGES_DIR, $this->config('imageCache'), $this->config('cacheFileListMaxAge'), $this->config('useExifDate'), CACHE_DIR);
$addons->dispatch('afterLoadGallery');

// set data
$this->setData('pageType', 'home');
$this->setData('gallery', $this->gallery);
$this->setData('album', '');
$this->setData('parentPage', null);
$this->template('home');