<?php
require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$case_sqls = array(); 

$tbl_name = 'technician_details';
$records = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$tbl_name} WHERE id IN(161,74,131,164,64,177) ORDER BY id ASC");

if(is_array($records) && count($records)>0){
    foreach($records as $record){
        $url = add_query_arg('view', 'salary-agreement', esc_url_raw(home_url('technician-dashboard/')));
        // hit critical notice to upload the mileage records information
        $notice="Your account is locked please review <b>form 1099</b> salary agreement contract immediately to unlock your account & <a href='".$url."' target='_blank'>Click here</a> to fillup contract details.";
        $data=[
            'type'  =>  'contract_1099_salary_agreement',
            'level' =>  'critical',
            'class' =>  'error',
            'notice' =>  $notice,
            'date'  =>  date('Y-m-d'),
            'week'  =>  date('Y-\WW'),
            'technician_id'  =>  $record->id
        ];
        $wpdb->insert($wpdb->prefix."technician_account_status",$data);
    }
}