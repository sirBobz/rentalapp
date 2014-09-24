<?php
namespace app\components;

abstract  class AbstractConsumer extends \CComponent
{
    public function consume($message)
    {
        $channel = $message->delivery_info['channel']; /* @var \PhpAmqpLib\Channel\AMQPChannel $channel */
        $tag = $message->delivery_info['delivery_tag'];
        $decoded = $this->decode($message);
        if ($decoded === false || !$this->predicate($decoded)) {
            $channel->basic_nack($tag);
            return false;
        }
        else {
            $channel->basic_ack($tag);
            $this->process($decoded);
            return true;
        }
    }
    
    abstract public function predicate($message);
    
    abstract public function process($message);
    
    public function decode($message)
    {
        $contentType = $message->get('content_type');
        if (!stristr($contentType, 'json'))
            return false;
        
        $decoded = json_decode($message->body, true);
        
        return $decoded === null ? false : $decoded;
    }
}
?>
