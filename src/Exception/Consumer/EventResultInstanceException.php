<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 18:01
 */

namespace Zwei\Emq\Exception\Consumer;


class EventResultInstanceException extends ConsumerBaseException
{
    /**
     * 事件消费返回事件结果实例错误
     *
     * @param string $additionalMessage 附加消息
     * @throws EventResultInstanceException|BaseException
     */
    public static function eventResultInstance($additionalMessage = '') {
        $code = 0;
        static::rawThrow('event consume result must EventResultInterface instance', $additionalMessage, $code);
    }
}
