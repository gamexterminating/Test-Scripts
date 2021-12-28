<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$roles = $wpdb->get_results("select * from wp_roles");

foreach($roles as $role){
    $employee_id = $wpdb->get_var("select employee_id from wp_employee_role where role_id = '$role->id'");
    if(!$employee_id) continue;

    $update_data  = ['employee_id' => $employee_id];
    $response = $wpdb->update("wp_roles", $update_data, ['id' => $role->id]);
    if(!$response) pdie('unable to update');
}