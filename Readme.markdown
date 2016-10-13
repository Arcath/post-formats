# post-formats

Provides meta boxes and all required code to get the WordPress post formats working in the admin panel.

_does not provide any helpers for displaying in your theme._

## Install

You can install through composer

```
composer require arcath/post-formats
```

## Usage

Requiring composer autoload will add the PostFormats class.

_functions.php_
```php
require_once('vendor/autoload.php');

$formats = array('gallery', 'video');
asort($formats);
$post_formats = new PostFormats($formats, array('post', 'page'));
```

`new PostFormats` takes 2 arguments, an arry of formats to enable (which I have sorted alphabetically above) and an array of post types to enable them for.

### In your theme

The contents of the meta box is stored in `_post_format_{POST_FORMAT}` in the post meta.

For example to get the array of gallery IDs you would look at `_post_format_gallery`.

More info can be found in [this post](https://arcath.net/2016/10/post-formats-theme/).
