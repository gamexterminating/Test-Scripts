<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$wpdb->delete("wp_payments", ['week' => '2021-W40']);