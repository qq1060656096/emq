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
    
    public function getName()
    {
        return Arr::get($this->getData(), 'name');
    }
    
    public function getQueueName()
    {
        return Arr::get($this->getData(), 'queueName');
    }
    
    public function getAppNameTopics()
    {
        return Arr::get($this->getData(), 'appNameTopics');
    }
    
    public function getConnection()
    {
        return Arr::get($this->getData(), 'connection');
    }
    
    public function getConnectionName()
    {
        return Arr::get($this->getData(), 'connectionName');
    }
    
    public function getConsumeEvents()
    {
        return Arr::get($this->getData(), 'consumeEvents');
    }
    
    public function getConsumeEvent($eventName)
    {
        return Arr::get($this->getData(), 'consumeEvents.'.$eventName);
    }
    
    public function addConsumeEvent($eventName, callable $callback)
    {
        return Arr::add($this->getData(), 'consumeEvents.'.$eventName, $callback);
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
            'appNameTopics',
            'connectionName',
            'log',
            'consumeEvents',
            'consumeFailLog',
        ];
    }
}
