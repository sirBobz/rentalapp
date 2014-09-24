<?php
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

namespace app\services;

class EventDispatcher
{
    private static $conn;
    private static $ch;


    static function connect()
    {
        $data = \Yii::$app->params;
        
        self::$conn = new \PhpAmqpLib\Connection\AMQPConnection($data['rabbitmqHost'], $data['rabbitmqPort'], 
                $data['rabbitmqUser'], $data['rabbitmqPassword']);
        self::$ch = self::$conn->channel();
        
        self::$ch->queue_declare($data['rabbitmqQueue'], false, true, false, false);
        self::$ch->exchange_declare($data['rabbitmqExchange'], 'direct', false, true, false);
        self::$ch->queue_bind($data['rabbitmqQueue'], $data['rabbitmqExchange']);
        
    }

    public static function dispatch($message)
    {
        $data = \Yii::$app->params;
        
        self::connect();
        $msg = new \PhpAmqpLib\Message\AMQPMessage($message, array('delivery_mode' => 2));
        self::$ch->basic_publish($msg, $data['rabbitmqExchange']);
        
        self::closeConnection();
    }
    
    function process_message($msg)
    {
        print_r($msg->body);
        die();
    }

    public static function consume()
    {
        self::connect();
        self::$ch->basic_consume(\Yii::$app->params['rabbitmqQueue'], '', false, true, false, false, 
                function($msg)
        {
            print_r($msg->body);
        });
        //self::closeConnection();
        
        while(count(self::$ch->callbacks))
        {
            self::$ch->wait();
        }
    }

    static function closeConnection()
    {
        self::$ch->close();
        self::$conn->close();
    }
}
?>
