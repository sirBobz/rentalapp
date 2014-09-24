<?php
namespace app\components;

class Queue extends \CComponent
{
    public $name;
    public $isExclusive = false;
    public $isPassive = false;
    public $isDurable = true;
    public $autoDelete = false;
    public $status;
    protected $_identifier;
    protected $_client;
    protected $_isInitialized = false;
    
    public function setClient($client)
    {
        $this->_client = $client;
    }
    
    public function getClient()
    {
        if ($this->_client === null) {
            $app = \Yii::app();
            if (!$app->hasComponent('amqp'))
                throw new \CException(__CLASS__." expects a 'mq' application component!");
            $this->_client = $app->getComponent('amqp');
        }
        return $this->_client;
    }
    
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
    public function setIsInitialized($isInitialized)
    {
        $this->_isInitialized = $isInitialized;
    }
    
    public function getIsInitialized()
    {
        return $this->_isInitialized;
    }
    
    public function init()
    {
        if ($this->getIsInitialized())
            return;
        
        $response = $this->getClient()->getChannel()->queue_declare(
            $this->name,
            $this->isPassive,
            $this->isDurable,
            $this->isExclusive,
            $this->autoDelete
        );
        $this->_identifier = array_shift($response);
        $this->setIsInitialized(true);
    }
    
    public function bind(Exchange $exchange, $routingKey = null)
    {
        $this->init();
        $this->getClient()->getChannel()->queue_bind($this->getIdentifier(), $exchange->name, $routingKey);
    }
    
    public function consume($callback, $tag = '', $excludeLocal = false, $noAck = false, $isExclusive = null, 
            $noWait = false)
    {
        if ($callback instanceof AbstractConsumer)
            $callback = array($callback, 'consume');
        
        elseif (!is_callable($callback))
            throw new \InvalidArgumentException('First argument to '.__METHOD__.' must be callable!');
        
        $this->init();
        $this->getClient()->getChannel()->basic_consume(
            $this->getIdentifier(),
            $tag,
            $excludeLocal,
            $noAck,
            ($isExclusive === null) ? $this->isExclusive : $isExclusive,
            $noWait,
            $callback
        );
    }
}
?>
