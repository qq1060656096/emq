<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 18:30
 */

namespace Zwei\Emq\Exception\Consumer;


use Zwei\Emq\Event\EventInterface;
use Zwei\Emq\Event\EventResultInterface;

class EventConsumeFailException extends ConsumerBaseException
{
    
    /**
     * 事件消费失败
     *
     * @param string $additionalMessage 附加消息
     * @param EventInterface|null $event
     * @param EventResultInterface $eventResult
     * @throws EventConsumeFailException
     */
    public static function eventConsumeFail($additionalMessage = '', EventInterface $event = null, EventResultInterface $eventResult) {
        $code = 0;
        $message = 'event consume fail';
        static::eventCommonThrow($message, $additionalMessage, $code, $event, $eventResult);
    }
}
