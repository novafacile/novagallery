<?php
/**
 * Simple Addon Class Interface
 */
namespace novafacile;
interface AddonInterface {

  public function addonName();
  public function addonDirName();
  public function addonPath();
  public function addonUrl();
  public function author();
  public function website();
  public function includeCSS($file);
  public function includeJS($file);
  public function enabled();
  public function events();
  public function version();
  public function webhook();

}