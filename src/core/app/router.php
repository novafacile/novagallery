<?php defined("NOVA") or die();
/**
 * Router for novaGallery
 * @author novafacile OÜ
 * @copyright Copyright (c) 2025 by novafacile OÜ
 * @license AGPL-3.0
 * @version 2.0.0
 * @link https://novagallery.org
 */

// addons hook
$addons->dispatch('beforeRouting');

// home
$app->bind('/', fn() => $app->page('home'));

// cached & resized image
$app->bind('/'.STORAGE_DIR_NAME.'/'.CACHE_DIR_NAME.'/(.*)/(.*)/(.*)', fn($album, $size, $image) => $app->cachedImage($album, $size, $image));

// album
$app->bind('/album/(.*)', fn($album) => $app->album($album));

// redirect album without album name to home
$app->bind('/album', fn() => $app->redirect('/'));

// set error page 404
$app->pathNotFound(fn() => $app->errorPage('404'));

// addons hook
$addons->dispatch('afterRouting');