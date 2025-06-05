# novaGallery

novaGallery is a beautiful php image gallery with the focus on your images, trimmed for ease of use and low demands on web space. You just need a webserver with PHP 8.X support. No database is required. Simple to use, easy customizable and beautiful. 

**Official Product Page**: [novaGallery.org](https://novagallery.org)

## Features

### Overview

* **Albums**: Directories are rendered as albums
* **Sub-Albums**: Can be used for more detailed organisation
* **Preview images** for albums: Automatically generate an album preview image
* **Photo overview**: All photos are displayed sorted by creation date or name
* **Lightbox**: View your photos in large format, hiding everything that is distracting
* **Slideshow**: All photos in an album, can be displayed and presented via click (or keyboard) in the lightbox view
* **Use your own upload method**: Simply upload the photos into the gallery folder (e.g. via FTP) and the photos are already online
* **Thumbnails**: Automatic generation of thumbnails
* **File compression**: Photos with high file size, are automatically compressed with very little loss.
* **Supported Images**: JPEG, PNG, GIF, WebP are supported.
* **Custom Themes**: With just a little knowledge of CSS and HTML, the look can be customized.
* **Languages**: You can run the gallery in your langauage
* **Addons**: Addons allow you to extend the features of novaGallery:
  * **Private Mode**: You can set a password to protect your gallery from public access.
  * **Robots Meta Tag**: Set the visibility of your content for bots
  * **Pro Version**: Changes your novaGallery to novaGallery Pro and removes contributions
  * _more addons coming soon_

### Screenshots

![novaGallery Home](https://novagallery.org/img/novagallery-home.jpg "novaGallery Home") ![novaGallery Album](https://novagallery.org/img/novagallery-album.jpg "novaGallery Album")
![novaGallery Lightbox](https://novagallery.org/img/novagallery-lightbox.jpg "novaGallery Lightbox") ![novaGallery Private Mode](https://novagallery.org/img/novagallery-private.jpg "novaGallery Private Mode")

### Demo

* [novaGallery Demo](https://demo.novagallery.org/)

### Requirements

* Apache web server or similar
* Apache: enabled mod_rewrite and .htaccess support
* Other webserver (e.g. nginx): Individual settings in config to route all requests throw index.php
* PHP 8.x
* PHP-GD Extension
* PHP Exif Support

## Setup

### Installation

* Download latest version: [Download novaGallery (latest version)](https://download.novafacile.com/novagallery/novagallery-free.zip)
* Unzip files
* Upload files to your webspace
* Copy `config/site.example.php` to `config/site.php`
* Edit the config in `config/site.php`
  * Set URL to novaGallery including http or https
* Upload your photos into galleries
* Enjoy your new photo gallery

### Configuration

* Every folder in galleries is a gallery
* In `config/site.php` you can change some basic informations, image sizes and other settings
* That's it :-)

#### Configuration for Installation in Subdir

* Set RewriteBase in .htaccess: `RewriteBase /subdir/` (Be sure, that you set the URL path, **without** the domain and with starting and trailing slash)
* Set url in `config/site.php` with full subdir path

### Manage Images

* **Add Photos**: Just upload the new photo to the server into the correct album (e.g via FTP)
* **Delete a Photos**: Just delete the photo at the server. To save web space it's recommended to delete the cached files in cache folder also.
* **Delete an Album**: Just delete the whole album (directory) at the server. To save web space it's recommended to delete the cached files in cache folder also.
* **Reset Cache**: Each gallery has its own folder in the cache directory (default: _YOUR_PATH/storage/cache_), identical to the path in the gallery folder. Simply delete the corresponding gallery folder in the cache directory.

### Themes

* Two basic themes are included: novagallery (default, dark) and novagallery-light
* To change the theme, just change the theme name in `config/site.php`
* Creating a new template is quite simple and works with basic PHP without any extra template engine.
* To create a new template, the basic template (based on Bootstrap 5) can be duplicated, customized and activated in the settings.

### Languages

* Some languages are already added (English, German, Spanisch, French, Finnish, Italian, Dutch, Portuguese)
* Set your language in `config/site.php`
* You can create easily your own language. Just copy one of the language files, translate it and save it with your language code
* It would be nice, if you let us know if you created a new language file. Just send us a message or create a pull request.

### Addons

* novaGallery 2 can be extended with addons
* The addon feature is experimental. The functionality including hooks and configuration may still change completely.
* To activate the addons feature, just copy `config/addons.example.php` to `config/addons.php`
* Upload your addon to the `addons`-directory
* Add the config of the addons config-file (`config/addons.php`) and activate it:
```
  "The Addon Name": {
    "enabled": true
  }
```
* Be careful, the file must contain valid JSON so that the addons config can be loaded

#### Private Mode
* In novaGallery 2, the private mode is build as addon. This is included as basic addon.
* To activate it, make sure that the Addons functionality is generally activated
* In `config/addons.php` you can set a PHP password hash:
```
"Password Protection": {
    "enabled": false,
    "passwordHash": "HERE-YOUR-PASSWORD-HASH"
  },
```
* The password hash has to be created with the standard PHP function `password_hash()`
* If you can't generate a password hash by your own, you can use our password hash generator: [Password Hash Generator](https://tools.nova.ms/password-hash-generator)

## Nice to Know

* Supported images: JPEG, PNG, GIF, WebP (experimental)
* At the first time an album is opened, the thumbnails are generated. This may take a little time. After that the cached images are used for each visit.
* If you don't use an apache2 web server with .htaccess support (e.g. nginx), you have to set the required rewrite rules from the .htaccess file at the webserver config.
* WebP-Support is experimantal. The basics PHP-GD-Lib currently doesn't support generating thumbnails from WebP-images without additional software on the webserver.
* The thumbnail of animated GIFs is without animation. The animation is available in the lightbox view.

## Troubleshooting

* Error 404 when clicking on an album or image:
  * On Apache: mod_rewrite is not installed and/or enabled
  * On Apache: htacces support is not enabled
  * On nginx or other webserver: settings in server config to route all requests throw index.php is not enabled (similar mod_rewrite)
  * novaGallery is installed in sub dir, but `RewriteBase` in .htaccess is not set (or similar on other webserver)
* If thumbnails and images are not generated, in most cases it's one of the following problems:
  * On Apache: mod_rewrite is not installed and/or enabled
  * On Apache: htacces support is not enabled
  * On nginx or other webserver: settings in server config to route all requests throw index.php is not enabled (similar mod_rewrite)
  * PHP GD-Lib is not installed or/and activated

## Configuration Details

### All settings

| Setting Name                | Description                                                            | Example                                                     |
|----------------------------|-------------------------------------------------------------------------|-------------------------------------------------------------|
| `siteName`                 | Name of the gallery                                                     | `novaGallery`                                               |
| `siteTitle`                | Title of the gallery, e.g. shown in the browser tab                     | `The Simple PHP Image Gallery`                              |
| `metaTitle`                | Title used for meta tags (SEO purposes)                                 | `novaGallery`                                               |
| `metaDescription`          | Description used for meta tags (SEO purposes)                           | `novaGallery - beautiful php photo gallery...`              |
| `footerText`               | HTML content for the footer area                                        | `&copy; 2025 by novafacile OÜ`                              |
| `theme`                    | Name of the theme to be used                                            | `novagallery`                                               |
| `url`                      | Base URL of the gallery                                                 | `https://demo.novagallery.org`                              |
| `language`                 | Language code (ISO 639-1)                                               | `en`                                                        |
| `imagesDirName`            | Directory name containing the image albums                              | `galleries`                                                 |
| `storageDirName`           | Directory name used for cached/generated files                          | `storage`                                                   |
| `imageCache`               | Enables or disables image caching (`true`, `false`)                         | `true`                                                      |
| `cacheFileListMaxAge`      | Maximum age (in seconds) of cached file lists                           | `60`                                                        |
| `imageSizes.thumbnail`     | Size of the thumbnail images (width x height, or largest side)              | `400x400`                                                   |
| `imageSizes.large`         | Maximum width for the large image version (width x height, or largest side) | `2000`                                                      |
| `imageQuality`             | JPEG quality for generated images (1–100)                               | `85`                                                        |
| `useOriginalForLarge`      | Use original image for large version instead of a resized one (`true`, `false`)   | `false`                                                     |
| `useExifDate`              | Use EXIF date for sorting (`true`, `false`)                                   | `true`                                                      |
| `sortAlbums`               | Sort order of albums (`nameASC`, `dateDESC`, `nameASC`, `nameDESC`, `random`)                      | `dateDESC`                                                  |
| `sortImages`               | Sort order of images within an album (`nameASC`, `dateDESC`, `nameASC`, `nameDESC`, `random`)      | `dateDESC`                                                  |
| `albumTitle.enabled`       | Enables or disables transformation of album titles (`true`, `false`)                      | `true`                                                      |
| `albumTitle.transformation`| Transformation applied to album titles (`ucwords`, `ucfirst`, `uppercase`, `lowercase`)   | `ucwords`                                                   |
| `albumTitle.replace`       | Characters to be replaced in album titles                               | `{ "_": " ", "-": " ", "/": " » " }`                        |
| `imageCaption.enabled`     | Enables or disables image captions                                      | `false`                                                     |
| `imageCaption.transformation`| Transformation applied to image captions                              | `ucwords`                                                   |
| `imageCaption.replace`     | Characters to be replaced in image captions                             | `{ "_": " ", "-": " " }`                                    |
| `imageCaption.showInAlbum`| Show image captions in album view                                        | `false`                                                     |
| `imageCaption.showInLightbox`| Show image captions in lightbox view                                  | `true`                                                      |

### Sorting Images and Albums

Albums and Images can be sorted with the following settings:
| Setting    | Descripton  |
| ---------- | ------------- |
| dateASC    | Oldest first - Sorted by exif capture date. If exif data is not available, it uses the last filetime |
| dateDESC   | Newest first - Sorted by exif capture date. If exif data is not available, it uses the last filetime |
| nameASC    | alphabetical ascending |
| nameDESC   | alphabetical descending |
| random     | radmon order with every request |

### Captions
For albums and images captions can be shown. For this the file name is used without the file extension. It's possible to transform file names for the caption. For images you can set if caption is shown in album or lightbox or both.

| Setting      | possible settings  |
| ------------- | ------------- |
| transformation | ucwords, ucfirst, uppercase, lowercase |
| replace | every replacemt has to be an own entry (e.g. `"_": " "` means replace underscore in filename with whitespace).|

## Support

* For support just open a ticket: [novaGallery Support](https://github.com/novafacile/novagallery/issues)

## Support Development: Pro Version without contribution

* To support the development you can buy the Pro version for only $15. This contains exactly the same features as the free version but the mention of "powerd by novaGallery" in the footer is no longer required. In addition, every Pro Version user has the possibility to receive personal support via email.
* Get the Pro Version: [Buy novaGallery Pro Version](https://novagallery.org/#download)

## Contribution

We are open to ideas, improvements and bug fixes. Just create a pull request with your improvements and optimizations. If everything is okay and it fits to our product vision, we will be pleased to merge it.

## Upgrade from v1
novaGallery 2 is a complete rewrite and works fundamentally differently than novaGallery v1. The upgrade requires a few manual steps, but enables an interrupt-free upgrade.

1. prepare and customize the new config file locally before uploading
1. prepare and customize the new .htacces locally before uploading
1. upload new files completely
1. ensure that the WebServer has write permissions in the `storage` folder
1. delete all folders beginning with `nova-`.
1. delete the folder `cache` and the file `filesCache.php` in the gallery folder in each album

Please note that this only works if you have not made any individual modifications. 

**Alternative option:**
1. upload the new version separately
1. Customize the configuration
1. Re-upload or copy photos/albums (delete the old cache folders & files after copying)
1. Switch to the new version

### Developer

## Addon Hooks
This is a list of the available hooks. Please note that the addons functionality is still under development and experimental and therefore not all hooks are fully functional yet. The functionality including hooks and configuration may still change completely.

| Hook Name                    | Description                                                       | Status  |
|-----------------------------|-------------------------------------------------------------------|---------|
| `beforeAll`                 | Triggered before loading all routes                               | testing  |
| `afterAll`                  | Triggered after all routes have been processed                    | testing  |
| `beforeRouting`             | Triggered before the routing process starts                       | testing  |
| `afterRouting`              | Triggered after the routing process ends                          | testing  |
| `beforePage`                | Triggered before rendering a page                                 | testing  |
| `afterPage`                 | Triggered after a page has been rendered                          | testing  |
| `beforeImage`               | Triggered before an image is processed or displayed               | development  |
| `afterImage`                | Triggered after an image has been processed or displayed          | development  |
| `beforeTheme`               | Triggered before the theme is initialized                         | testing  |
| `beforeLoadGallery`         | Triggered before the gallery content is loaded                    | development  |
| `afterLoadGallery`          | Triggered after the gallery content has been loaded               | development  |
| `templateHead`              | Triggered after the `<head>` section of the HTML template         | testing  |
| `templateBodyBegin`         | Triggered after the `<body>` tag                                  | testing  |
| `templateNavigationBegin`   | Triggered before rendering the navigation section begins          | testing  |
| `templateNavigationEnd`     | Triggered after the navigation section ends                       | testing  |
| `templateBeforePageTitle`   | Triggered before the page title is rendered                       | testing  |
| `templateAfterPageTitle`    | Triggered after the page title is rendered                        | testing  |
| `templateBeforeAlbumList`   | Triggered before the album list is rendered                       | development  |
| `templateAfterAlbumList`    | Triggered after the album list is rendered                        | development  |
| `templateBeforeAlbum`       | Triggered before a single album block is rendered                 | development  |
| `templateAfterAlbum`        | Triggered after a single album block is rendered                  | development  |
| `templateBeforeAlbumCover`  | Triggered before the album cover image is rendered                | development  |
| `templateAfterAlbumCover`   | Triggered after the album cover image is rendered                 | development  |
| `templateBeforeImageList`   | Triggered before the list of images is rendered                   | testing  |
| `templateAfterImageList`    | Triggered after the list of images is rendered                    | testing  |
| `templateBeforeImage`       | Triggered before a single image block is rendered                 | development  |
| `templateAfterImage`        | Triggered after a single image block is rendered                  | development  |
| `templateBeforeImageCover`  | Triggered before the main image cover is rendered                 | development  |
| `templateAfterImageCover`   | Triggered after the main image cover is rendered                  | development  |
| `templateBodyEnd`           | Triggered before the closing `</body>` tag                        | testing  |



## Used Packages

We are very grateful to the creators of the following great packages that we use for novaGallery:

* [SteamPixelPHPRouter](https://github.com/steampixel/simplePHPRouter)
* [SimpleImage](https://github.com/claviska/SimpleImage)
* [Bootstrap](https://getbootstrap.com)
* [SimpleLightbox](https://simplelightbox.com)
* Some concepts are inspired by the wonderful flat file CMS [Bludit](https://www.bludit.com)

## Copyright & License

* All rights reserved by [novafacile OÜ](https://novafacile.com)
* License: GNU Affero General Public License (A-GPL 3.0)
* To remove all public copyright & "powered by" mentions, you can purchase a life time license on [novagallery.org](https://novagallery.org) for only 15,00 €.
