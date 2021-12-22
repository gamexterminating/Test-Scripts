<?php

/*
    This script loop on all linked vehicles and check if any vehicle mileage information is not present
    if it found any , it generates a critical leven notice to fill out the mileage information form
*/
require_once dirname(dirname(dirname(__FILE__)))."/wp-load.php";

$vehicles = (new CarCenter)->getVehicles();

if(is_array($vehicles) && count($vehicles)>0){
    foreach($vehicles as $vehicle){
        if(empty($vehicle->last_break_change_mileage) || empty($vehicle->last_oil_change_mileage) || empty($vehicle->current_mileage)){
                // hit critical notice to upload the mileage records information
                $notice="All technicians are required to fill the vehicle mileage information form. Please fill out the mileage information form in order to unlock your account.";
    
                $data=[
                    'type'  =>  'mileage_information_form',
                    'level' =>  'critical',
                    'class' =>  'error',
                    'notice'    =>  $notice,
                    'date'  =>  date('Y-m-d'),
                    'week'  =>  date('Y-\WW'),
                    'technician_id'  =>  $vehicle->technician_id
                ];
                
                $wpdb->insert($wpdb->prefix."technician_account_status",$data);
        }
    }
}