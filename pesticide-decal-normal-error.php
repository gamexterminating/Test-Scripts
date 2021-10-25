<?php

// convert critical errors to normal errors for pesticide decal proof

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$where_data = [
    'level' =>  'critical',
    'type'  =>  'requestForPesticideDecalProof'
];
$wpdb->update("wp_technician_account_status", ['level' => 'normal'], $where_data);