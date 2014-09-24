<?php
namespace app\components;

class Exchange extends \CComponent
{
    public $name;
    public $type = 'direct';
    public $isPassive = false;
    public $isDurable = true;
    public $autoDelete = false;
    public $routingKey = null;
    protected $_client;
    protected $_queue;
    protected $_isInitialized = false;
    
    public function setClient($client)
    {
        $this->_client = $client;
    }
    
    public function getClient()
    {
        if ($this->_client === null) {
            $app = \Yii::app();
            if (!$app-> hasComponent('amqp'))
                throw new \CException(__CLASS__." expects a 'mq' application component!");
            $this->_client = $app->getComponent('amqp');
        }
        return $this->_client;
    }
    
    public function setIsInitialized($isInitialized)
    {
        $this->_isInitialized = $isInitialized;
    }
    
    public function getIsInitialized()
    {
        return $this->_isInitialized;
    }
    
    public function setQueue($queue)
    {
        if (is_string($queue))
            $queue = $this->getClient()->getQueues()->itemAt($queue);
        $this->_queue = $queue;
    }
    
    public function getQueue()
    {
        if ($this->_queue === null)
            $this->_queue = $this->getClient()->getDefaultQueue();
        return $this->_queue;
    }
    
    public function init()
    {
        if ($this->getIsInitialized())
            return;
        
        $client = $this->getClient();
        $client->getChannel()->exchange_declare(
            $this->name,
            $this->type,
            $this->isPassive,
            $this->isDurable,
            $this->autoDelete
        );
        
        if ($this->routingKey === null)
            $this->routingKey = $this->name;
         
        $this->getQueue()->bind($this, $this->routingKey);
        $this->setIsInitialized(true);
    }

    public function send($message, $routingKey = null)
    {
        $this->init();
        if ($routingKey === null)
            $routingKey = $this->routingKey;
        
        if (!($message instanceof AMQPMessage))
            $message = $this->createMessage($message);
        
        $this->getClient()->getChannel()->basic_publish($message, $this->name, $routingKey);
    }
    
    public function createMessage($content, $options = array('content_type' => 'application/json', 'delivery_mode' => 2))
    {
        if (!empty($options['content_type']) && $options['content_type'] == 'application/json')
            $content = json_encode($content);
        
        return new AMQPMessage($content, $options);
    }
}
?>
