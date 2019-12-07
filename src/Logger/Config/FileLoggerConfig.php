<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 23:15
 */

namespace Zwei\Emq\Logger\Config;


use Zwei\Emq\Config\Arr;


class FileLoggerConfig extends LoggerConfig
{
    public function getFile()
    {
        return Arr::get($this->getData(), 'file');
    }
    
    public function getConfigKeys()
    {
        return [
            'name',
            'driver',
            'file',
        ];
    }
}
