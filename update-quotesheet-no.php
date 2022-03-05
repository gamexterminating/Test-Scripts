<?php
require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$case_sqls = array(); 

$tbl_name = 'commercial_quotesheet';
$records = $wpdb->get_results("select id,quote_no from {$wpdb->prefix}{$tbl_name}");

$query = ['tbl' => $tbl_name, 'col' => 'quote_no']; 

foreach ($records as $record) {
    if(!empty($record->quote_no)) continue;
    $rand_no = (new GamFunctions)->generateGamUniqueNumber($query); 
    $all_ids[] = intval($record->id);
    $case_sqls[] = "WHEN ".intval($record->id)." THEN '{$rand_no}'";
} 
$case_sql = implode(" ", $case_sqls);

$update_query = "
UPDATE 
    {$wpdb->prefix}{$query['tbl']}
SET 
    quote_no = (CASE id ".$case_sql." END)
WHERE 
    id IN(".implode(",", $all_ids).");
";
$result = $wpdb->query($update_query);
if( FALSE === $result ) {
    echo "Failed!";
} else {
    echo "Quote no updated!";
}