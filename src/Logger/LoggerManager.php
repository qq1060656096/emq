<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 22:20
 */
namespace Zwei\Emq\Logger;

use Zwei\Emq\Config\Arr;
use Zwei\Emq\Helper\ManagerTrait;
use Monolog\Logger;
use Zwei\Emq\Logger\Config\LoggerConfig;

class LoggerManager
{
    use ManagerTrait;
    
    protected $drivers;
    
    /**
     * @param Logger $logger
     */
    public function add(Logger $logger)
    {
        return $this->addRaw($logger->getName(), $logger);
    }
    
    /**
     * @param Logger $logger
     */
    public function set(Logger $logger)
    {
        return $this->setRaw($logger->getName(), $logger);
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function remove($name)
    {
        return $this->removeRaw($name);
    }
    
    /**
     * @param string $name
     *
     * @return Logger
     */
    public function get($name)
    {
        return $this->getRaw($name);
    }
    
    /**
     * 获取驱动
     *
     * @param string $driver
     * @return \Closure
     */
    public function getDriver($driver)
    {
        $closure = Arr::get($this->drivers, $driver);
        return $closure;
    }
    
    /**
     * @param string $driver
     * @param LoggerConfig $config
     * @return ConnectionInterface
     */
    public function makeDriver($driver, LoggerConfig $config)
    {
        $closure = $this->getDriver($driver);
        return $closure($config);
    }
    
    /**
     * 扩展驱动
     *
     * @param string $driver
     * @param \Closure $closure
     * @return $this
     */
    public function extend($driver, \Closure $closure)
    {
        $this->drivers[$driver] = $closure;
        return $this;
    }
}
