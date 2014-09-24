<?php
namespace app\components;

use PhpAmqpLib\Connection\AMQPConnection;

defined('AMQP_DEBUG') or define('AMQP_DEBUG', FALSE);

class MqClient extends \yii\base\Object
{
    protected $_defaultQueueName;
    protected $_exchanges;
    protected $_queues;
    protected $_connection;
    protected $_connectionConfig = array();
    protected $_channel;
    protected $_qos;
    
    /**
    * @param AMQPConnection $connection
    */
    public function setConnection($connection)
    {
        if (!($connection instanceof \PhpAmqpLib\Connection\AbstractConnection)) {
            $this->_connectionConfig = $connection;
            $connection = $this->createConnection($connection);
        }
        $this->_connection = $connection;
    }
    
    public function getConnection()
    {
        if ($this->_connection === null)
            $this->_connection = $this->createConnection($this->getConnectionConfig());
        return $this->_connection;
    }
    
    public function setConnectionConfig($connectionConfig)
    {
        if (empty($connectionConfig['host']))
            $connectionConfig['host'] = 'localhost';
        if (empty($connectionConfig['port']))
            $connectionConfig['port'] = 5672;
        if (empty($connectionConfig['user']))
            $connectionConfig['user'] = 'guest';
        if (empty($connectionConfig['password']))
            $connectionConfig['password'] = 'guest';
        
        $this->_connectionConfig = $connectionConfig;
    }
    
    public function getConnectionConfig()
    {
        if ($this->_connectionConfig === array()) {
            $this->_connectionConfig = array(
                'host' => 'localhost',
                'port' => 5672,
                'user' => 'guest',
                'password' => 'guest'
            );
        }
        return $this->_connectionConfig;
    }
    
    protected function createConnection($config = array())
    {
        return new AMQPConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );
    }
    
    public function getDefaultQueueName()
    {
        if ($this->_defaultQueueName === null)
            $this->_defaultQueueName = "rentalapp_queue";
        
        return $this->_defaultQueueName;
    }
    
    public function setChannel($channel)
    {
        $this->_channel = $channel;
    }
    
    public function getChannel()
    {
        if ($this->_channel === null)
            $this->_channel = $this->getConnection()->channel();
        return $this->_channel;
    }
    
    public function setQueues($queues)
    {
        if ($queues instanceof QueueCollection)
            $queues->client = $this;
        else
            $queues = $this->createQueueCollection($queues);
        
        $this->_queues = $queues;
    }
    
    public function getQueues()
    {
        if ($this->_queues === null)
            $this->_queues = $this->createQueueCollection();
        
        return $this->_queues;
    }

    protected function createQueueCollection($data = array())
    {
        $collection = new QueueCollection();
        $collection->setClient($this);
        $collection->copyFrom($data);
        
        return $collection;
    }
    
    public function getDefaultQueue()
    {
        return $this->getQueues()->itemAt($this->getDefaultQueueName());
    }
    
    public function setDefaultQueue($queue)
    {
        if (!($queue instanceof Queue))
            $queue = $this->getQueues()->createQueue($queue);
        
        $this->getQueues()->add($this->getDefaultQueueName(), $queue);
    }
    
    public function setExchanges($exchanges)
    {
        if ($exchanges instanceof ExchangeCollection)
            $exchanges->setClient($this);
        else
            $exchanges = $this->createExchangeCollection($exchanges);
        
        $this->_exchanges = $exchanges;
    }
    
    public function getExchanges()
    {
        if ($this->_exchanges === null)
            $this->_exchanges = $this->createExchangeCollection();
        
        return $this->_exchanges;
    }
    
    protected function createExchangeCollection($data = array())
    {
        $collection = new ExchangeCollection();
        $collection->setClient($this);
        //$collection->copyFrom($data);
        
        return $collection;
    }
    
    public function wait()
    {
        $channel = $this->getChannel();
        while(count($channel->callbacks))
            $channel->wait();
    }
}
?>
