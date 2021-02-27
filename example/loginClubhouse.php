<?php

// This is an example login with phone number
// use at your own risk!

require_once 'vendor/autoload.php';
use \FadhiilRachman\Clubhouse As Clubhouse;

// replace with your phone number and country code
$phoneNumberOrAuthToken='+6281200000000';

$clubhouse = new Clubhouse\Clubhouse($phoneNumberOrAuthToken);

try {
    
    // send verification code to phone number
    $clubhouse->startPhoneNumberAuth();

    echo '> Input verification code: ';
    $input = fopen("php://stdin","r");
    $verification_code = trim(fgets($input));

    // complete the verification
    $clubhouse->completePhoneNumberAuth($verification_code);

    // get user info
    echo "[PhoneNumber] " . $clubhouse->phone_number . PHP_EOL;
    echo "[AuthToken] " . $clubhouse->auth_token . PHP_EOL;
    echo "[UserId] " . $clubhouse->user_id . PHP_EOL;

    // get channels and print it
    $channels = $clubhouse->getChannels();
    foreach ($channels['channels'] as $channel) {
        echo "[ID] " . $channel['channel_id'] . PHP_EOL;
        echo "[Name] " . $channel['topic'] . PHP_EOL;
        echo "[URL] " . $channel['url'] . PHP_EOL;
        echo "[Speakers] " . $channel['num_speakers'] . PHP_EOL;
        echo "[Speakers] " . $channel['num_speakers'] . PHP_EOL;
        echo PHP_EOL;
    }

} catch(Clubhouse\ClubhouseException $e) {
    // if error, display the message and code
    echo 'Error message: ' . $e->getMessage() . "\n";
    echo 'Error code: ' . $e->getCode();
}
