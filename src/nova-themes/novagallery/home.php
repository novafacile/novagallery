    <?php 
      $gallery = Page::data('gallery');
      $album = Page::data('album');
      $order = Page::data('order');
    ?>
    <content class="row mt-md-5">
      <div class="col-12 mb-4"><h1><?php echo Page::title(); ?></h1></div>
        <div class="container mt-4">
        <!-- albums -->
        <?php if($gallery->hasAlbums()): ?>
        <div class="row px-3 mb-5">
          <?php foreach($gallery->albums($order) as $element => $modDate): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Site::basePath().'album/'.$element; ?>">
                <img src="<?php echo Image::url($element, $gallery->coverImage($element, $order), Site::config('imageSizeThumb')); ?>" loading="lazy" class="rounded"><br>
                <?php echo ucwords($element); ?>
              </a>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if($gallery->hasImages()): ?>
        <div class="row gallery px-2">
          <?php foreach($gallery->images($order) as $element => $modDate): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Site::url().Image::url($album, $element, Site::config('imageSizeBig')); ?>">
                <img src="<?php echo Site::url().Image::url($album, $element, Site::config('imageSizeThumb')); ?>" loading="lazy" class="rounded"><br>
              </a>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>
      </div>
    </content>