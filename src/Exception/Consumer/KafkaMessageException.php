<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-16
 * Time: 14:51
 */

namespace Zwei\Emq\Exception\Consumer;


class KafkaMessageException extends ConsumerBaseException
{
    /**
     * kafka消息一出
     * @param string $additionalMessage
     * @param int $code
     * @throws KafkaMessageException
     */
    public static function kafkaMessageError($additionalMessage = '', $code = 0) {
        static::rawThrow('event consume kafka message error.', $additionalMessage, $code);
    }
}
