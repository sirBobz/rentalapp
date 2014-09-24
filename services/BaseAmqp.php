<?php
namespace app\services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPLazyConnection;

abstract class BaseAmqp
{
    protected $conn;
    protected $ch;
    protected $consumerTag;
    protected $exchangeDeclared = false;
    protected $queueDeclared = false;
    protected $routingKey = '';
    protected $autoSetupFabric = true;
    protected $basicProperties = ['content_type' => 'text/plain', 'delivery_mode' => 2];
    
    protected $exchangeOptions = [
        'type' => 'direct',
        'passive' => false,
        'durable' => true,
        'auto_delete' => false,
        'internal' => false,
        'nowait' => false,
        'arguments' => null,
        'ticket' => null,
        'declare' => true
    ];
    
    protected $queueOptions = [
        'name' => 'rentalapp_queue',
        'passive' => false,
        'durable' => true,
        'exclusive' => false,
        'auto_delete' => false,
        'nowait' => false,
        'arguments' => null,
        'ticket' => null
    ];
    
    public function __construct(AMQPConnection $conn, AMQPChannel $ch = null, 
            $consumerTag = null) {
        $this->conn = $conn;
        $this->ch = $ch;
                
        if (!($conn instanceof AMQPLazyConnection))
            $this->getChannel ();
        
        $this->consumerTag = empty($consumerTag) ? 
                sprintf("PHPPROCESS_%s_%s", gethostname(), getmypid()) : $consumerTag;
    }
    
    public function getChannel()
    {
        if(empty($this->ch))
            $this->ch = $this->conn->channel ();
        
        return $this->ch;
    }
    
    public function setChannel(AMQPChannel $ch)
    {
        $this->ch = $ch;
    }
    
    public function setExchangeOptions(array $options = array())
    {
        $this->exchangeOptions = array_merge($this->exchangeOptions, $options);
    }
    
    public function setQueueOptions(array $options = array())
    {
        $this->queueOptions = array_merge($this->exchangeOptions, $options);
    }
    
    public function setRoutingKey($routingKey)
    {
        $this->routingKey = $routingKey;
    }
    
    protected function exchangeDeclare()
    {
        if($this->exchangeOptions['declare']){
            $this->getChannel()->exchange_declare(
                    $this->exchangeOptions['name'],
                    $this->exchangeOptions['type'],
                    $this->exchangeOptions['passive'],
                    $this->exchangeOptions['durable'],
                    $this->exchangeOptions['auto_delete'],
                    $this->exchangeOptions['internal'],
                    $this->exchangeOptions['nowait'],
                    $this->exchangeOptions['arguments'],
                    $this->exchangeOptions['ticket']);
            $this->exchangeDeclared = TRUE;
        }
    }
    
    protected function queueDeclare()
    {
        if(null !== $this->queueOptions['name'])
        {
            list($queueName,,) = $this->getChannel()->queue_declare(
                    $this->queueOptions['name'],
                    $this->queueOptions['passive'],
                    $this->queueOptions['durable'],
                    $this->queueOptions['exclusive'],
                    $this->queueOptions['auto_delete'],
                    $this->queueOptions['nowait'],
                    $this->queueOptions['arguments'],
                    $this->queueOptions['ticket']);
            
            if(isset($this->queueOptions['routing_keys']) && count($this->queueOptions['routing_keys']) > 0)
            {
                foreach ($this->queueOptions['routing_keys'] as $routingKey)
                {
                    $this->getChannel()->queue_bind($queueName, $this->exchangeOptions['name'], $routingKey);
                }
            }
            else {
                $this->getChannel()->queue_bind($queueName, $this->exchangeOptions['name'], $this->routingKey);
            }
            
            $this->queueDeclared = TRUE;
        }
    }
    
    public function setupFabric()
    {
        if(!$this->exchangeDeclared)
            $this->exchangeDeclare ();
        if(!$this->queueDeclared)
            $this->queueDeclare ();
    }
    
    public function disableAutoSetupFabric()
    {
        $this->autoSetupFabric = FALSE;
    }

    public function __destruct() {
        if($this->ch)
            $this->ch->close ();
        
        if($this->conn)
            $this->conn->close ();
    }
}
?>
