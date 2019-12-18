<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 17:59
 */

namespace Zwei\Emq\Exception\Consumer;


class EventCallbackConfigException extends ConsumerBaseException
{
    /**
     * 事件消费
     *
     * @param string $additionalMessage 附加消息
     * @throws EventResultInstanceException|BaseException
     */
    public static function eventCallbackConfig($additionalMessage = '') {
        $code = 0;
        static::rawThrow('event callback config', $additionalMessage, $code);
    }
}
