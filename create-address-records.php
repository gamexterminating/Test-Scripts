<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$records = $wpdb->get_results("select * from wp_animal_cage_tracking_new");

foreach($records as $record){

    if(empty($record->address)) continue;
    if((new AnimalCageTracker)->isAddressExist($record->address)) continue;
    
    $data = [
        'name'  =>  $record->client_name,
        'address'   =>   $record->address,
        'retrieved' =>  $record->retrieved,
        'pickup_date'   =>   $record->pickup_date,
        'branch_id' =>  $record->branch_id
    ];

    $wpdb->insert("wp_cage_address", $data);
}