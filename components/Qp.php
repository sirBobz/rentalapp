<?php
namespace app\components;

use Yii;
use yii\base\Component;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Qp extends Component {
    private $_connect = null;
    private $_channel = null;
    private $callback;
    private $client;

    /*public function init() {
        parent::init();
        
        //Yii::setPathOfAlias('PhpAmqpLib', Yii::getPathOfAlias('application.components.AMQP.PhpAmqpLib'));
        $this->_connect = new AMQPConnection($this->host, $this->port, $this->login, $this->password);
        $this->_channel = $this->_connect->channel();
    }*/
    public function createConnection()
    {
        //\Yii::warning('sample log', 'application');
        //Yii::$app->log('[' .get_class(). '] Creating connection', 'info');
        
        $this->_connect = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $this->_channel = $this->_connect->channel();
        
        return $this->_channel;
    }
    
    /* name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
    */

    public function declareExchange($name, $type = 'direct', $passive = false, $durable = true, $auto_delete = false) {

        return $this->_channel->exchange_declare($name, $type, $passive, $durable, $auto_delete);
    }
    
    /*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
    */

    public function declareQueue($name, $passive = false, $durable = true, $exclusive = false, $auto_delete = false) {
        return $this->_channel->queue_declare($name, $passive, $durable, $exclusive, $auto_delete);
    }
    
    public function bindQueueExchange($queueName, $exchangeName, $routingKey = '') {
        $this->_channel->queue_bind($queueName, $exchangeName, $routingKey);
    }
    
    public function publish_message($message, $exchangeName, $routingKey = '', $content_type = 'text/json', $app_id = '') {
        $toSend = new AMQPMessage($message, array(
            'content_type' => $content_type,
            'content_encoding' => 'utf-8',
            'app_id' => $app_id,
            'delivery_mode' => 2));
        $this->_channel->basic_publish($toSend, $exchangeName, $routingKey);

        //$msg = $this->_channel->basic_get('q1');
        //var_dump($msg);
    }
    
    /**
    * This method asks the server to start a "consumer", which is a transient request for messages from a specific queue. 
         * Consumers last as long as the channel they were declared on, or until the client cancels them.
    *
    * @param string $queue Specifies the name of the queue to consume from.
    * @param string $consumerTag Specifies the identifier for the consumer. The consumer tag is local to a channel, 
    so two clients can use the same consumer tags. If this field is empty the server will generate a unique tag.
    * @param bool $noLocal Don't receive messages published by this consumer.
    * @param bool $noAck Tells the server if the consumer will acknowledge the messages.
    * @param bool $exclusive Request exclusive consumer access, meaning only this consumer can access the queue.
    * @param bool $nowait don't wait for a server response. In case of error the server will raise a channel exception
    */
    public function consume($queue = NULL, $consumerTag = '', $noLocal = false, $noAck = false, 
            $exclusive = false, $nowait = false) {

        if (!$queue)
            $queue = $this->queue;
        
        /*
        queue: Queue from where to get the messages
        consumer_tag: Consumer identifier
        no_local: Don't receive messages published by this consumer.
        no_ack: Tells the server if the consumer will acknowledge the messages.
        exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
        nowait: don't wait for a server response. In case of error the server will raise a channel
        exception
        callback: A PHP Callback
        */
        $this->channel->basic_consume($queue, $consumerTag, $noLocal, $noAck, $exclusive, $nowait, $this->callback);
    }
    
    /**
    * Wait for some expected AMQP methods and dispatch to them.
    *
    * */
    public function wait() {
        while (count($this->_channel->callbacks)) {
            $this->_channel->wait();
        }
    }
    
    /**
    * Register a call back function that is called when a message is received
    *
    * @param function
    *
    * */
    public function registerCallback($callback) {
        if (is_callable($callback)) {
            \Yii::log('[' . get_class() . '] Registering worker callback', 'info');
            $this->callback = $callback;
        }
    }
    
    public function closeConnection() {
        $this->_channel->close();
        $this->_connect->close();
    }
}
//busy signal leaving
?>
