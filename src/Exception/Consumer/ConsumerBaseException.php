<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 18:51
 */

namespace Zwei\Emq\Exception\Consumer;


use Throwable;
use Zwei\Emq\Event\EventInterface;
use Zwei\Emq\Event\EventResultInterface;
use Zwei\Emq\Exception\BaseException;

class ConsumerBaseException extends BaseException
{
    /**
     * @var EventInterface
     */
    protected $event;
    
    /**
     * @var EventResultInterface
     */
    protected $eventResult;
    
    /**
     * @return EventInterface
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    /**
     * @param EventInterface $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
    
    /**
     * @return EventResultInterface
     */
    public function getEventResult()
    {
        return $this->eventResult;
    }
    
    /**
     * @param EventResultInterface $eventResult
     */
    public function setEventResult($eventResult)
    {
        $this->eventResult = $eventResult;
    }
    
    /**
     * 事件公共异常
     * @param string $message
     * @param string $additionalMessage
     * @param integer $code
     * @param EventInterface|null $event
     * @param EventResultInterface $eventResult
     * @throws EventConsumeFailException
     */
    public static function eventCommonThrow($message, $additionalMessage, $code, EventInterface $event = null, EventResultInterface $eventResult) {
        $obj = new static($message.$additionalMessage, $code);
        $obj->setEvent($event);
        $obj->setEventResult($eventResult);
        throw $obj;
    }
}
