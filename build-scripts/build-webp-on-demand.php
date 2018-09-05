<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('PHPMerger.php');
//use PHPMerger;

// Build "webp-on-demand.php" to be used for non-composer
PhpMerger::generate([
    'destination' => '../build/webp-on-demand.inc',

    'jobs' => [
        [
            'root' => './',
            'files' => [
                // put base classes here
                '../src/WebPOnDemand.php',
                //'webp-on-demand-script.inc',
            ],
            'dirs' => [
                // dirs will be required in specified order. There is no recursion, so you need to specify subdirs as well.
                //'.',
            ]
        ]
    ]
]);
