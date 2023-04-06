<?php

namespace Talkback;

use danog\MadelineProto\EventHandler;
use Amp\Sql\Pool;
use Amp\Mysql\MysqlConfig;
use Amp\Mysql\MysqlConnectionPool;
use danog\MadelineProto\Exception;

/**
 * The main event handling class for the talkback bot
 */
class TalkbackEventHandler extends EventHandler
{


  private $admin = self::DEFAULT_ADMIN_ID;

  private const DEFAULT_ADMIN_ID = 'putyouridhere';

  private const NO_ANSWER_MESSAGE = "No answer";

  // private Pool $pool;

  private string $dbUser;
  private string $dbPassword;
  private string $dbName;
  private string $dbHost;


  protected function getSqlConfig() : MysqlConfig{    
    $this->dbName = getenv('MARIADB_DATABASE');
    $this->dbHost = getenv('MARIADB_HOST');
    $this->dbPassword = getenv('MARIADB_PASSWORD');
    $this->dbUser = getenv('MARIADB_USER');

    if(!$this->dbName || 
        !$this->dbHost || 
        !$this->dbUser || 
        !$this->dbPassword
    ) {
      throw new \RuntimeException("Database environment variables not provided. Make sure you provide the variables in bot.env");
    }

    return MysqlConfig::fromString("host={$this->dbHost} db={$this->dbName} user={$this->dbUser} password={$this->dbPassword}");
    // $config = MysqlConfig::fromString("host=db db=talkback user=talkback_user password=17:30Apr5");
    // yield $this->logger("Db pool created: " . print_r($this->pool, true));
  }


  public function onStart()
  {
    $pool = new MysqlConnectionPool($this->getSqlConfig());
    // yield $this->logger("Inside onStart");
    // $this->admin = getenv('ADMIN_ID') ?? self::DEFAULT_ADMIN_ID;
    // yield $this->logger("ADMIN ID set to {$this->admin}");
    $info = yield $this->getSelf();
    $this->logToDb($info['id'], event : 'Bot started', incoming : false);

    try{
      yield $this->messages->sendMessage(
        peer: $this->admin,
        message: $info['first_name'] . " started"
      );
    } catch(Exception $e){
      yield $this->logger("Error sending message : {$e->getMessage()}");
    }
  }

  public function onUpdateNewMessage(array $update): \Generator
  {

    $pool = new MysqlConnectionPool($this->getSqlConfig());

    if ($update['message']['message'] == '/start') {
      yield $this->messages->sendMessage(peer: $update['message']['from_id'], message: "Welcome to the bot! \nI am here to talk to you!\nAsk me something!");
      yield;
    }
    if ($update['message']['out'] == true) return;

    yield $this->logger("New message: " . print_r($update['message'], true));

    $message = $update['message']['message'];
  

    yield $this->logToDb(userId: $update['message']['peer_id']['user_id'], incoming : true, event: \json_encode($update));


    $query = "SELECT * FROM qa WHERE  question = :question";
    $statement = yield $pool->prepare($query);
    yield $this->logger("Query: " . $query);

    $result = yield $statement->execute(['question' => $message]);
    yield $this->logger("Searching question: " . $message);

    $answer = yield $result->fetchRow()['answer'] ?? self::NO_ANSWER_MESSAGE;

    $peer = $update['message']['from_id'];

    try {
      yield $this->messages->sendMessage(peer: $peer, message: $answer);
    } catch(\Exception $e){
      yield $this->logger("Failed to send message : {$e->getMessage()} \n {$e->getTraceAsString()}");
    }
        


  }

  /**
   * Summary of getReportPeer
   * @return bool|string
   */
  public function getReportPeers() {
    return $this->admin;
  }


  /**
   * Logs events to database, like when the bot receives a message or sends a message
   */
  public function logToDb(
    int $userId,
    string $event,
    bool $incoming
  ){  

    $pool = new MysqlConnectionPool($this->getSqlConfig());
    $statement = yield $pool->prepare("INSERT INTO analytics (`user_id`, `incoming`, `event`) VALUES (:user_id, :incoming, :event)");
    try{
    $result = yield $statement->execute(
      [
        'user_id' => $userId,
        'incoming' => $incoming,
        'event' => $event,
      ]
    );
  } catch(\Exception $e){
      yield $this->logger("Failed to execute db query : {$e->getMessage()} \n {$e->getTraceAsString()}");
  }

    return $result;
  }
}