<?php
namespace app\components;

class ExchangeCollection/* extends \CAttributeCollection*/
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
    
    public function add($key, $value)
    {
        if (!($value instanceof Exchange))
            $value = $this->createExchange($key, $value);
        else {
            $value->name = $key;
            $value->setClient($this->getClient());
        }
        
        parent::add($key, $value);
    }
    
    public function itemAt($key)
    {
        $item = parent::itemAt($key);
        if ($item === null) {
            $item = $this->createExchange($key);
            $this->add($key, $item);
        }
      
        return $item;
    }
    
    public function contains($key)
    {
        return true;
    }
    
    protected function createExchange($name, $config = array())
    {
        $exchange = new Exchange();
        foreach($config as $key => $value)
            $exchange->{$key} = $value;
        
        $exchange->name = strtolower($name);
        $exchange->setClient($this->getClient());
        
        return $exchange;
    }
}
?>
