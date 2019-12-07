<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 23:15
 */

namespace Zwei\Emq\Logger\Config;


use Zwei\Emq\Config\Arr;
use Zwei\Emq\Config\ConfigTrait;

class LoggerConfig
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
    
    public function getConfigKeys()
    {
        return [
            'name',
            'driver',
        ];
    }
}
