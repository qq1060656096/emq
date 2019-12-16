<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:42
 */

namespace Zwei\Emq\Connection;


use Zwei\Emq\Config\Arr;
use Zwei\Emq\Connection\Config\ConnectionConfig;
use Zwei\Emq\Helper\ManagerTrait;

/**
 * Class ConnectionManager
 * @package Zwei\Emq\Connection
 */
class ConnectionManager
{
    use ManagerTrait;
    
    protected $drivers;
    
    /**
     * @param ConnectionInterface $connection
     */
    public function add(ConnectionInterface $connection)
    {
        return $this->addRaw($connection->getName(), $connection);
    }
    
    /**
     * @param ConnectionInterface $connection
     */
    public function set(ConnectionInterface $connection)
    {
        return $this->setRaw($connection->getName(), $connection);
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
     * @return ConnectionInterface
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
     * @param ConnectionConfig $config
     * @return ConnectionInterface
     */
    public function makeDriver($driver, ConnectionConfig $config)
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
