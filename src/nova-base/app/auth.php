<?php

if(Site::config('pagePassword')){
  session_start();
  if(!isset($_SESSION['visitorLoggedIn']) || $_SESSION['visitorLoggedIn'] !== true){
    header("HTTP/1.1 401 Unauthorized");
    header('Location: '.Site::url().'/login');
  }
}
