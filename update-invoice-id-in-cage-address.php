<?php
require_once dirname(dirname(__FILE__)) . "/wp-load.php";

global $wpdb;

$case_sqls = array();

$tbl_name = 'cage_address';
$tbl_inv = 'invoices';

$records = $wpdb->get_results("select ca.*,max(inv.id) as inv_id,inv.client_name,inv.address from {$wpdb->prefix}{$tbl_name} as ca JOIN {$wpdb->prefix}{$tbl_inv} as inv on ca.name = inv.client_name AND ca.address = inv.address AND ca.invoice_id = '' GROUP BY inv.client_name,inv.address order by ca.id desc");

$query = ['tbl' => $tbl_name, 'col' => 'invoice_id'];

foreach ($records as $record) {
    $invoice_id = intval($record->inv_id);
    $all_ids[] = intval($record->id);
    $case_sqls[] = "WHEN " . intval($record->id) . " THEN '{$invoice_id}'";
}
$case_sql = implode(" ", $case_sqls);

$update_query = "
UPDATE 
    {$wpdb->prefix}{$query['tbl']}
SET 
    invoice_id = (CASE id " . $case_sql . " END)
WHERE 
    id IN(" . implode(",", $all_ids) . ");
";
$result = $wpdb->query($update_query);
if (FALSE === $result) {
    echo "Failed!";
} else {
    echo "Invoice id updated!";
}
