# novaGallery

novaGallery is a beautiful php photo gallery with the focus on your images, trimmed for ease of use and low demands on web space. You just need a webserver with PHP 7.X support. No database is required. Simple to use, easy customizable and beautiful. 

Product Page: [novaGallery.org](https://novagallery.org)

## Features

### Overview

* **Albums**: Directories are rendered as albums
* **Sub-Albums**: 
* **Preview images** for albums: Automatically generate an album preview image
* **Photo overview**: All photos are displayed sorted by creation date or name
* **Lightbox**: View your photos in large format, hiding everything that is distracting
* **Slideshow**: All photos in an album, can be displayed and presented via click (or keyboard) in the lightbox view
* **Use your own upload method**: Simply upload the photos into the gallery folder (e.g. via FTP) and the photos are already online
* **Thumbnails**: Automatic generation of thumbnails
* **File compression**: Photos with high file size, are automatically compressed with very little loss.
* **JPEG & PNG**: JPG and PNG images are supported.
* **Custom Themes**: With just a little knowledge of CSS and HTML, the look can be customized.
* **Languages**: You can run the gallery in your langauage
* **Private Mode**: You can set a password to protect your gallery from public access.

### Screenshots

![novaGallery Home](https://novagallery.org/img/novagallery-home-400.jpg "novaGallery Home") ![novaGallery Album](https://novagallery.org/img/novagallery-album-400.jpg "novaGallery Album")
![novaGallery Lightbox](https://novagallery.org/img/novagallery-lightbox-400.jpg "novaGallery Lightbox") ![novaGallery Private Mode](https://novagallery.org/img/novagallery-private-400.jpg "novaGallery Private Mode")

### Demo

* [novaGallery Demo](https://demo.novagallery.org/)

### Requirements

* Apache web server or similar
* PHP 7.X
* PHP-GD Extension
* PHP Exif Support

## Setup

### Installation

* Download latest version: [Download novaGallery (latest version)](https://github.com/novafacile/novagallery/raw/main/releases/novagallery-latest.zip)
* Unzip files
* Upload files to your webspace
* Copy `nova-config/site.example.php` to `nova-config/site.php`
* Edit the config in `nova-config/site.php`
* Upload your photos into galleries
* Enjoy your new photo gallery

### Configuration

* Every folder in galleries is a gallery
* In `nova-config/site.php` you can change some basic informations, image sizes and cache settings
* That's it :-)

### Manage Images

* **Add Photos**: Just upload the new photo to the server into the correct album (e.g via FTP)
* **Delete a Photos**: Just delete the photo at the server. To save web space it's recommended to delete the cached files also.
* **Delete an Album**: Just delete the whole album (directory) at the server
* **Reset Cache**: Every album has a cache directory. Just delete this directory.


### Themes

* Creating a new template is quite simple and works with basic PHP without any extra template engine.
* To create a new template, the basic template (based on Bootstrap 4) can be duplicated, customized and activated in the settings.

### Languages

* Some populare languages are already added (English, German, Spanisch, French)
* Set your language in `nova-config/site.php`
* You can create easily your own language. Just copy one of the language files, translate it and save it with your language code
* It would be nice, if you let us know if you created a new language file. Just send us a message or create a pull request.

### Private Mode
* In `nova-config/site.php` you can set a PHP password hash
* The password hash has to be created with the standard PHP function `password_hash()`
* If you can't generate a password hash by your own, you can use our password hash generatore: [Password Hash Generator](https://tools.nova.ms/password-hash-generator)

## Nice to Know

* Supported images: JPEG & PNG
* At the first time an album is opened, the thumbnails are generated. This may take a little time. After that the cached images are used for each visit.
* If you don't use an apache2 web server with .htaccess support (e.g. nginx), you have to set the required rewrite rules from the .htaccess file at the webserver config

## Support

* For support just open a issue ticket: [novaGallery Support](https://github.com/novafacile/novagallery/issues)

## Contribution

We are open to ideas, improvements and bug fixes. Just create a pull request with your improvements and optimizations. If everything is okay and it fits to our product vision, we will be pleased to merge it.

## Used Packages

We are very grateful to the creators of the following great packages that we use for novaGallery:

* [SteamPixelPHPRouter](https://github.com/steampixel/simplePHPRouter)
* [GImage](https://joseluisq.github.io/gimage)
* [Bootstrap](https://getbootstrap.com)
* [SimpleLightbox](https://simplelightbox.com)
* Some concepts are inspired by the wonderful flat file CMS [Bludit](https://www.bludit.com)

## Copyright & License

* All rights reserved by [novafacile OÜ](https://novafacile.com)
* License: GNU Affero General Public License (A-GPL 3.0)
* To remove all public copyright & "powered by" mentions, you can purchase a life time license on [novagallery.org](https://novagallery.org) for only 15,00 €.
