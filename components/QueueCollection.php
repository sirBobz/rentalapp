<?php
namespace app\components;

class QueueCollection extends \CAttributeCollection
{
    protected $_client;
    
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
    
    public function itemAt($key)
    {
        $item = parent::itemAt($key);
        if ($item === null) {
            $item = $this->createQueue($key);
            $this->add($key, $item);
        }
        return $item;
    }
    
    public function createQueue($name, $config = array())
    {
        $queue = new Queue();
        foreach($config as $key => $value)
            $queue->{$key} = $value;
        
        $queue->name = $name;
        $queue->setClient($this->getClient());
        
        return $queue;
    }
    
    public function add($key, $value)
    {
        if (!($value instanceof Queue))
            $value = $this->createQueue($key, $value);
        else {
            $value->name = $key;
            $value->setClient($this->getClient());
        }
        
        parent::add($key, $value);
    }
    
    public function contains($key)
    {
        return true;
    }
    
}
?>
