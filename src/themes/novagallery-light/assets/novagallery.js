lazyload();
document.addEventListener('DOMContentLoaded', function() {
  var $gallery = new SimpleLightbox('.gallery a', {
    sourceAttr: 'data-sl',
    showCounter: false,
    overlayOpacity: 1,
    captionPosition: 'bottom',
    nav: true,
    captionDelay: 300,
    captionsData: 'data-caption',
    widthRatio: 1,
    heightRatio: 1,
    //download: 'Download Image',
    fadeSpeed: 300, 
    animationSlide: true
  });
});