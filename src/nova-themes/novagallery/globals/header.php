<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php echo Page::metaTitle(); ?></title>
  <meta name="description" content="<?php echo Page::metaDescription(); ?>">

  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets//bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets/simple-lightbox.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets/style.css" />

  <link rel="icon" href="<?php echo THEME_PATH; ?>/assets/novagallery-favicon.png" type="image/png">

</head>
<body>
  <div class="container">
    <header class="row mt-4">
     <div class="col-md-3 mb-3 logo"><a href="<?php echo Site::url(); ?>"><?php echo Site::config('siteName'); ?></a></div>
     <div class="col-12 col-md-9 mb-3 text-md-right">
       <?php if(Site::config('pagePassword') && isset($_SESSION['visitorLoggedIn']) && $_SESSION['visitorLoggedIn'] === true): ?>
        <a href="<?php echo Site::url().'/logout' ?>" class="btn btn-secondary btn-sm d-none d-md-inline-block"><?php L::p('Logout'); ?></a>
      <?php endif; ?>
     </div>
    </header>