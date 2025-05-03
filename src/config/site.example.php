<?php defined("NOVA") or die(); ?>
{
  "siteName": "novaGallery",
  "siteTitle": "The Simple PHP Image Gallery",
  "metaTitle": "novaGallery",
  "metaDescription": "novaGallery - beautiful php photo gallery - free and open source.",
  "footerText": "&copy; 2025 by novafacile OÜ",
  "theme": "novagallery",
  "url": "https://demo.novagallery.org",
  "language": "en",
  "imagesDirName": "galleries",
  "storageDirName": "storage",
  "imageCache": true,
  "cacheFileListMaxAge": 60,
  "imageSizes": {
    "thumbnail": "400x400",
    "large": "2000"
  },
  "imageQuality": 85,
  "useOriginalForLarge": false,
  "useExifDate": true,
  "sortAlbums": "dateDESC",
  "sortImages": "dateDESC",
  "albumTitle": {
    "enabled": true,
    "transformation": "ucwords",
    "replace": {
      "_": " ",
      "-": " ",
      "/": " &raquo; "
    }
  },
  "imageCaption": {
    "enabled": false,
    "transformation": "ucwords",
    "replace": {
      "_": " ",
      "-": " "
    },
    "showInAlbum": false,
    "showInLightbox": false
  }
}