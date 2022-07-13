<?php


include __DIR__ . '/vendor/autoload.php';


use \danog\MadelineProto\EventHandler;
use \danog\MadelineProto\Settings\Logger as LoggerConfig;
use \danog\MadelineProto\Logger;
use \danog\MadelineProto\API;




class BotTalk extends EventHandler{
	static array $WORD_BANK = [
		"Hello" => "Hello",
		"Hi" => "Hello",
		"Hallo" => "Hello",
		"How are you?" => "Thank you. I'm fine!",
		"How old are you?" => "I don't know really. It depends on when I started running on this computer :)",
		"Are you male or female?" => "I am just a program",
		"Will you be my friend?" => "I am your friend!",
		"What can you do?" => "I can talk to you",
		
		
	];

	public function onUpdateNewMessage(array $update): \Generator 
	{



		if ($update['message']['message'] == '/start') {
			yield $this->messages->sendMessage(['peer' => $update['message']['from_id'], 'message' => "Welcome to the bot! \nI am here to talk to you!\nAsk me something!"]);
			return;
		}
		if ($update['message']['out'] == false){ 

		

		yield $this->logger("New message: " . print_r($update['message'], true));

		$message = $update['message']['message'];

		
		$answer = self::$WORD_BANK[$message] ?? "Sorry I didn't get you";

		$peer = $update['message']['from_id'];

		yield $this->messages->sendMessage(['peer' => $peer, 'message' => $answer]);
		}

	}
}


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

if( !$apiId or !$apiHash or !$botToken){
	echo "Please provide API_ID and API_HASH and BOT_TOKEN environment variables \n";
	exit(1);
}

$settings->setAppInfo(
	(new \danog\MadelineProto\Settings\AppInfo())
		->setApiId($apiId)
		-> setApiHash($apiHash)
);

$settings->setLogger(
	(new LoggerConfig)
		->setType(Logger::ECHO_LOGGER)
	//	-> setExtra(__DIR__ . '/log/bot.log')
	//	->setMaxSize(100 * 1024 * 1024)

);



$api = new API(__DIR__ . '/session/talkback.session', $settings);
if ( ! $api->getSelf() ){
	$api->botLogin($botToken); //Log in non-interactively
}

$api->init();
$api->start();
$api->stop();

BotTalk::startAndLoop(__DIR__ . '/session/talkback.session', $settings);

