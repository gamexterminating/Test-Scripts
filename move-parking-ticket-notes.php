<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$parking_tickets = $wpdb->get_results("select * from wp_parking_tickets");

foreach($parking_tickets as $parking_ticket){
    $data = [
        'parking_ticket_id' =>  $parking_ticket->id,
        'note'              =>  $parking_ticket->description,
    ];

    $wpdb->insert("wp_parking_ticket_notes", $data);
}