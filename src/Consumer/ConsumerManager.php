<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:41
 */

namespace Zwei\Emq\Consumer;


use Zwei\Emq\Helper\ManagerTrait;

/**
 * 消费者管理者
 *
 * Class ConsumerManager
 * @package Zwei\Emq\Consumer
 */
class ConsumerManager
{
    use ManagerTrait;
    
    /**
     * @param ConsumerInterface $consumer
     */
    public function add(ConsumerInterface $consumer)
    {
        return $this->addRaw($consumer->getName(), $consumer);
    }
    
    /**
     * @param ConsumerInterface $consumer
     */
    public function set(ConsumerInterface $consumer)
    {
        return $this->setRaw($consumer->getName(), $consumer);
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
     * @return ConsumerInterface
     */
    public function get($name)
    {
        return $this->getRaw($name);
    }
}
