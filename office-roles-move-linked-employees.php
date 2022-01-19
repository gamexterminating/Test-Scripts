<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;

$roles = $wpdb->get_results('select * from wp_roles');

foreach($roles as $role){
    if(!empty($role->employee_id)){
        (new Roles)->linkEmployee($role->id, $role->employee_id);
    }
}