<?php

require_once dirname(dirname(dirname(__FILE__)))."/wp-load.php";

$vehicles = (new CarCenter)->getAllVehicles();

foreach($vehicles as $vehicle){
    if(
        empty($vehicle->registration_document) || 
        empty($vehicle->insurance_document) ||
        empty($vehicle->registration_expiry_date) ||
        empty($vehicle->insurance_expiry_date)
    ){
        if($vehicle->owner == "company"){
            $vehicle_name = (new CarCenter)->getName($vehicle->id);
            $message = "
                <p>Vehicle $vehicle_name have registation or insurance data pending to be updated in system.</p>
                <p>Please check and update the required data accordingly</p>
            ";
            (new OfficeTasks)->updateVehicleInformation($message);
        }

        $technician = (new Technician_details)->getTechnicianByVehicleId($vehicle->id, ['id']);
        if(!$technician) continue;

        $notice = "Your vehicle $vehicle_name have insurance or registartion data pending to be uploaded to the system. Please update this as soon as possible under vehicle -> Edit vehicle informatinon section.";
        $data = [
            'type'          =>  'vehicle_information',
            'level'         =>  'normal',
            'notice'        =>  $notice,
            'technician_id' =>  $technician->id
        ];

        (new Notices)->generateTechnicianNotice($data);
    }
}