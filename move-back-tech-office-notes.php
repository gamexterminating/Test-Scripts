<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$notes = $wpdb->get_results("select * from wp_residential_quote_notes");

foreach($notes as $note){
    if($note->note_owner == "Office"){
        $wpdb->update("wp_quotesheet", ['office_notes' => $note->note], ['id' => $note->quote_id]);
    }
    else{
        $wpdb->update("wp_quotesheet", ['tech_notes_for_office' => $note->note], ['id' => $note->quote_id]);
    }
}

$notes = $wpdb->get_results("select * from wp_commercial_quote_notes");

foreach($notes as $note){
    if($note->note_owner == "Office"){
        $wpdb->update("wp_commercial_quotesheet", ['office_notes' => $note->note], ['id' => $note->quote_id]);
    }
    else{
        $wpdb->update("wp_commercial_quotesheet", ['tech_notes_for_office' => $note->note], ['id' => $note->quote_id]);
    }
}
