<?php

$technicians = (new Technician_details)->get_all_technicians();
echo "<pre>";
echo "total ".count($technicians);
foreach($technicians as $tech){

    $employee_data = [
        'employee_ref_id'   =>  $tech->id,
        'name'              =>  $tech->first_name." ".$tech->last_name,
        'email'             =>  $tech->email,
        'address'           =>  $tech->address,
        'role_id'           =>  '1',
        'username'          =>  $tech->slug,
        'password'          =>  $tech->password
        ];

    $response = (new Employee\Employee)->createEmployee($employee_data);   

    if(!$response){
        print_r($employee_data);
    }
}

$cold_callers = (new ColdCaller)->getAllColdCallers();
echo "<pre>";
echo "total ".count($cold_callers);
foreach ($cold_callers as $cold_caller) {
    $employee_data = [
        'employee_ref_id'   =>  $cold_caller->id,
        'name'              =>  $cold_caller->name,
        'email'             =>  $cold_caller->email,
        'address'           =>  $cold_caller->address,
        'phone_no'          =>  $cold_caller->phone_no,
        'role_id'           =>  '2',
        'username'          =>  $cold_caller->username,
        'password'          =>  $cold_caller->password
    ];

    $response = (new Employee\Employee)->createEmployee($employee_data);   

    if(!$response){
        print_r($employee_data);
    }
}


$office_staff = (new OfficeStaff)->getStaffMembers();
echo "<pre>";
echo "total ".count($office_staff);
foreach ($office_staff as $staff_member) {
    
    $employee_data = [
        'employee_ref_id'   =>  $staff_member->id,
        'name'              =>  $staff_member->name,
        'email'             =>  $staff_member->email,
        'address'           =>  $staff_member->address,
        'role_id'           =>  '3',
        'username'          =>  $staff_member->username,
        'password'          =>  $staff_member->password
    ];

    $response = (new Employee\Employee)->createEmployee($employee_data);   

$door_to_door_employees = (new Employee\DoorToDoorSales)->get();
echo "<pre>";
echo "total ".count($door_to_door_employees);
foreach ($door_to_door_employees as $door_to_door) {
    
    $employee_data = [
        'employee_ref_id'   =>  $door_to_door->id,
        'name'              =>  $door_to_door->name,
        'email'             =>  $door_to_door->email,
        'address'           =>  $door_to_door->address,
        'role_id'           =>  '3',
        'username'          =>  $door_to_door->username,
        'password'          =>  $door_to_door->password
    ];

    $response = (new Employee\Employee)->createEmployee($employee_data);   

    if(!$response){
        print_r($employee_data);
    }
}
