<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$case_sqls = $all_ids = array(); 

$tbl_name = 'emails';
$records = $wpdb->get_results("select id,is_valid,email from {$wpdb->prefix}{$tbl_name} WHERE is_valid='no'");

foreach ($records as $record) {
    $email = (new Sendgrid_child)->isValidEmail($record->email) ? 'valid' : 'not valid';
    if($email == 'valid'){
        $all_ids[] = intval($record->id);
        $case_sqls[] = "WHEN ".intval($record->id)." THEN 'yes'";
    }
} 

$case_sql = implode(" ", $case_sqls);

if(count($all_ids) > 0){
    $update_query = "
    UPDATE 
        {$wpdb->prefix}{$tbl_name}
    SET 
        is_valid = (CASE id ".$case_sql." END)
    WHERE 
        id IN(".implode(",", $all_ids).");
    ";
    echo $update_query;
    $result = $wpdb->query($update_query);
    if( FALSE === $result ) {
        echo "Nothing to update!";
    } else {
        echo "Email mark as valid";
    }
}else{
    echo "All emails are invalid";
}