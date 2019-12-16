<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 12:44
 */

namespace Zwei\Emq\Connection\Config;


use Zwei\Emq\Config\Arr;
use Zwei\Emq\Config\ConfigInterface;
use Zwei\Emq\Config\ConfigTrait;

class ConnectionConfig implements ConfigInterface
{
    use ConfigTrait;
    
    public function getName()
    {
        return Arr::get($this->getData(), 'name');
    }
    
    public function getDriver()
    {
        return Arr::get($this->getData(), 'driver');
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
        ];
    }
}
