This document isn't ready yet.

# Installing WebP On Demand without composer

A setup consists of these parts:

- *Redirect rules* that redirects JPG/PNG images to *webp-on-demand.php*.
- *webp-on-demand.php* that handles a request. If it needs to convert an image, it will load *webp-convert-and-serve.inc*
- *webp-convert-and-serve.inc* contains the webp-convert-and-serve library all in one file (including the dependent *webp-convert* library)
- *webp-on-demand-options.inc*. A configuration file automatically loaded by *webp-on-demand.php*.


### 1. Copy the latest build files into your website
Copy *webp-on-demand.php* and *webp-convert-and-serve.inc* from the *build* folder into your website. They can be located wherever you like, but they need to reside in the same folder.

### 2. Create a *webp-on-demand-options.inc* in same folder

The file must define two variables: `$options` and `$destination`
Here is a minimal example to get started with:
```
<?php
$destination = $_GET['source'] . '.webp';    // Store the converted images besides the original images (other options are available!)
$options = [
    // UNCOMMENT NEXT LINE, WHEN YOU ARE UP AND RUNNING!    
    'show-report' => true                    // Show a conversion report instead of serving the converted image.
];
```

### 3. Add redirect rules
Place the following rewrite rules in a .htaccess file in the directory you want WebPOnDemand to do its magic:

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect images to webp-on-demand.php (if browser supports webp)
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteRule ^(.*)\.(jpe?g|png)$ webp-on-demand.php?source=%{SCRIPT_FILENAME} [NC,L]
</IfModule>

AddType image/webp .webp
```
If you have placed *webp-on-demand.php* in a subfolder, you will need to change the rewrite rule accordingly.

### 4. Validate that it works

Browse to an JPEG image. Instead of an image, you should see a conversion report. Hopefully, you get a success. Otherwise, you need to hook up to a cloud converter or try to meet the requirements for cwebp, gd or imagick. You can learn more about available options at the github page for [webp-convert](https://github.com/rosell-dk/webp-convert)

Once you get a successful conversion, you can uncomment the "show-report" option in *webp-on-demand-options.inc*

It should work now, but to be absolute sure:

1. Visit a page on your site with an image on it, using *Google Chrome*.
- Right-click the page and choose "Inspect"
- Click the "Network" tab
- Reload the page
- Find a jpeg or png image in the list. In the "type" column, it should say "webp". There should also be a *X-WebP-On-Demand* header on the image.

### 5. Customize

You can return to the main README for further instructions
