<main class="container mt-3 mt-md-5 mb-5">
  <!-- Title -->
  <div class="row mb-4 mb-md-5">
    <div class="col-12">
      <?php $addons->dispatch('templateBeforePageTitle') ?>
      <h1><?= $app->data('pageTitle') ?></h1>
      <?php if($album): ?>
        <div class="mt-3">
          <a href="<?= $app->url().'/'.$app->data('parentPage') ?>" class="text-muted">&laquo; <?= $L->get('Back'); ?></a>
        </div>
      <?php endif; ?>
      <?php $addons->dispatch('templateAfterPageTitle') ?>
    </div>
  </div>

  <!-- albums -->
  <?php $addons->dispatch('templateBeforeAlbumList') ?>
  <?php if($gallery->hasAlbums()): ?>
    <div class="row pt-2 pb-5 mb-5 gx-4 gy-5 albums">
    <?php foreach ($gallery->albums($app->config('sortAlbums')) as $element => $modDate): ?>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3 cover-image">
        <?php $addons->dispatch('templateBeforeAlbum') ?>
        <a href="<?= $app->basePath().'/album/'.$app->albumUri($element, $album) ?>" class="">
          <?php $addons->dispatch('templateBeforeAlbumCover') ?>
          <img data-src="<?= $app->imageUrl($app->albumUri($element, $album), $gallery->coverImage($element, $order), 'thumbnail') ?>" class="lazyload rounded">
          <?php $addons->dispatch('templateAfterAlbumCover') ?>
          <?php if($app->albumTitleEnabled()): ?>
            <div class="pb-3"><?= $app->albumTitle($element) ?></div>
          <?php endif; ?>
        </a>
        <?php $addons->dispatch('templateAfterAlbum') ?>
      </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php $addons->dispatch('templateAfterAlbumList') ?>


  <!-- images -->
  <?php $addons->dispatch('templateBeforeImageList') ?>
  <?php if($gallery->hasImages()): ?>
    <div class="row pt-2 pb-5 gx-4 gy-5 gallery">
    <?php foreach($gallery->images($app->config('sortImages')) as $element => $modDate): ?>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3 cover-image">
        <?php $addons->dispatch('templateBeforeImage') ?>
        <a href="<?= $app->imageUrl($album, $element) ?>" data-sl="<?= $app->imageUrl($album, $element, 'large') ?>">
          <?php $addons->dispatch('templateBeforeImageCover') ?>
          <img  data-src="<?= $app->imageUrl($album, $element, 'thumbnail') ?>" data-caption="<?= $app->imageCaptionLightbox($element) ?>" class="lazyload rounded">
          <?php $addons->dispatch('templateAfterImageCover') ?>
          <?php if($app->imageCaptionInAlbumEnabled()): ?>
            <div class="pb-3"><?= $app->imageCaption($element) ?></div>
          <?php endif; ?>
        </a>
        <?php $addons->dispatch('templateAfterImage') ?>
      </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php $addons->dispatch('templateAfterImageList') ?>
</main>
