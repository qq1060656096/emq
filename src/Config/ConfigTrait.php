<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 15:28
 */

namespace Zwei\Emq\Config;


trait ConfigTrait
{
    protected $data;
    
    public function __construct(array $config)
    {
        $this->data = $config;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public abstract function getConfigKeys();
}
