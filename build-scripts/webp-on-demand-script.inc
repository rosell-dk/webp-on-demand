<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use \WebPOnDemand\WebPOnDemand;

$source = $_GET['source'];

require 'webp-on-demand-options.inc';

$options['require-for-conversion'] = 'webp-convert-and-serve.inc';
WebPOnDemand::serve($source, $destination, $options);
