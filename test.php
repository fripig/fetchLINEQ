<?php
require 'vendor/autoload.php';
$gcs = new \App\GCSFile();

$LINEQ = new \App\LINEQ($gcs);

var_dump($LINEQ->fetchQ($argv[1]));