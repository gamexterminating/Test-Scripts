<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";
global $wpdb;

$technicians = $wpdb->get_results("select * from wp_technician_details");
echo "<pre>";
echo "total ".count($technicians);
foreach($technicians as $tech){

    $employee_data = [
        'employee_ref_id'       =>  $tech->id,
        'name'                  =>  $tech->first_name." ".$tech->last_name,
        'email'                 =>  $tech->email,
        'address'               =>  $tech->address,
        'role_id'               =>  '1',
        'username'              =>  $tech->slug,
        'password'              =>  $tech->password,
        'application_status'    =>  $tech->application_status,
        'status'                =>  $tech->status
    ];

    $response = (new Employee\Employee)->createEmployee($employee_data);   

    if(!$response){
        print_r($employee_data);
    }
}

$cold_callers = $wpdb->get_results("select * from wp_cold_callers");
echo "<pre>";
echo "total ".count($cold_callers);
foreach ($cold_callers as $cold_caller) {
    $employee_data = [
        'employee_ref_id'       =>  $cold_caller->id,
        'name'                  =>  $cold_caller->name,
        'email'                 =>  $cold_caller->email,
        'address'               =>  $cold_caller->address,
        'phone_no'              =>  $cold_caller->phone_no,
        'role_id'               =>  '2',
        'username'              =>  $cold_caller->username,
        'password'              =>  $cold_caller->password,
        'application_status'    =>  $cold_caller->application_status,
    ];

    if($cold_caller->status == "inactive") $employee_data['status'] = 0;
    if($cold_caller->status == "active") $employee_data['status'] = 1;

    $response = (new Employee\Employee)->createEmployee($employee_data);   

    if(!$response){
        print_r($employee_data);
    }
}