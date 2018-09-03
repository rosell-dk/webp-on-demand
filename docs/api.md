# WebP On Demand API

**WebPOnDemand::convert($source, $destination, $options)**

| Parameter        | Type    | Description                                                         |
| ---------------- | ------- | ------------------------------------------------------------------- |
| `$source`        | String  | Absolute path to source image (only forward slashes allowed)        |
| `$destination`   | String  | Absolute path to converted image (only forward slashes allowed)     |
| `$options`       | Array   | Array of conversion (option) options                                |

## The *$options* argument

The options argument is a named array. *WebPOnDemand* has just a few available options. However, the options will be handed over to *WebPConvertAndServe*, which in turn hands them over to  *WebPConvert*. So Any option available in these two libraries are available here.


### *show-report*
Produce a report rather than serve an image.
Default value: *false*

### *reconvert*
Force a conversion, discarding existing converted image (if any)
Default value: *false*

### *original*
Forces serving original image
Default value: *false*

### *add-x-webp-on-demand-headers*
When set to *true*, a *X-WebP-On-Demand* header will be added describing how things went.
Default value: *true*

Depending on how things goes, the header will be set to one of the following:
- Failed (Missing source argument)
- Failed (Missing destination argument)
- Reporting...
- Serving original image (was explicitly told to)
- Serving original image - because it is smaller than the converted!
- Serving existing converted image
- Converting image (handed over to WebPConvertAndServe)

### *add-vary-header*
Add a "Vary: Accept" header when an image is served. Experimental.
Default value: *false*

### *require-for-conversion*
Makes the library 'require in' a file just before calling WebPConvertAndServe. This is not needed for composer projects, as composer takes care of autoloading classes when needed. The option is here for making it possible to have a simple, yet flexible setup without composer. The file that is required in could be a one-file version of all webp-convert-and-serve, including its dependies (webp-convert).
Default value: *null*
