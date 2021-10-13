<?php

require_once dirname(dirname(__FILE__))."/wp-load.php";
$week = '2021-W40';
(new EmployePayment)->generatePaymentRecords($week);