<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(dirname(__FILE__)) . "/wp-load.php";
echo (new Callrail_new)->getCallrailIdByPhoneNo('626-523-2188');