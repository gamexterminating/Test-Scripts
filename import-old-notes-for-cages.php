<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$notes = $wpdb->get_results("selct * from wp_animal_cage_notes order by created_at asc");

$not_found_records = [];

foreach($notes as $note){

    // get the address for note
    $old_address = $wpdb->get_var("select address from wp_animal_cage_tracking_new where id = '$note->cage_id'");
    if(!$old_address){
        $not_found_records[] = $note;
        continue;
    }

    // get the new address id
    $address_id = $wpdb->get_var("select id from wp_cage_address where address = '$old_address'");
    if(!$address_id){
        $not_found_records[] = $note;
        continue;
    }

    // get the oldest cage record for that address 
    $oldest_record = $wpdb->get_row("select * form wp_cage_data where address_id = '$address_id' order by created_at desc");
    if(!$oldest_record){
        $not_found_records[] = $note;
        continue;
    }

    // if that record has note = *No Note for old cage data then udpate to old note
    if(trim($oldest_record->note) == "*No Note for old cage data"){
        $update_data = ['notes'  =>  $note->notes];
        $wpdb->update('wp_cage_data', $update_data, ['id' => $oldest_record->id]);
    }
}

echo "<pre>";
print_r($not_found_records);die;