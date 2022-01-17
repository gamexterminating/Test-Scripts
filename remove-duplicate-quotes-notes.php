<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;
$quote_ids = $wpdb->get_results("select quote_id from wp_residential_quote_notes group by quote_id having count(*) = 2");

foreach($quote_ids as $quote_id){
    $notes = $wpdb->get_results("select * from wp_residential_quote_notes where quote_id = '$quote_id' ");

    if($notes[0]->note == $notes[1]->note && $notes[0]->created_at == $notes->[1]->)

}