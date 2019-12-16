<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 17:57
 */
namespace Zwei\Emq\Exception;

class BaseException extends \Exception
{
    /**
     * 抛出异常
     * @param string $message 消息
     * @param string $additionalMessage 附加消息
     * @param integer $code 异常错误码
     * @throws BaseException
     */
    public static function rawThrow($message, $additionalMessage, $code) {
        throw new static($message.$additionalMessage, $code);
    }
}
