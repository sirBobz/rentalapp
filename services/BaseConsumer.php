<?php
namespace app\services;

use app\services\BaseAmqp;

abstract class BaseConsumer extends BaseAmqp
{
    protected $target;
    protected $consumed = 0;
    protected $callback;
    protected $forceStop = false;
    protected $idleTimeout = 0;
    
    public function setCallBack($callback)
    {
        $this->callback = $callback;
    }
    
    public function start($msgAmount = 0)
    {
        $this->target = $msgAmount;
        
        $this->setupConsumer();
        
        while(count($this->getChannel()->callbacks))
            $this->getChannel ()->wait();
    }
    
    public function stopConsuming()
    {
        $this->getChannel()->basic_cancel($this->getConsumerTag());
    }

    protected function setupConsumer()
    {
        if($this->autoSetupFabric)
            $this->setupFabric ();
        
        $this->getChannel()->basic_consume($this->queueOptions['name'], $this->getConsumerTag(), 
                false, false, false, false, array($this, 'processMessage'));
    }
    
    protected function maybeStopConsumer()
    {
        if(extension_loaded('pcntl') && (defined('AMQP_WITHOUT_SIGNALS') ? !AMQP_WITHOUT_SIGNALS : TRUE))
        {
            if(!function_exists('pcntl_signal_dispatch'))
                throw new \BadFunctionCallException("fn 'pcntl_signal_dispatch' is referenced in the php.ini 'disable_functions' and can't be called.");
            
            pcntl_signal_dispatch();
        }
        
        if($this->forceStop || ($this->consumed == $this->target && $this->target > 0))
            $this->stopConsuming ();
        else
            return;
    }

    public function setConsumerTag($tag)
    {
        $this->consumerTag = $tag;
    }
    
    public function getConsumerTag()
    {
        return $this->consumerTag;
    }
    
    public function forceStopConsumer()
    {
        $this->forceStop = TRUE;
    }
    
    public function setQosOptions($prefetchSize = 0, $prefetchCount = 0, $global = TRUE)
    {
        $this->getChannel()->basic_qos($prefetchSize, $prefetchCount, $global);
    }
    
    public function setIdleTimeout($idleTimeout)
    {
        $this->idleTimeout = $idleTimeout;
    }
    
    public function getIdleTimeout()
    {
        return $this->idleTimeout;
    }
}
?>
