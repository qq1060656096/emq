<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-13
 * Time: 09:33
 */

namespace Zwei\Emq\Event;


use Zwei\Emq\Config\Arr;

class RetryEvent extends EventAbstract
{
    /**
     * 增加重试次数
     *
     * @return array
     */
    public function incrementRetryCount()
    {
        $retryCount = $this->getRetryCount();
        $retryCount ++;
        return Arr::set($this->getData(), 'retryCount', $retryCount);
    }
    
    /**
     * @param EventAbstract $eventAbstract
     * @return RetryEvent
     */
    public static function toRetryEvent(EventAbstract $eventAbstract)
    {
        $obj = new RetryEvent('', [], '');
        $obj->data = $eventAbstract->data;
        return $obj;
    }
    
    /**
     * @return Event
     */
    public function toEvent()
    {
        $event = new Event();
        $event->data = $this->data;
        return $event;
    }
}
