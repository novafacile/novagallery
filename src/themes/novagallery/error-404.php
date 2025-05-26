<main class="container mt-3 mt-md-5 mb-5">
  <div class="row mb-4">
    <div class="col-12">
      <h1><?= $L->get('404 / Page not found') ?></h1>
      <p><?= $L->get('Sorry, this page does not seem to exist.') ?></p>
      <p><a href="<?= $app->url() ?>" class="text-muted">&laquo; <?= $L->get('Back to Home') ?></a></p>
    </div>
  </div>
</main>