<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";

global $wpdb;
$technicians = (new Technician_details)->get_all_technicians();
foreach($technicians as $technician){
    if(empty($technician->pesticide_license) || empty($technician->pesticide_license_no)){
        (new Technician_details)->requestForPesticideLiceneseDetails($technician->id);
    }
}