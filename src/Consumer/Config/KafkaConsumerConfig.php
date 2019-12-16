<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-15
 * Time: 22:13
 */

namespace Zwei\Emq\Consumer\Config;


use Zwei\Emq\Config\Arr;

class KafkaConsumerConfig extends ConsumerConfig
{
    public function getTimeoutMs()
    {
        return Arr::get($this->getData(), 'timeoutMs');
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
            'timeoutMs',
        ];
    }
}
