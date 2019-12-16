<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:28
 */
namespace Zwei\Emq\Event;

interface EventInterface
{
    /**
     * 心跳事件
     */
    const HEART_EVENT_NAME = 'HEART_EVENT';
    
    /**
     * @return string
     */
    public function getId();
    
    /**
     * @return integer
     */
    public function getKey();
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return array
     */
    public function getData();
    
    /**
     * @return integer
     */
    public function getTime();
    
    /**
     * @return string
     */
    public function getIp();
    
    /**
     * @param $additionalItem
     * @return mixed
     */
    public function addAdditional($additionalItem);
    
    /**
     * @return mixed
     */
    public function getAdditional();
    
    /**
     * @return bool
     */
    public function isHeartEvent();
    
    /**
     * @return bool
     */
    public function isSuccessEvent();
    
    /**
     * @return bool
     */
    public function isRetry();
    
    /**
     * @return integer
     */
    public function getRetryCount();
}
