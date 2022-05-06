<?php

require_once dirname(dirname(__FILE__)) . "/wp-load.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

// For more information about installing and using the Bing Ads PHP SDK, 
// see https://go.microsoft.com/fwlink/?linkid=838593.

\GamFunctions::loadVendor();

use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = 'ACc37564f275bb841d981fbc9cd5568a03';
$token = '5038440f5755bab0bcf705c6cfe54d97';
$twilio = new Client($sid, $token);

$phone_no = '+919878907047';
$from_no = '+17738230870';
$message = 'Hello world';
try {
    $execution = $twilio->studio->v2->flows("FWff53c6b18a5200c5433a1ccd4157b7a1")
        ->executions
        ->create(
            $phone_no, // to
            $from_no, // from
            [
                "parameters" => [
                    "foo" => "bar",
                    "address_id" => 3214567
                ]
            ]
        );
    /*$execution = $twilio->studio->v2->flows("FWff53c6b18a5200c5433a1ccd4157b7a1")
                 ->executions
                ->create("+919459102765", "+17738230870");*/

    /*$execution = $twilio->messages->create(
        // the number you'd like to send the message to
        $phone_no,
        [
            // A Twilio phone number you purchased at twilio.com/console
            'from' => '+17738230870',
            // the body of the text message you'd like to send
            'body' => $message
        ]
    );*/
    pdie($execution);
} catch (Exception $e) {
    echo $e->getCode() . ' : ' . $e->getMessage() . "<br>";
    return false;
}
