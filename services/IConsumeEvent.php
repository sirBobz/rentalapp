<?php
namespace app\services;

use PhpAmqpLib\Message\AMQPMessage;
/**
 *
 * @author JLukoba
 */
interface IConsumeEvent {
    const MSG_ACK = 1;
    
    const MSG_SINGLE_NACK_REQUEUE = 2;
    
    const MSG_REJECT_REQUEUE = 0;
    
    //flag for reject and drop
    const MSG_REJECT = -1;
    
    /*
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg);
}

?>
