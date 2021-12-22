<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

$commercial_qutoes = $wpdb->get_results("select * from wp_commercial_quotesheet");

foreach($commercial_qutoes as $quote){

    if(empty($quote->admin_comment)) continue;

    $note_data = [
        'quote_id'      =>  $quote->id,
        'note'          =>  $quote->admin_comment,
        'note_owner'    =>  "Office",
        'created_at'    =>  date('Y-m-d h:i:s', strtotime($quote->date)),
        'updated_at'    =>  date('Y-m-d h:i:s', strtotime($quote->date)),        
    ];

    $wpdb->insert("wp_commercial_quote_notes", $note_data);
}


$residential_qutoes = $wpdb->get_results("select * from wp_quotesheet");

foreach($residential_qutoes as $quote){

    if(empty($quote->admin_comment)) continue;

    $note_data = [
        'quote_id'      =>  $quote->id,
        'note'          =>  $quote->admin_comment,
        'note_owner'    =>  "Office",
        'created_at'    =>  date('Y-m-d h:i:s', strtotime($quote->date)),
        'updated_at'    =>  date('Y-m-d h:i:s', strtotime($quote->date)),
    ];

    $wpdb->insert("wp_residential_quote_notes", $note_data);
}


