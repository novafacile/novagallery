<?php defined("NOVA") or die();

if($this->isLoggedIn()){
  $app->redirect('/', 302);
  exit;
}

// SEO settings
$app->setData('pageTitle', 'Login '.$app->config('siteTitle')); // on home: page title = home title
$app->seo('metaTitle', $app->data('pageTitle').' | '.$app->config('metaTitle'));
$app->seo('metaDescription', $app->config('metaDescription'));
$app->noindex();

if(isset($_POST['password'])){
  if(password_verify($_POST['password'], $this->passwordHash)){
    $_SESSION['visitorLoggedIn'] = true;
    header('Location: '.$this->redirectUri());
  } else {
    sleep(5);
  }
}

$app->setData('pageType', 'login');

// load custom template if exists
if(file_exists(THEME_DIR.DS.'password-protection'.DS.'login.php')){
  $app->template(THEME_DIR.DS.'password-protection'.DS.'login');
} else {
  $app->template($this->templateBase.'login');
}

?>