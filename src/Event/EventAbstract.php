<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-13
 * Time: 09:48
 */

namespace Zwei\Emq\Event;


use Zwei\Emq\Config\Arr;

abstract class EventAbstract implements EventInterface
{
    protected $data;
    
    /**
     * EventAbstract constructor.
     * @param string $name
     * @param array $data
     * @param string $ip
     * @param integer|null $key
     * @param integer|null $time
     */
    public function __construct($name, $data, $ip, $key = null, $time = null)
    {
        $retryCount = 0;
        $this->data = [
            'id' => $this->generateId($ip),
            'key' => $key,
            'eventKey' => $name,
            'data' => $data,
            'time' => $time === null ? time() : $time,
            'ip' => $ip,
            'retryCount' => $retryCount,
        ];
    }
    
    /**
     * 生成id
     * @param string $ip
     * @return string
     */
    protected function generateId($ip)
    {
        $id = $this->uuid();
        return $id;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return Arr::get($this->data, 'id');
    }
    
    /**
     * @return int
     */
    public function getKey()
    {
        return Arr::get($this->data, 'key');
    }
    
    /**
     * @inheritdoc
     */
    public function getName()
    {
        // 兼容老板, 老版事件名 eventKey，新版事件名 name
        $eventName =  Arr::get($this->data, 'eventKey');
        if (is_null($eventName)) {
            $eventName =  Arr::get($this->data, 'name');
        }
        return $eventName;
    }
    
    public function getData()
    {
        return Arr::get($this->data, 'data');
    }
    
    /**
     * @return int
     */
    public function getTime()
    {
        return Arr::get($this->data, 'time');
    }
    
    public function getIp()
    {
        return Arr::get($this->data, 'ip');
    }
    
    /**
     * @param mixed $additionalItem
     * @return array
     */
    public function addAdditional($additionalItem)
    {
        return Arr::add($this->data, 'additional', $additionalItem);
    }
    
    public function getAdditional()
    {
        return Arr::get($this->data, 'additional');
    }
    
    /**
     * @return bool
     */
    public function isHeartEvent()
    {
        return $this->getName() === self::HEART_EVENT_NAME ? true : false;
    }
    
    /**
     * @return mixed|void
     */
    public function isSuccessEvent()
    {
        $eventName = $this->getName();
        $eventNameSuffix = '_success';
        $arr = explode($eventNameSuffix, $eventName, 2);
        $eventNameSuccess = implode('', $arr).$eventNameSuffix;
        return $eventName === $eventNameSuccess ? true : false;
    }
    
    /**
     * @return bool
     */
    public function isRetry()
    {
        return $this->getRetryCount() > 0 ? true : false;
    }
    
    /**
     * @return int
     */
    public function getRetryCount()
    {
        return Arr::get($this->data, 'retryCount', 0);
    }
    
    /**
     * 生产成uuid
     * @return string
     */
    protected function uuid() {
        if (function_exists ( 'com_create_guid' )) {
            return com_create_guid ();
        } else {
            mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
            $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
            $hyphen = chr ( 45 ); // "-"
            $uuid = '' . //chr(123)// "{"
                substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
            //.chr(125);// "}"
            return $uuid;
        }
    }
    
    /**
     * @param string $jsonStr
     * @return EventAbstract
     */
    public static function jsonToEvent($jsonStr)
    {
        $event = new static('', [], '');
        $event->data = \json_decode($jsonStr, true);
        return $event;
    }
    
    public function __toString()
    {
        return \json_encode($this->data);
    }
}
