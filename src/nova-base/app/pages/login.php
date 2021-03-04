<?php

if(!Site::config('pagePassword')){
  header('Location: '.Site::url());
}

Page::title('Login');
Page::metaTitle(Page::title().' | '.Site::config('siteName'));
Page::metaDescription(Site::config('metaDescription'));


session_start();

if(isset($_SESSION['visitorLoggedIn']) && $_SESSION['visitorLoggedIn'] == true){
  header('Location: '.Site::url().'/');
}

if(isset($_POST['password'])){
  if(password_verify($_POST['password'], Site::config('pagePassword'))){
    $_SESSION['visitorLoggedIn'] = true;
    header('Location: '.Site::url().'/');
  }
}


Template::render('login');

?>