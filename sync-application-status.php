<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";
global $wpdb;

$technicians = $wpdb->get_results("select * from wp_technician_details");


foreach($technicians as $technician){
    $data = [
        'application_status'    =>  $technician->application_status,
        'status'                =>  $technician->status
    ];

    $where_data = [
        'employee_ref_id'   =>  $technician->id,
        'role_id'           =>  1
    ];

    $wpdb->update("wp_employees", $data, $where_data);
}

$cold_callers = $wpdb->get_results("select * from wp_cold_callers");

foreach ($cold_callers as $cold_caller) {
    $data = [
        'application_status'    =>  $cold_caller->application_status
    ];

    if($cold_caller->status == "inactive") $data['status'] = 0;
    if($cold_caller->status == "active") $data['status'] = 1;

    $where_data = [
        'employee_ref_id'   =>  $technician->id,
        'role_id'           =>  2
    ];

    $wpdb->update("wp_employees", $data, $where_data);

}