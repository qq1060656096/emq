<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 15:55
 */

namespace Zwei\Emq\Consumer;


use Zwei\Emq\Config\ConsumerConfig;
use Zwei\Emq\Connection\ConnectionInterface;

abstract class ConsumerAbstract implements ConsumerInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;
    
    /**
     * @var mixed 消费者
     */
    protected $consumer;
    
    /**
     * @var ConsumerConfig
     */
    protected $config;
    
    public function __construct(ConnectionInterface $connection, ConsumerConfig $config)
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->consumer = $this->connection->createConsumer($this->config);
    }
    
    /**
     * @return ConsumerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * 消费者名
     *
     * @return string
     */
    public function getName()
    {
        return $this->getConfig()->getName();
    }
    
    /**
     * 队列名
     *
     * @return string
     */
    public function getQueueName()
    {
        return $this->getConfig()->getQueueName();
    }
    
    /**
     * 主题名
     *
     * @return array
     */
    public function getAppNameTopics()
    {
        return $this->getConfig()->getAppNameTopics();
    }
    
    /**
     * 日志
     *
     * @return string
     */
    public function getLog()
    {
        return $this->getConfig()->getLog();
    }
    
    /**
     * 消费失败日志
     *
     * @return mixed
     */
    public function getConsumeFailLog()
    {
        return $this->getConfig()->getDeathLog();
    }
    
    /**
     * 添加消费事件
     *
     * @param string $eventName
     * @param callable $callback
     * @return mixed
     */
    public function addConsumeEvent($eventName, callable $callback)
    {
        return $this->getConfig()->addConsumeEvent($eventName, $callback);
    }
    
    /**
     * 获取消费事件callback
     * @param string $eventName
     * @return mixed
     */
    public function getConsumeEvent($eventName)
    {
        return $this->getConfig()->getConsumeEvent($eventName);
    }
    
    /**
     * 获取所有消费事件
     * @return mixed
     */
    public function getConsumeEvents()
    {
        return $this->getConfig()->getConsumeEvents();
    }
}
