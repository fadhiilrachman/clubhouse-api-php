<?php

// This is an example for get me
// use at your own risk!

require_once 'clubhouse-api/vendor/autoload.php';
use \FadhiilRachman\Clubhouse As Clubhouse;

// replace with your phone number and country code
$phone_number='+6281200000000';

$clubhouse = new Clubhouse\Clubhouse($phone_number);

try {
    
    // send verification code to phone number
    $clubhouse->startPhoneNumberAuth();

    echo '> Input verification code: ';
    $input = fopen("php://stdin","r");
    $verification_code = trim(fgets($input));

    // complete the verification
    $clubhouse->completePhoneNumberAuth($verification_code);

    // get me and print it
    $me = $clubhouse->me();
    print_r($me);

} catch(ClubhouseException $e) {
	// if error, display the message and code
	echo 'Error message: ' . $e->getMessage() . "\n";
	echo 'Error code: ' . $e->getCode();
}
