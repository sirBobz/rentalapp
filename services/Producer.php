<?php
namespace app\services;

use Yii;
use app\services\BaseAmqp;
use PhpAmqpLib\Message\AMQPMessage;

class Producer extends BaseAmqp
{
    protected $contentType = "text/json";
    protected $deliveryMode = 2;
    
    public function __construct($exchange)
    {
        $this->setExchangeOptions(['name' => $exchange]);
        
        $conn = new \PhpAmqpLib\Connection\AMQPConnection(Yii::$app->params['rabbitmqHost'], 
                    Yii::$app->params['rabbitmqPort'], Yii::$app->params['rabbitmqUser'], 
                    Yii::$app->params['rabbitmqPassword']);
        $ch = $conn->channel();
        
        parent::__construct($conn, $ch);
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        
        return $this;
    }
    
    public function setDeliveryMode($deliveryMode)
    {
        $this->deliveryMode = $deliveryMode;

        return $this;
    }
    
    protected function getBasicProperties()
    {
        return array('content_type' => $this->contentType, 'delivery_mode' => $this->deliveryMode);
    }
    
    /**
    * Publishes the message and merges additional properties with basic properties
    *
    * @param string $msgBody
    * @param string $routingKey
    * @param array $additionalProperties
    */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array())
    {
        //$this->setExchangeOptions(['name' => 'test_exchange']);
        if ($this->autoSetupFabric) {
            $this->setupFabric();
        }

        $msg = new AMQPMessage((string) $msgBody, array_merge($this->getBasicProperties(), $additionalProperties));
        $this->getChannel()->basic_publish($msg, $this->exchangeOptions['name'], (string) $routingKey);
    }
}
?>
