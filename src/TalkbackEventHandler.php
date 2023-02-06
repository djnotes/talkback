<?php

namespace Talkback;

use danog\MadelineProto\EventHandler;

/**
 * The main event handling class for the talkback bot
 */
class TalkbackEventHandler extends EventHandler
{


  private $admin = self::DEFAULT_ADMIN_ID;

  private const DEFAULT_ADMIN_ID = 'the_bots_master';

  static array $WORD_BANK = [
    "Hello" => "Hello",
    "Hi" => "Hello",
    "Hallo" => "Hello",
    "How are you?" => "Thank you. I'm fine!",
    "How old are you?" => "I don't know really. It depends on when I started running on this computer :)",
    "Are you male or female?" => "I am just a program",
    "Will you be my friend?" => "I am your friend!",
    "What can you do?" => "I can talk to you",
    "What is your name?" => "My name is Talkback.",
    "How many languages do you know?" => "I only know English and my knowledge is limited!",
    "What's up?" => "Nothing, just a little bit bored!",
    "What are you doing now?" => "Hmm, playing with my bugs",
    "You are so stupid" => "I wish I could take that as a compliment",
    //TODO: Add more sentences or make me more intellient by integrating language models
  ];

  public function onStart()
  {
    // yield $this->logger("Inside onStart");
    // $this->admin = getenv('ADMIN_ID') ?? self::DEFAULT_ADMIN_ID;
    // yield $this->logger("ADMIN ID set to {$this->admin}");
    $info = yield $this->getSelf();
    yield $this->messages->sendMessage(
      peer: $this->admin,
      message: "{$info['first_name']} started"
    );
  }

  public function onUpdateNewMessage(array $update): \Generator
  {

    if ($update['message']['message'] == '/start') {
      yield $this->messages->sendMessage(peer: $update['message']['from_id'], message: "Welcome to the bot! \nI am here to talk to you!\nAsk me something!");
      yield;
    }
    if ($update['message']['out'] == false) {



      yield $this->logger("New message: " . print_r($update['message'], true));

      $message = $update['message']['message'];


      $answer = self::$WORD_BANK[$message] ?? "Sorry I didn't get you";

      $peer = $update['message']['from_id'];

      yield $this->messages->sendMessage(['peer' => $peer, 'message' => $answer]);
    }

  }

  /**
   * Summary of getReportPeer
   * @return bool|string
   */
  public function getReportPeers() {
    return $this->admin;
  }
}