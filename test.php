<?php
require 'vendor/autoload.php';

$LINEQ = new \App\LINEQ();

var_dump($LINEQ->fetchQ($argv[1]));