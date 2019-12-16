<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 15:55
 */

namespace Zwei\Emq\Consumer;


use Zwei\Emq\Connection\ConnectionManager;
use Zwei\Emq\Consumer\Config\ConsumerConfig;
use Zwei\Emq\Consumer\Config\KafkaConsumerConfig;
use Zwei\Emq\Logger\LoggerManager;

abstract class ConsumerAbstract implements ConsumerInterface
{
    /**
     * @var ConnectionManager
     */
    protected $connectionManager;
    
    /**
     * @var LoggerManager
     */
    protected $loggerManager;
    /**
     * @var mixed 消费者
     */
    protected $consumerQueue;
    
    /**
     * @var KafkaConsumerConfig
     */
    protected $config;
    
    /**
     * ConsumerAbstract constructor.
     *
     * @param ConsumerConfig $config
     */
    public function __construct(ConsumerConfig $config)
    {
        $this->config = new KafkaConsumerConfig($config->getData());
    }
    
    /**
     * @param ConnectionManager $connectionManager
     */
    public function setConnectionManager(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }
    
    /**
     * @param LoggerManager $loggerManager
     *
     */
    public function setLoggerManager(LoggerManager $loggerManager)
    {
        $this->loggerManager = $loggerManager;
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
     * @inheritdoc
     */
    public function getLog()
    {
        return $this->loggerManager->get($this->getConfig()->getLog());
    }
    
    /**
     * 消费失败日志
     *
     * @inheritdoc
     */
    public function getConsumeFailLog()
    {
        return $this->loggerManager->get($this->getConfig()->getConsumeFailLog());
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
