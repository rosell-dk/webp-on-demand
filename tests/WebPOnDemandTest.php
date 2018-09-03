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
    public function testLogic()
    {
        $this->assertEquals(1, 1);
    }
/*
    public function testCriticalFailureOriginal()
    {

        $_GET = [
            'source' => 'non-existing.jpg',
            'converters' => 'cwebp',
            'fail' => '404',
            'critical-fail' => 'original'
        ];

        ob_start();
        $result = WebPOnDemand::serve(__DIR__);
        $output = ob_get_contents();

        $this->assertEquals($result, WebPConvertAndServe::$ORIGINAL);
    }

    public function testCriticalFailure404()
    {

        $_GET = [
            'source' => 'non-existing.jpg',
            'converters' => 'cwebp',
            'fail' => 'original',
            'critical-fail' => '404'
        ];

        ob_start();
        $result = WebPOnDemand::serve(__DIR__);
        $output = ob_get_contents();

        $this->assertEquals($result, WebPConvertAndServe::$HTTP_404);
    }

    public function testCriticalFailureReportAsImage()
    {

        $_GET = [
            'source' => 'non-existing.jpg',
            'converters' => 'cwebp',
            'fail' => 'original',
            'critical-fail' => 'report-as-image'
        ];

        ob_start();
        $result = WebPOnDemand::serve(__DIR__);
        $output = ob_get_contents();

        $this->assertEquals($result, WebPConvertAndServe::$REPORT_AS_IMAGE);
    }

    public function testCriticalFailureReport()
    {

        $_GET = [
            'source' => 'non-existing.jpg',
            'converters' => 'cwebp',
            'fail' => 'original',
            'critical-fail' => 'report'
        ];

        ob_start();
        $result = WebPOnDemand::serve(__DIR__);
        $output = ob_get_contents();

        $this->assertEquals($result, WebPConvertAndServe::$REPORT);
    }
*/


}
