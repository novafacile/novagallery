<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $app->seo('metaTitle') ?></title>
  <meta name="description" content="<?= $app->seo('metaDescription') ?>" />
  <link rel="stylesheet" href="<?= THEME_URL ?>/assets/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?= THEME_URL ?>/assets/simple-lightbox.min.css">
  <link rel="stylesheet" type="text/css" href="<?= THEME_URL ?>/assets/novagallery.css" />
  <link rel="icon" href="<?= THEME_URL ?>/assets/novagallery-favicon.png" type="image/png">
<?php $addons->dispatch('templateHead'); ?>
</head>
<body>
<?php $addons->dispatch('templateBodyBegin'); ?>

<header class="navbar navbar-dark mt-4">
  <nav class="container">
    <a href="<?= $app->url() ?>" class="navbar-brand"><?= $config->get('siteName') ?></a>
    <?php $addons->dispatch('templateNavigationBegin'); ?>
    <?php $addons->dispatch('templateNavigationEnd'); ?>
  </nav>
</header>
