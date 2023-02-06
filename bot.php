<?php

/**
 * This sample file is intended to show you how to receive and use environment variables passed inside the container
 * in the script. By passing the env-file parameter to Podman or Docker CLI, you can make bot logins non-interactive by using 
 */

include __DIR__ . '/vendor/autoload.php';

use \danog\MadelineProto\Settings\Logger as LoggerConfig;
use \danog\MadelineProto\Logger;
use \danog\MadelineProto\API;

use Talkback\TalkbackEventHandler;

//Check for log and session directories
//
if ( ! file_exists(__DIR__ . '/session')) {
	echo "Creating session directory \n";
	mkdir(__DIR__ . '/session');
}

if ( ! file_exists(__DIR__ . '/log')) {
	echo "Creating log directory \n";
	mkdir(__DIR__ . '/log');
}

$settings = new \danog\MadelineProto\Settings;


$apiId = getenv('API_ID');
$apiHash = getenv('API_HASH'); 
$botToken = getenv('BOT_TOKEN');
$adminId = getenv('ADMIN_ID');

if( !$apiId or !$apiHash or !$botToken or !$adminId){
	echo "Please provide API_ID and API_HASH and BOT_TOKEN and ADMIN_ID environment variables \n";
	exit(1);
}

$settings->setAppInfo(
	(new \danog\MadelineProto\Settings\AppInfo())
		->setApiId($apiId)
		-> setApiHash($apiHash)
		
);

//Set connection timeout
$settings->getConnection()->setTimeout(20.0);

$settings->setLogger(
	(new LoggerConfig)
		->setType(Logger::ECHO_LOGGER)
);



$api = new API(__DIR__ . '/session/talkback.session', $settings);

$api->botLogin($botToken);


TalkbackEventHandler::startAndLoop(__DIR__ . '/session/talkback.session', $settings);


