<?php
require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$case_sqls = array(); 

$tbl_name = 'technician_details';
$records = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$tbl_name} WHERE application_status LIKE '%verified%' AND fw9_taxpayer IS NULL OR f1099_misc IS NULL ORDER BY id ASC");

$msg = [];
if(is_array($records) && count($records)>0){
    foreach($records as $record){
        if(empty($record->fw9_taxpayer)){
            $msg[] = 'form W9';
        }

        if(empty($record->f1099_misc)){
            $msg[] = 'form 1099';
        }

        if(count($msg) > 0){
            $url = add_query_arg( 'action', 'agreement_proofs', esc_url_raw(home_url('technician-contract/')));
            // hit critical notice to upload the mileage records information
            $notice="Please upload mandatory <b>".implode(' , ',$msg)."</b> before saturday. If you n't upload agreement before deadline your account will be locked immediately.Please <a href='".$url."' target='_blank'>Click here</a> to fillup";

            $data=[
                'type'  =>  'taxpayer_irs_agreement_proofs',
                'level' =>  'normal',
                'class' =>  'error',
                'notice'    =>  $notice,
                'date'  =>  date('Y-m-d'),
                'week'  =>  date('Y-\WW'),
                'technician_id'  =>  $record->id
            ];
            
            $wpdb->insert($wpdb->prefix."technician_account_status",$data);
        }
        unset($msg);
    }
}