<?php
require_once dirname(dirname(__FILE__)) . "/wp-load.php";

global $wpdb;

$tbl_name = 'reservice_clients';
$records = $wpdb->get_results(
"
select I.id as invoice_id, I.client_name, I.address, I.email , RC.*
from {$wpdb->prefix}invoices I
join {$wpdb->prefix}reservice_clients RC
on I.reservice_id = RC.id
where I.email !='' AND I.reservice_id IS NOT NULL
ORDER BY I.id DESC"
);

if (count($records) > 0) {
    $rearrange_records = [];
    foreach ($records as $k => $record) {
        if (
            !empty($record->revisit_frequency_unit)
            && !empty($record->revisit_frequency_timeperiod)
        ) {
            $rearrange_records[] = (array) $record;
            $rearrange_records[$k]['next_reservice_date'] = date('Y-m-d', strtotime('-2 days', strtotime(date('Y-m-d', strtotime($record->created_at . '+' . $record->revisit_frequency_unit . ' ' . $record->revisit_frequency_timeperiod . '')))));
        }
    }
    pdie($rearrange_records);
}
