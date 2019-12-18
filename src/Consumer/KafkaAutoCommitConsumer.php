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
use Zwei\Emq\Event\RetryEvent;
use Zwei\Emq\Exception\Consumer\KafkaMessageException;
use Zwei\Emq\Exception\Consumer\ConsumerBaseException;
use Zwei\Emq\Exception\Consumer\EventCallbackConfigException;
use Zwei\Emq\Exception\Consumer\EventConsumeFailException;
use Zwei\Emq\Exception\Consumer\EventConsumeFailRetryCountMaximumException;
use Zwei\Emq\Exception\Consumer\EventResultInstanceException;
use Zwei\Emq\Exception\Consumer\NotConfigEventCallbackException;


/**
 * Class KafkaAutoCommitConsumer
 * @package Zwei\Emq\Consumer
 * @property RdKafkaConsumer $consumerQueue
 */
class KafkaAutoCommitConsumer extends KafkaConsumerAbstract
{
    /**
     * @param RdKafkaConsumer $consumerQueue
     */
    public function setConsumerQueue(RdKafkaConsumer $consumerQueue)
    {
        $this->consumerQueue = $consumerQueue;
    }
    /**
     * 获取消息
     * @return Message
     */
    public function pop()
    {
        $message = $this->consumerQueue->consume($this->getConfig()->getTimeoutMs());
        return $message;
    }
    
