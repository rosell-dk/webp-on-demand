<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('PHPMerger.php');
//use PHPMerger;

// We could also remove the comments:
// https://stackoverflow.com/questions/503871/best-way-to-automatically-remove-comments-from-php-code


// Build "webp-convert-and-serve.inc"
PhpMerger::generate([
    'destination' => '../build/webp-convert-and-serve.inc',

    'jobs' => [
        [
            'root' => '../vendor/rosell-dk/webp-convert/src/',

            'files' => [
                // put base classes here
                'Exceptions/WebPConvertBaseException.php',
                'Loggers/BaseLogger.php'
            ],
            'dirs' => [
                // dirs will be required in specified order. There is no recursion, so you need to specify subdirs as well.
                //'.',
                '.',
                'Converters',
                'Exceptions',
                'Converters/Exceptions',
                'Loggers',
            ]
        ],
        [
            'root' => '../vendor/rosell-dk/webp-convert-and-serve/src/',
            'files' => [
            ],
            'dirs' => [
                '.',
            ]
        ]
    ]
]);
