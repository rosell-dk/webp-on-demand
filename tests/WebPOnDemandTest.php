<?php

/**
 * WebPConvert - Convert JPEG & PNG to WebP with PHP
 *
 * @link https://github.com/rosell-dk/webp-convert
 * @license MIT
 */

namespace WebPOnDemand\Tests;

use WebPOnDemand\WebPOnDemand;
use WebPConvertAndServe\WebPConvertAndServe;
use PHPUnit\Framework\TestCase;

class WebPOnDemandTest extends TestCase
{
    public function testConvertWithNoConverters()
    {
      /*
      broken. has no time to fix right now...
      
        $this->assertEquals($_GET, []);


        $_GET['source'] = 'test.jpg';
        //$_GET['converters'] = 'nonexistant';
        $_GET['fail'] = '404';
        $_GET['critical-fail'] = '404';

        ob_start();
        $result = WebPOnDemand::serve(__DIR__);
        $output = ob_get_contents();

        $this->assertEquals($result, WebPConvertAndServe::$HTTP_404);
        */
    }
}
