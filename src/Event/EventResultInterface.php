<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 12:23
 */

namespace Zwei\Emq\Event;


interface EventResultInterface
{
    /**
     * 从应用出题重试
     */
    const RETRY_TYPE_APPLICATION_TOPIC = 'application_topic';
    
    /**
     * 立即重试
     */
    const RETRY_TYPE_NOW = 'now';
    
    public function getData();
    
    /**
     * @return string
     */
    public function getRetryType();
    
    /**
     * @return bool
     */
    public function retryTypeIsApplicationTopic();
    
    /**
     * @return bool
     */
    public function retryTypeIsNow();
    
    public function getRetryCount();
    
    public function isSuccess();
    
    public function isFailExit();
    
}
