<?php

// This is an example for get me
// use at your own risk!

require_once 'clubhouse-api/vendor/autoload.php';
use \FadhiilRachman\Clubhouse As Clubhouse;

// replace with your auth token
$phoneNumberOrAuthToken='REPLACE_WITH_YOUR_TOKEN';

$clubhouse = new Clubhouse\Clubhouse($phoneNumberOrAuthToken);

try {

    // get channel list and print it
    $me = $clubhouse->getChannels();
    print_r($me);

} catch(ClubhouseException $e) {
    // if error, display the message and code
    echo 'Error message: ' . $e->getMessage() . "\n";
    echo 'Error code: ' . $e->getCode();
}