    /**
     * @param $message
     * @return array [$event, $eventResult]
     * @throws EventConsumeFailRetryCountMaximumException
     * @throws EventResultInstanceException
     * @throws \Zwei\Emq\Exception\BaseException
     */
    public function consume($message)
    {
        // 1. 获取消息
        // 2. 创建事件类
        // 3. 消费事件
        // 4. 重试
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $payload = $message->payload;
                $errorMessage = sprintf("consumer.kafka.message.noError");
                $this->getLog()->info($errorMessage, [
                    '$message' => $message,
                ]);
            break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:// 没有消息
                //                    echo "No more messages; will wait for more\n";
                $errorMessage = sprintf("consumer.kafka.message.noMoreMessage");
                $this->getLog()->info($errorMessage, [
                    '$message' => $message,
                    'errstrs' => $message->errstr(),
                ]);
                KafkaMessageException::kafkaMessageError($message->errstr(), $message->err);
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:// 超时
                //                    echo "Timed out\n";
                $errorMessage = sprintf("consumer.kafka.message.timeout");
                $this->getLog()->info($errorMessage, [
                    '$message' => $message,
                    'errstrs' => $message->errstr(),
                ]);
                KafkaMessageException::kafkaMessageError($message->errstr(), $message->err);
                break;
            default:
                $errorMessage = sprintf("consumer.kafka.message.error");
                $this->getLog()->error($errorMessage, [
                    '$message' => $message,
                    'errstrs' => $message->errstr(),
                ]);
                KafkaMessageException::kafkaMessageError($message->errstr(), $message->err);
                break;
        }
        $event = Event::jsonToEvent($payload);
        try {
            nowRetryLabel:
            return $this->consumeEvent($event);
        } catch (EventConsumeFailException $e) {
            switch ($e->getEventResult()->getRetryType()) {
                case EventResultInterface::RETRY_TYPE_NOW:
                    $retryEvent = RetryEvent::toRetryEvent($event);
                    if ($e->getEventResult()->getRetryCount() > $retryEvent->getRetryCount()) {
                        $retryEvent->incrementRetryCount();
                        $event = $retryEvent->toEvent();
                        goto nowRetryLabel;
                    }
                    break;
                case EventResultInterface::RETRY_TYPE_APPLICATION_TOPIC:
                    $retryEvent = RetryEvent::toRetryEvent($event);
                    if ($e->getEventResult()->getRetryCount() > $retryEvent->getRetryCount()) {
                        $retryEvent->incrementRetryCount();
                        $event = $retryEvent->toEvent();
                        goto nowRetryLabel;
                    }
                    break;
                default:
                    throw $e;
                    break;
            }
            $message = sprintf("consumer.kafka.message.eventResult.isFail");
            $errorData = [
                '$event' => $e->getEvent(),
                '$eventResult' => $e->getEventResult(),
            ];
            $this->getConsumeFailLog()->error($message, $errorData);
            $this->getLog()->error($message, $errorData);
            EventConsumeFailRetryCountMaximumException::eventConsumeFailRetryCountMaximum('', $e->getEvent(), $e->getEventResult());
        }
    }
    
    /**
     * @param EventInterface $event
     * @return array [$event, $eventResult]
     * @throws EventConsumeFailException
     * @throws EventResultInstanceException
     * @throws \Zwei\Emq\Exception\BaseException
     */
    protected function consumeEvent(EventInterface $event) {
        // 3. 获取事件，如果事件不存在跑出事件EventNotFound
        // 4. 执行事件
        // 5. 检测事件返回值是不是EventResultInterface的示例，不是记录失败日志
        // 6. 检测如果执行失败需要退出程序
        // 7. 如果执行事件失败就记录失败日志
    
        
        $callback = $this->getConsumeEvent($event->getName());
        if (is_null($callback)) {
            $message = sprintf("consumer.kafka.message.eventResult.isFailExit");
            $this->getLog()->error($message, ['$callback' => $callback]);
            NotConfigEventCallbackException::notConfigEventCallback();
        }
    
        if (!is_callable($callback)) {
            $message = sprintf("consumer.kafka.message.eventCallback.configError");
            $this->getConsumeFailLog()->error($message, ['$callback' => $callback]);
            $this->getLog()->error($message, ['$callback' => $callback]);
            EventCallbackConfigException::eventCallbackConfig();
        }
    
        $eventResult = call_user_func_array($callback, [$event]);
        // 5. 检测事件返回值是不是EventResultInterface的实例，记录失败和异常日志
        if (!($eventResult instanceof EventResultInterface)) {
            $message = sprintf("consumer.kafka.message.eventResult.instanceError");
            $this->getConsumeFailLog()->error($message, ['$eventResult' => $eventResult]);
            $this->getLog()->error($message, ['$eventResult' => $eventResult]);
            EventResultInstanceException::eventResultInstance();
        }
    
        // 6. 检测如果执行失败需要退出程序
        if ($eventResult->isFailExit()) {
            $message = sprintf("consumer.kafka.message.eventResult.isFailExit");
            $this->getConsumeFailLog()->error($message, ['$eventResult' => $eventResult]);
            $this->getLog()->error($message, ['$eventResult' => $eventResult]);
            die();
        }
        // 7. 如果执行事件失败就记录失败日志
        if (!$eventResult->isSuccess()) {
            $message = sprintf("consumer.kafka.message.eventResult.isFail");
            $this->getConsumeFailLog()->error($message, ['$eventResult' => $eventResult]);
            $this->getLog()->error($message, ['$eventResult' => $eventResult]);
            EventConsumeFailException::eventConsumeFail('', $event, $eventResult);
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
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                // return $this->consumerQueue->commit($message);
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:// 没有消息
                //                    echo "No more messages; will wait for more\n";
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:// 超时
                //                    echo "Timed out\n";
                
                break;
            default:
                
                break;
        }
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
            $message = $this->pop();
            try {
                list($event, $eventResult) = $this->consume($message);
                $this->sendSuccessEvent($event, $eventResult);
            } catch (KafkaMessageException $e) {// 没有配置事件回调就直接删除
    
            } catch (NotConfigEventCallbackException $e) {// 没有配置事件回调就直接删除
            
            } catch (EventCallbackConfigException $e) {// 事件回调失败
            
            } catch (EventConsumeFailException $e) {// 超过最大重试次数
            
            } catch (EventConsumeFailRetryCountMaximumException $e) {// 超过最大重试次数
    
            } catch (EventResultInstanceException $e) {// 事件回调失败
    
            } catch (ConsumerBaseException $e) {// 消费者异常
                throw $e;
            } finally {
                $this->del($message);
            }
            
        }
    }
}
