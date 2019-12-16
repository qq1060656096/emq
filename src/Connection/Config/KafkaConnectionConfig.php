<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-12
 * Time: 17:24
 */

namespace Zwei\Emq\Connection\Config;


use Zwei\Emq\Config\Arr;

class KafkaConnectionConfig extends ConnectionConfig
{
    public function getOptions()
    {
        return Arr::get($this->getData(), 'options', []);
    }
    
    /**
     * 配置键
     *
     * @return array
     */
    public function getConfigKeys()
    {
        return [
            'name',
            'driver',
            'options',
        ];
    }
}
