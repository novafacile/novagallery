<?php defined("NOVA") or die();

// Page title from album name
$title = $this->albumTitle($this->album);

// SEO settings
$this->setData('pageTitle', $title);
$this->seo('metaTitle', $this->data('pageTitle').' | '.$this->config('siteName'));
$this->seo('metaDescription', $this->data('pageTitle').' - '.$this->seo('metaDescription'));

// ceck if album exists
if(file_exists(IMAGES_DIR.DS.$this->album)){
  // load images and sub albums from album
  $addons->dispatch('beforeLoadGallery');
  $this->gallery = new novafacile\novaGallery(IMAGES_DIR.DS.$this->album, $this->config('imageCache'), $this->config('cacheFileListMaxAge'), $this->config('useExifDate'), CACHE_DIR.DS.$this->album);
  $parentPage = $this->gallery->parentAlbum($this->album);
  if($parentPage){
    $parentPage = 'album/'.$parentPage;
  }
  $addons->dispatch('afterLoadGallery');

  // set data
  $this->setData('pageType', 'album');
  $this->setData('gallery', $this->gallery);
  $this->setData('album', $this->album);
  $this->setData('parentPage', $parentPage);
  $this->template('album');  

} else {
  // show error 404
  $this->errorPage('404');
}

