<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:15
 */

namespace Zwei\Emq\Consumer;

use RdKafka\Conf;
use RdKafka\KafkaConsumer as RdKafkaConsumer;
use RdKafka\Message;
use Zwei\Emq\Event\Event;
use Zwei\Emq\Event\EventInterface;
use Zwei\Emq\Event\EventResultInterface;

/**
 * Class KafkaConsumer
 * @package Zwei\Emq\Consumer
 * @property RdKafkaConsumer $consumer
 */
class KafkaConsumer extends ConsumerAbstract
{
    /**
     * 获取消息
     * @return Message
     */
    public function pop()
    {
        $message = $this->consumer->consume($this->getConsumerConfig()->getTimeoutMs());
        return $message;
    }
    
    /**
     * @param Message $message
     * @return array|mixed
     */
    public function consume($message)
    {
        // 1. 获取消息
        // 2. 创建事件类
        // 3. 获取事件，如果事件不存在跑出事件EventNotFound
        // 4. 执行事件
        // 5. 检测事件返回值是不是EventResultInterface的示例，不是记录失败日志
        // 6. 检测如果执行失败需要退出程序
        // 7. 如果执行事件失败就记录失败日志
        
        $payload = $message->payload;
        $event = new Event($payload);
        $callback = $this->getConsumeEvent($event->getName());
        if (!is_callable()) {
        
        }
        // 5. 检测事件返回值是不是EventResultInterface的实例，记录失败和异常日志
        $eventResult = call_user_func_array($callback, [$event]);
        if (!($eventResult instanceof EventResultInterface)) {
        
        }
        // 6. 检测如果执行失败需要退出程序
        if ($eventResult->isFailExit()) {
            die();
        }
        // 7. 如果执行事件失败就记录失败日志
        if (!$eventResult->isSuccess()) {
        
        }
        return [$event, $eventResult];
    }
    
    /**
     * 发送成功事件
     *
     * @param EventInterface $event
     * @param EventResultInterface $eventResult
     * @return mixed|void|null
     */
    public function sendSuccessEvent(EventInterface $event, EventResultInterface $eventResult)
    {
        // 1. 如果事件是成功事件就不在发送了
        // 2. 如果事件执行失败就不发送成功事件
        // 3. 如果发送成功事件失败，就记录失败日志
        if ($event->isSuccessEvent()) {
            return;
        }
        
        if (!$eventResult->isSuccess()) {
            return null;
        }
        
    }
    
    /**
     * 删除消息
     *
     * @param Message $message
     * @return mixed
     */
    public function del($message)
    {
        return $this->consumer->commit($message);
    }
    
    /*
     * 计划任务消费
     */
    public function scheduleTaskConsume()
    {
        while (true) {
            // 1. 从消费队列获取消息
            // 2. 消费消息
            // 3. 从消费队列删除消息
            // 4. 异常就记录日志
            try {
                $message = $this->pop();
                list($event, $eventResult) = $this->consumer($message);
                $this->sendSuccessEvent($event, $eventResult);
                $this->del($message);
            } catch (\Exception $e) {
            
            }
        }
    }
    
}
