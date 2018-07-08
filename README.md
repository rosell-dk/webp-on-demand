# WebP on demand

This is a solution for serving auto-generated WebP images instead of JPEG/PNG to browsers that supports WebP (Google Chrome, that is). It works by `.htaccess magic` coupled with an image converter (using *NGINX*? &ndash; try looking [here](https://github.com/S1SYPHOS/kirby-webp#nginx)). Basically, JPEGS and PNGS are routed to the image converter, unless it has already converted the image. In that case, it is routed directly to the converted image.

The image converter is able to use several methods to convert the image (`imagick`, `gd` directly calling `cwebp` binary or connecting to ewww image optimizer cloud service). Read more on its [project page](https://github.com/rosell-dk/webp-convert).

A cool thing about this solution is that it works on all images, no matter how they are referenced. It for example also works on images referenced in CSS. Also, it does not require any change in the HTML. So it can easily be integrated into any framework.

## Installation

#### 1. Clone or download this repository
#### 2. Install dependencies
- Run composer: `composer install`

#### 3. Get `webp-on-demand.php` up and running
1. Copy the supplied `webp-on-demand.php.example` file to the part of your website that you want WebPOnDemand to work on (usually webroot). And remove '.example' from filename.
- Test that the converter is working. Place an image in same folder as `webp-on-demand.php`. And then point your browser to `http://your-domain.com/your-folder/webp-on-demand.php?source=your-image.jpg&debug` in your browser. The debug parameter causes the script to return text instead of the converted image.

#### 4. Get the `.htaccess` file up and running
1. Decide how you wish the converted files to be organized. You must choose between 1) having them placed in the same folder as the source file or 2) having them all placed in their own common folder.
- Create a `.htaccess` file in the same folder as `webp-on-demand` and copy the content of one of the supplied `.htaccess.example` files into it. If you want the converted files to be placed in the same folder as the source file, go for  `.htaccess.example1`. If you want the converted files to be placed in their own common folder, go for `.htaccess.example2a` or `.htaccess.example2b`. Choose the "a" version, if you have placed `webp-on-demand.php` in the root. Choose the "b" version, if you choose "b", you will need to do a search/replace for "your-folder". And don't forget to read the comments.
- Test that the `.htaccess` is routing your image to the image converter by pointing your browser to `http://your-domain.com/your-folder/webp-on-demand.php?your-image.jpg&debug`. This is possible, because the `.htaccess` is set up to forward the querystring. So all options available for the script can be passed this way. You could for example use this to set higher quality on selected images.

#### 5. Use it!
You do not have to make any changes to your existing HTML or CSS. The routing and conversion are now done automatically. To confirm that it works:

1. Visit a page on your site with an image on it, using *Google Chrome*.
- Right-click the page and choose "Inspect"
- Click the "Network" tab
- Reload the page
- Find a jpeg or png image in the list. In the "type" column, it should say "webp"


## Troubleshooting the `.htaccess` file.
By appending `?debug` to your image url, you get a report (text output) instead of the converted image. If there is no report, it means that the `.htaccess` is not working as intended.

By appending `?reconvert` to your image url, you bypass the automatic routing to existing source files.

Is something not working?
- Perhaps there are other rules in your `.htaccess` that interfere with the rules?
- Perhaps your site is on Apache, but it has been configured to use *Nginx* to serve image files. You then need to reconfigure your server setup. Or create Nginx rules. There are some [here](https://github.com/S1SYPHOS/kirby-webp#nginx).


## `webp-on-demand.php` options.

You add options to `webp-on-demand.php` directly in the `.htaccess`. You can however also add them after an image url.

| option                       | Description                                          |
| ---------------------------- | --------------------------------------------- |
| *source*                       | Path to source file.<br><br>Path is relative to the `webp-on-demand.php` script. If it starts with "/", it is considered an absolute path.|
| *destination-root* (optional)  | Default is ".", meaning that the destination folder will be the same as the source folder. <br><br>Path is relative to the `webp-on-demand.php` script. If it starts with "/", it is considered an absolute path.|
| *quality* (optional)           | The quality of the generated WebP image, "auto" or 0-100. Defaults to "auto". See [WebPConvert](https://github.com/rosell-dk/webp-convert#methods) docs |
| *max-quality* (optional)        | The maximum quality. Only relevant when quality is set to "auto" |
| *default-quality* (optional)    | Fallback value for quality, if it isn't possible to detect quality of jpeg. Only relevant when quality is set to "auto" |
| *metadata* (optional)          | If set to "none", all metadata will be stripped. If set to "all", all metadata will be preserved. See [WebPConvert](https://github.com/rosell-dk/webp-convert#methods) docs |
| *converters* (optional)        | Comma-separated list of converters. Ie. "cwebp,gd". To pass options to the individual converters, see next. Also, check out the [WebPConvert](https://github.com/rosell-dk/webp-convert#methods) docs |
| *[converter-id]-[option-name]* (optional)  | This pattern is used for setting options on the individual converters. Ie, in order to set the "key" option of the "ewww" converter, you pass "ewww-key".
| *[converter-id]-[n]-[option-name]* (optional)  | Use this pattern for targeting options of a converter, that are used multiple times. However, use the pattern above for targeting the first occurence. `n` stands for the nth occurence of that converter in the `converters` option. Example: `...&converters=cwebp,ewww,ewww,gd,ewww&ewww-key=xxx&ewww-2-key=yyy&ewww-3-key=zzz&gd-skip-pngs=1` |
| *[converter-id]-[option-name]-[2]* (optional) | This is an alternative, and simpler pattern than the above, for providing fallback for a single converter. If WebPOnDemand detects that such an option is provided (ie ewww-key-2=yyy), it will automatically insert an extra converter into the array (immidiately after), configured with the options with the '-2' postfix. Example: `...&converters=cwebp,ewww,gd&ewww-key=xxx&ewww-key-2=yyy` - will result in converter order: cwebp, ewww (with key=xxx), ewww (with key=yyy), gd |
| *debug* (optional)             | If set, a report will be served (as text) instead of an image |
| *fail* (optional)              | What to serve if conversion fails. Default is  "original". Possible values: "original", "404", "report", "report-as-image". See [WebPConvertAndServe](https://github.com/rosell-dk/webp-convert-and-serve#api) docs|
| *critical-fail* (optional)              | What to serve if conversion fails and source image is not availabl Default is  "error-as-image". Possible values: "original", "404", "report", "report-as-image". See [WebPConvertAndServe](https://github.com/rosell-dk/webp-convert-and-serve#api) docs |


## The .htacesss example files

Three different example files are supplied:

`.htaccess.example1`
Puts the converted files in the same folder as the original. The converted files gets the same name as the original plus ".webp". Ie. "image.jpg" will be converted into "image.jpg.webp"

`.htaccess.example2a`
Puts the converted files into a folder dedicated for the converted files. The converted files will then be organized into the same structure as the original. If you for example set the folder to be "webp-cache", then "/images/2017/fun-at-the-hotel.jpg" will be converted into "/webp-cache/images/2017/fun-at-the-hotel.jpg"

`.htaccess.example2b`
Same as 2a, but for the case where `.htaccess` isn't in webroot, but in a subfolder


## Configuring the converter

You configure the options to the image converter directly in the ```.htaccess```.

If you want to have a different quality on a certain image, you can append "&reconvert&quality=95" to the image url. You can in fact override any change any converter option like this.

You configure the options to the image converter directly in the ```.htaccess```.

If you want to have a different quality on a certain image, you can append "&reconvert&quality=95" to the image url. You can in fact override any change any converter option like this.


## Requirements

* Apache web server (not tested on LiteSpeed yet)
* PHP > 5.5.0
* That one of the WebP converters are working (these have different requirements)


## FAQ

### How do I make this work with a CDN?
Chances are that the default setting of your CDN is not to forward any headers to your origin server. But the solution needs the "Accept" header, because this is where the information is whether the browser accepts webp images or not. You will therefore have to make sure to configure your CDN to forward the "Accept" header.

The .htaccess takes care of setting the "Vary" HTTP header to "Accept" when routing WebP images. When the CDN sees this, it knows that the response varies, depending on the "Accept" header. The CDN is thus instructed not to cache the response on URL only, but also on the "Accept" header. This means that it will store an image for every accept header it meets. Luckily, there are (not that many variants for images[https://developer.mozilla.org/en-US/docs/Web/HTTP/Content_negotiation/List_of_default_Accept_values#Values_for_an_image], so it is not an issue.


## Detailed explanation of how it works

### Workflow:

1. The .htaccess routes convert request to *webp-on-demand.php*, with options in query string
2. *webp-on-demand.php* basically just calls `WebPOnDemand::serve(__DIR__)`
3. `WebPOnDemand::serve` routes the options to  [WebPConvertAndServe](https://github.com/rosell-dk/webp-convert-and-serve)
4. `WebPConvertAndServe` in turn delegates the conversion to [WebPConvert](https://github.com/rosell-dk/webp-convert)

### The Apache configuration files

Below follows some detailed explanation of the .htaccess files. *Note however that these needs updating*

### With location set to same folder as original (option 1)
When the destination of the converted files is set to be the same as the originals, the .htaccess contains the following code (comments removed)

```
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /

  RewriteCond %{HTTP_ACCEPT} image/webp
  RewriteRule ^(.*)\.(jpe?g|png)$ $1.$2.webp [NC,T=image/webp,E=accept:1]

  RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]
  RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp !-f
  RewriteCond %{QUERY_STRING} (.*)
  RewriteRule ^(.*)\.(jpe?g|png)\.(webp)$ webp-convert/webp-convert.php?source=$1.$2&quality=85&preferred-converters=imagick,cwebp,gd&serve-image=yes&%1 [NC]

</IfModule>
<IfModule mod_headers.c>
    Header append Vary Accept env=REDIRECT_accept
</IfModule>
AddType image/webp .webp
```

Lets take it line by line, skipping the first couple of lines:

```RewriteCond %{HTTP_ACCEPT} image/webp```\
This condition makes sure that the following rule only applies when the client has sent a HTTP_ACCEPT header containing "image/webp". In other words: The next rule only activates if the browser accepts WebP images.

```RewriteRule ^(.*)\.(jpe?g|png)$ $1.$2.webp [NC,T=image/webp,E=accept:1]```\
This line rewrites any request that ends with ".jpg", ".jpeg" or ".png" (case insensitive). The target is set to the same as source, but with ".webp" appended to it. Also, MIME type of the response is set to "image/webp" (my tests shows that Apache gets the MIME type right without this, but better safe than sorry - it might be needed in other versions of Apache). The E flag part sets the environment variable "accept" to 1. This is used further down in the .htaccess to conditionally append a Vary header. So setting this variable means that the Vary header will be appended if the rule is triggered. The NC flag makes the match case insensitive.

```RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]```\
This line tests if the query string starts with "reconvert" or "debug". If it does, the rule that passes the request to the image converter will have green light to go. So appending "&reconvert" to an image url will force a new convertion even though the image is already converted. And appending "&debug" also bypasses the file check, and actually also results in a reconversion - but with text output instead of the image.

```RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp !-f```\
This line adds the condition that the image is not already converted. The $1 and $2 refers to matches of the following rule. The condition will only match files ending with ".jpeg.webp", "jpg.webp" or "png.webp". As a webp is thus requested, it makes sense to have the rule apply even to browsers not accepting "image/webp".

```RewriteCond %{QUERY_STRING} (.*)```\
This line enables us to use the query string in the following rule, as it will be available as "%1". The condition is always met.

```RewriteRule ^(.*)\.(jpe?g|png)\.(webp)$ webp-convert/webp-convert.php?source=$1.$2&quality=85&preferred-converters=imagick,cwebp,gd&serve-image=yes&%1 [NC]```\
This line rewrites any request that ends with ".jpg", ".jpeg" or ".png" to point to the image converter script. The php script get passed a "source" parameter, which is the file path of the source image. The script also accepts a destination root. It is not set here, which means the script will save the the file in the same folder as the source. The %1 is the original query string (it refers to the match of the preceding condition). This enables overiding the converter options set here. For example appending "&debug&preferred-converters=gd" can be used to test the gd converter. Or "&reconvert&quality=100" can be appended in order to reconvert the image using better quality.

```Header append Vary Accept env=REDIRECT_accept```\
This line appends a response header containing: "Vary: Accept", but only when the environment variable "accept" is set by the "REDIRECT" module.


### With location set to specific folder (option 2)
When the destination of the converted files is set to be a specific folder as the originals, the core functionality lies in the following code:

```
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]
RewriteCond %{DOCUMENT_ROOT}/webp-cache/$1.$2.webp !-f
RewriteCond %{QUERY_STRING} (.*)
RewriteRule ^(.*)\.(jpe?g|png)$ webp-convert/webp-convert.php?source=$1.$2&quality=80&destination-root=webp-cache&preferred-converters=imagick,cwebp&serve-image=yes&%1 [NC,T=image/webp,E=accept:1]

RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{QUERY_STRING} !((^reconvert.*)|(^debug.*))
RewriteCond %{DOCUMENT_ROOT}/webp-cache/$1.$2.webp -f
RewriteRule ^(.*)\.(jpe?g|png)$ /webp-cache/$1.$2.webp [NC,T=image/webp,E=accept:1,QSD]
```

The code is divided in two blocks. The first block takes care of delegating the request to the image converter under a set of conditions. The other block takes care of rewriting the url to point to an existing image under another set of conditions. The two set of conditions are mutual exclusive.

You should know that in *mod_rewrite*, OR has higher precedence than AND [[ref](https://stackoverflow.com/questions/922399/how-to-use-and-or-for-rewritecond-on-apache)]. The first set of conditions thus reads:

If *Browser accepts webp images* AND (*Query string begins with "reconvert" or "debug"* OR *There is no existing converted image*) AND *Query string is empty or not*

The last condition is always true. It is there to make the query string available to the following rule.

The other set of conditions reads;
If *Browser accepts webp images* AND *Query string does not begin with "reconvert" or "debug"* AND *There is an existing converted image*

Otherwise it is the same kind of stuff that is going on as in option 1 - which is described in the preceding section. Oh, there is the QSD flag. It tells Apache to strip the query string.




## A similar project
This project is very similar to [WebP realizer](https://github.com/rosell-dk/webp-realizer). *WebP realizer* assumes that the conditional part is in HTML, like this:
```
<picture>
  <source srcset="images/button.jpg.webp" type="image/webp" />
  <img src="images/button.jpg" />
</picture>
```
And then it automatically generates "image.jpg.webp" for you, the first time it is requested.\
Pros and cons:

- *WebP on demand* works on images referenced in CSS (*WebP realizer* does not)\
- *WebP on demand* requires no change in HTML (*WebP realizer* does)\
- *WebP realizer* works better with CDN's - CDN does not need to cache different versions of the same URL

## Ideas

* Only serve webp when filesize is smaller than original (ie. the script can generate an (extra) file image.jpg.webp.too-big.txt when filesize is larger - the htaccess can test for its existence)
* Is there a trick to detect that the original has been updated?

## Related
* [My original post presenting the solution](https://www.bitwise-it.dk/blog/webp-on-demand)
* [WebP Express](https://github.com/rosell-dk/webp-express). A Wordpress adaptation of the solution.
* https://www.maxcdn.com/blog/how-to-reduce-image-size-with-webp-automagically/
