<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 17:59
 */

namespace Zwei\Emq\Exception\Consumer;


class NotConfigEventCallbackException extends ConsumerBaseException
{
    /**
     * 没有配置事件消费
     *
     * @param string $additionalMessage 附加消息
     * @throws EventResultInstanceException|BaseException
     */
    public static function notConfigEventCallback($additionalMessage = '') {
        static::rawThrow('not config event callback', $additionalMessage);
    }
}
