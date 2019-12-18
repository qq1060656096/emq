<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-16
 * Time: 22:50
 */

namespace Zwei\Emq\Event;


use Zwei\Emq\Config\Arr;

abstract class EventResultAbstract implements EventResultInterface
{
    /**
     * @var array
     */
    protected $data;
    /**
     * EventResult constructor.
     * @param bool $isSuccess
     * @param array $data
     * @param string|null $retryType
     * @param int $retryCount
     * @param bool $isFailExit
     */
    public function __construct($isSuccess = false, $data = [], $isFailExit = false, $retryCount = 0, $retryType = EventResultInterface::RETRY_TYPE_NOW)
    {
        $this->setIsSuccess($isSuccess);
        $this->setData($data);
        $this->setRetryType($retryType);
        $this->setRetryCount($retryCount);
        $this->setIsFailExit($isFailExit);
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        Arr::set($this->data, 'data', $data);
    }
    
    /**
     * @param mixed $retryType
     */
    public function setRetryType($retryType)
    {
        Arr::set($this->data, 'retryType', $retryType);
    }
    
    /**
     * @param integer $retryCount
     */
    public function setRetryCount($retryCount)
    {
        Arr::set($this->data, 'retryCount', $retryCount);
    }
    
    /**
     * @param bool $isSuccess
     */
    public function setIsSuccess($isSuccess)
    {
        Arr::set($this->data, 'isSuccess', $isSuccess);
    }
    
    /**
     * @param bool $isFailExit
     */
    public function setIsFailExit($isFailExit)
    {
        Arr::set($this->data, 'isFailExit', $isFailExit);
    }
    
    
    /**
     * @return array
     */
    public function getData()
    {
        return Arr::get($this->data, 'data');
    }
    
    /**
     * @return string
     */
    public function getRetryType()
    {
        return Arr::get($this->data, 'retryType');
    }
    
    /**
     * @return bool
     */
    public function retryTypeIsApplicationTopic()
    {
        return $this->getRetryType() === self::RETRY_TYPE_APPLICATION_TOPIC ? true : false;
    }
    
    /**
     * @return bool
     */
    public function retryTypeIsNow()
    {
        return $this->getRetryType() === self::RETRY_TYPE_NOW ? true : false;
    }
    
    /**
     * @return int
     */
    public function getRetryCount()
    {
        return Arr::get($this->data, 'retryCount');
    }
    
    /**
     * @return bool
     */
    public function isSuccess()
    {
        return Arr::get($this->data, 'isSuccess');
    }
    
    /**
     * @return bool
     */
    public function isFailExit()
    {
        return Arr::get($this->data, 'isFailExit');
    }
    
    public function __toString()
    {
        return \json_encode($this->data);
    }
}
