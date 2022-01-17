<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$records = $wpdb->get_results("select * from wp_animal_cage_tracking_new");

foreach ($records as $key => $record) {

    // if empty address continue
    if(empty($record->address)) continue;

    // get the address id
    $address_id = $wpdb->get_var("select id from wp_cage_address where address = '$record->address'");
    if(!$address_id){
        echo "no addres_id found for address = $record->address";
        continue;
    }

    // first check if record already exist
    $count = $wpdb->get_var("select count(*) from wp_cage_data where address_id = '$address_id'");
    if($count) continue;
    
    // get the very last record quantity
    $last_record = $wpdb->get_row("select * from wp_animal_cage_tracking_new where address = '$record->address' order by created_at desc limit 1");

    $cage_data = [
        'address_id'    =>  $address_id,
        'created_at'    =>  $last_record->created_at,
        'updated_at'    =>  $last_record->created_at,
        'notes'         =>  '*No Note for old cage data',
        'technician_id' =>  $last_record->technician_id,
    ];

    if($last_record->cage_type_id == 1){
        $cage_data['racoon_cages'] = $last_record->quantity - $last_record->quantity_retrieved;
        $cage_data['squirrel_cages'] = 0;
    }
    else{
        $cage_data['racoon_cages'] = 0;
        $cage_data['squirrel_cages'] = $last_record->quantity - $last_record->quantity_retrieved;
    }

    $wpdb->insert("wp_cage_data", $cage_data);
}