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

class EventConsumeFailExitException extends ConsumerBaseException
{
    
    /**
     * 事件消费失败退出
     *
     * @param string $additionalMessage 附加消息
     * @param EventInterface|null $event
     * @param EventResultInterface $eventResult
     * @throws EventConsumeFailExitException
     */
    public static function eventConsumeFailExit($additionalMessage = '', EventInterface $event = null, EventResultInterface $eventResult) {
        $code = 0;
        $message = 'event consume fail exit';
        static::eventCommonThrow($message, $additionalMessage, $code, $event, $eventResult);
    }
}
