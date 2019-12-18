<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 12:42
 */
namespace Zwei\Emq\Consumer\Config;

use Zwei\Emq\Config\Arr;
use Zwei\Emq\Config\ConfigInterface;
use Zwei\Emq\Config\ConfigTrait;

class ConsumerConfig implements ConfigInterface
{
    use ConfigTrait;
    
    /**
     * @return string
     */
    public function getName()
    {
        return Arr::get($this->getData(), 'name');
    }
    
    /**
     * @return string
     */
    public function getQueueName()
    {
        return Arr::get($this->getData(), 'queueName');
    }
    
    /**
     * @return string
     */
    public function getQueueType()
    {
        return Arr::get($this->getData(), 'queueType');
    }
    
    /**
     * @return array
     */
    public function getAppNameTopics()
    {
        return Arr::get($this->getData(), 'appNameTopics');
    }
    
    /**
     * @return string
     */
    public function getConnectionName()
    {
        return Arr::get($this->getData(), 'connectionName');
    }
    
    /**
     * @return array
     */
    public function getConsumeEvents()
    {
        return Arr::get($this->getData(), 'consumeEvents');
    }
    
    /**
     * @param string $eventName
     * @return callable
     */
    public function getConsumeEvent($eventName)
    {
        return Arr::get($this->getData(), 'consumeEvents.'.$eventName);
    }
    
    /**
     * @param string $eventName
     * @param callable $callback
     * @return array
     */
    public function addConsumeEvent($eventName, callable $callback)
    {
        return Arr::add($this->data, 'consumeEvents.'.$eventName, $callback);
    }
    
    public function getLog()
    {
        return Arr::get($this->getData(), 'log');
    }
    
    public function getConsumeFailLog()
    {
        return Arr::get($this->getData(), 'consumeFailLog');
    }
    
    public function getConfigKeys()
    {
        return [
            'name',
            'queueName',
            'queueType',
            'appNameTopics',
            'connectionName',
            'log',
            'consumeEvents',
            'consumeFailLog',
        ];
    }
}
