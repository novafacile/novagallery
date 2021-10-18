<?php

// Todo:
// More protection for album & image names

// pass only allowed sizes
if(in_array($size, Site::config('allowedImageSizes'))){
  Image::render($album, $image, $size, Site::config('imageCache'), true);
} else {
  header('HTTP/1.0 404 Not Found');
}

?>