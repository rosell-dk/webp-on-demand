# WebP on demand

This is a solution for automatically serving WebP images instead of jpeg/pngs for browsers that supports WebP (Google Chrome, that is).

Once set up, it will automatically convert images, no matter how they are referenced. It for example also works on images referenced in CSS. As the solution does not require any change in the HTML, it can easily be integrated into any website / framework (A Wordpress adaptation was recently published on [wordpress.org](https://wordpress.org/plugins/webp-express/) - its also on [github](https://github.com/rosell-dk/webp-express))


WebP on demand consists of two parts.

- *The redirect rules* detects whether a requested image has already been converted. If so, it redirects it to the converted image. If not, it redirects it to the *image converter*

- *The image converter* converts, saves and serves the image. We are using [webp-convert-and-serve](https://github.com/rosell-dk/webp-convert-and-serve) library for this.

The redirect rules are written for Apache. They do not work on *LiteSpeed* servers (even though LiteSpeed claims compliance). I am currently working on LiteSpeed compliance. If you are on *NGINX*, try looking [here](https://github.com/S1SYPHOS/kirby-webp#nginx)


## Installation

#### 1. Clone or download this repository
#### 2. Install dependencies
- Run composer: `composer install`

#### 3. Get `webp-on-demand.php` up and running
1. Copy the supplied `webp-on-demand.php.example` file to the part of your website that you want WebPOnDemand to work on (usually webroot). And remove '.example' from filename.
- Test that the converter is working. Place an image in same folder as `webp-on-demand.php`. And then point your browser to `http://your-domain.com/your-folder/webp-on-demand.php?source=your-image.jpg&debug` in your browser. The debug parameter causes the script to return text instead of the converted image.

#### 4. Get the `.htaccess` file up and running

##### 4.1 Choose the appropiate .htaccess example file
There are multiple .htaccess example files to choose from, depending on your needs. So first, you must decide where you want the converted files to reside. You have two options:

*Same as source file*
Puts the converted files in the same folder as the original. The converted files gets the same name as the original plus ".webp". Ie. "image.jpg" will be converted into "image.jpg.webp"

*In a common folder*
Puts the converted files into a folder dedicated for the converted files. The converted files will then be organized into the same structure as the original. If you for example set the folder to be "webp-cache", then */images/2017/fun-at-the-hotel.jpg* will be converted into */webp-cache/images/2017/fun-at-the-hotel.jpg*

Now, choose the appropriate example file, using this table:

| Location of converted files | Location of webp-on-demand.php | .htaccess to copy from
| -- | -- | -- |
| Same as source file    |  webroot  |  `.htaccess.example1a` |
| Same as source file    |  subfolder to webroot  |  `.htaccess.example1b` |
| In a separate folder     |  webroot  |  `.htaccess.example2a`  |
| In a separate folder     |  subfolder to webroot  |  `.htaccess.example2b`  |

##### 4.2 Copy content from the .htaccess example file
- Create a .htaccess file in the *same folder* as webp-on-demand.php
- Copy the content of the appropiate example file

##### 4.2 For b-versions, substitute folder name
If you have choosen one of the "b"-versions, you will have to enter the path in the .htaccess (do a search/replace for "your-folder"). Don't forget to read the comments in the .htaccess.

##### 4.3 Test the routing
Test that the `.htaccess` is routing your image to the image converter by pointing your browser to `http://your-domain.com/your-folder/your-image.jpg&debug`. If you should a textual report, the redirect is working. If you see an image, it is not working. (the `.htaccess` rules are set up to forward the querystring, so - if things are working correctly - webp-on-demand.php will be called with "?debug", and therefor produce a textual report)

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

### The Apache configuration files in details

Lets make a walk-through of .htaccess-example1a. The other files are very similar, so walking through one of them should suffice.

The rules read:
```
<IfModule mod_rewrite.c>

    RewriteEngine On

    # Redirect to existing converted image (under appropriate circumstances)
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{QUERY_STRING} !((^reconvert.*)|(^debug.*))
    RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp -f
    RewriteRule ^\/?(.*)\.(jpe?g|png)$ $1.$2.webp [NC,T=image/webp,E=accept:1,QSD]

    # Redirect to converter (under appropriate circumstances)
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]
    RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp !-f
    RewriteCond %{QUERY_STRING} (.*)    # Always true. Enables us to grab the query string in the following rule
    RewriteRule ^\/?(.*)\.(jpe?g|png)$ webp-on-demand.php?source=$1.$2&destination-root=.&quality=80&fail=original&critical-fail=report&%1 [NC,E=accept:1]

</IfModule>

# Making CDN caching possible.
# The effect is that the CDN will cache both the webp image and the jpeg/png image and return the proper image to the
# proper clients (for this to work, make sure to set up CDN to forward the "Accept" header)
<IfModule mod_headers.c>
    Header append Vary Accept env=REDIRECT_accept
</IfModule>
```

First thing to notice is that the code is divided in two blocks. The first redirects to an existing converted image (under appropriate conditions), the second redirects to the image converter (under appropriate conditions)

Also, the blocks only kick in, if the browser supports webp. And there are also lines ensuring that if the image is called with a "debug" or "reconvert" parameter, it will redirect to the converter, rather than to an existing image. The two set of conditions are created such that they are mutual exclusive.

Lets break it down.

#### Redirecting to existing image

First block reads:
```
# Redirect to existing converted image (under appropriate circumstances)
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{QUERY_STRING} !((^reconvert.*)|(^debug.*))
RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp -f
RewriteRule ^\/?(.*)\.(jpe?g|png)$ $1.$2.webp [NC,T=image/webp,E=accept:1,QSD]
```

*Lets take it line by line:*

`RewriteCond %{HTTP_ACCEPT} image/webp`
This makes sure that the following rule only kicks in when the browser supports webp images. Browsers supporting webp images are obliged to sending a HTTP_ACCEPT header containing "image/webp", which we test for here.

`RewriteCond %{QUERY_STRING} !((^reconvert.*)|(^debug.*))`
This makes sure that the query string does not begin with "reconvert" or "debug" (we want those requests to be redirected to the converter, even when a converted file exists)

`RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp -f`
This makes sure there is an existing converted image. The $1 and $2 refers to matches of the following rule. You may think it is weird that we can reference matches in a rule not run yet, in a condition to that very rule. I agree - mod_rewrite is a complex beast.

`RewriteRule ^\/?(.*)\.(jpe?g|png)$ $1.$2.webp [NC,T=image/webp,E=accept:1,QSD]`
Rewrites any request that ends with ".jpg", ".jpeg" or ".png" (case insensitive). The first parentheses makes grabs the file path, which can then be referenced with $1. The second parentheses grabs the file extension into $2. These referenced are used in the preceding condition as well as in the rule itself. The effect of the rewrite is that the target is set to the same as source, but with ".webp" appended to it. Also, MIME type of the response is set to "image/webp" (not necessary, though). The E flag part sets the environment variable "accept" to 1. This is used further down in the .htaccess to conditionally append a Vary header. So setting this variable means that the Vary header will be appended if the rule is triggered. The NC flag makes the match case insensitive. The QSD flag tells Apache to strip the query string. The "\/?" is added to the beginning in order to support LiteSpeed web servers.

#### Redirecting to image converter
Second block redirects to *the image converter* (under appropiate circumstances)

```
# Redirect to converter (under appropriate circumstances)
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]
RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp !-f
RewriteCond %{QUERY_STRING} (.*)    # Always true. Enables us to grab the query string in the following rule
RewriteRule ^\/?(.*)\.(jpe?g|png)$ webp-on-demand.php?source=$1.$2&destination-root=.&quality=80&fail=original&critical-fail=report&%1 [NC,E=accept:1]
```

*Lets take it line by line*:
`RewriteCond %{HTTP_ACCEPT} image/webp`
We have covered this...

`RewriteCond %{QUERY_STRING} (^reconvert.*)|(^debug.*) [OR]`
If query string contains a "reconvert" or "debug", the block will be activated, even if there exists a converted file. Notice the "[OR]". Know that OR has higher precedence than AND [[ref](https://stackoverflow.com/questions/922399/how-to-use-and-or-for-rewritecond-on-apache)].

`RewriteCond %{DOCUMENT_ROOT}/$1.$2.webp !-f`
Make sure there aren't an existing image (OR above condition)

`RewriteCond %{QUERY_STRING} (.*)`
This is always true. The condition is there to enable us to pass on the querystring from image request to the converter in the next rule, where it will be accessible as "%1"

`RewriteRule ^\/?(.*)\.(jpe?g|png)$ webp-on-demand.php?source=$1.$2&destination-root=.&quality=80&fail=original&critical-fail=report&%1 [NC,E=accept:1]`

This line rewrites any request that ends with ".jpg", ".jpeg" or ".png" (case insensitive) to the image converter. You can remove "|png" from the line, if you do not want to convert png files. The flags are the same as in the other rewrite, except that "T=image/webp" has been removed. It was originally there, but I removed it in order for it to work in LiteSpeed. webp-on-demand.php sets a Content Type header, so it was not needed in the first place. Well, actually, webp-on-demand.php may set content type to text/html (when returning error report), or image/gif, when returning error report as image - so setting it to something different here, is asking for trouble. The "\/?" in the beginning was also added for LiteSpeed support. %1 prints the query string fetched in the preceding condition. This enables overiding the converter options set here. For example appending "&debug&preferred-converters=gd" to an url that points to an image can be used to test the gd converter. Or "&reconvert&quality=100" can be appended in order to reconvert the image using extreme quality.

#### Dealing with CDN

```
<IfModule mod_headers.c>
    Header append Vary Accept env=REDIRECT_accept
</IfModule>
```

This line appends a response header containing: "Vary: Accept", but only when the environment variable "accept" is set above. env="REDIRECT_accept" instructs mod_headers only to append the header, when the "accept" environment variable is set by the "REDIRECT" module.


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
