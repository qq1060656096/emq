<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:04
 */
namespace Zwei\Emq\Consumer;

use Monolog\Logger;
use Zwei\Emq\Config\ConsumerConfig;
use Zwei\Emq\Connection\ConnectionManager;
use Zwei\Emq\Event\EventInterface;
use Zwei\Emq\Event\EventResultInterface;
use Zwei\Emq\Logger\LoggerManager;

interface ConsumerInterface
{
    /**
     * 从消费队列中取出消息
     *
     * @return mixed $message
     */
    public function pop();
    
    /**
     * 消费事件
     * @param $message
     * @return mixed
     */
    public function consume($message);
    
    /**
     * 发送成功事件
     *
     * @param EventInterface $event
     * @param EventResultInterface $eventResult
     * @return mixed
     */
    public function sendSuccessEvent(EventInterface $event, EventResultInterface $eventResult);
    
    /**
     * 从消费队列中删除消息
     * @param $message
     * @return mixed
     */
    public function del($message);
    
    /**
     * 计划任务消费
     */
    public function scheduleTaskConsume();
    
    /**
     * @return ConsumerConfig
     */
    public function getConfig();
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getQueueName();
    
    /**
     * 应用主题
     *
     * @return array
     */
    public function getAppNameTopics();
    
    /**
     * 消费日志
     *
     * @return Logger
     */
    public function getLog();
    
    /**
     * 消费失败日志
     *
     * @return Logger
     */
    public function getConsumeFailLog();
    
    /**
     * 获取消费事件
     * @param string $eventName
     * @return mixed $callback
     */
    public function getConsumeEvent($eventName);
    
    /**
     * 获取所有消费事件
     *
     * @return array
     */
    public function getConsumeEvents();
    
    /**
     * @param string $eventName
     * @param callable $callback
     * @return mixed
     */
    public function addConsumeEvent($eventName, callable $callback);
    
    /**
     * @param ConnectionManager $connectionManager
     * @return mixed
     */
    public function setConnectionManager(ConnectionManager $connectionManager);
    
    /**
     * @param LoggerManager $loggerManager
     * @return mixed
     */
    public function setLoggerManager(LoggerManager $loggerManager);

}
