<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-18
 * Time: 10:18
 */

namespace Zwei\Emq\Tests\Consumer;


use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Zwei\Emq\Consumer\Config\ConsumerConfig;
use Zwei\Emq\Consumer\KafkaAutoCommitConsumer;
use Zwei\Emq\Event\Event;
use Zwei\Emq\Event\EventResult;
use Zwei\Emq\Event\EventResultAbstract;
use Zwei\Emq\Exception\Consumer\KafkaMessageException;
use Zwei\Emq\Exception\Consumer\NotConfigEventCallbackException;
use Zwei\Emq\Tests\SuperMockerEntity;
use Zwei\Emq\Tests\SuperMockerTrait;

class KafkaAutoCommitConsumerTest extends TestCase
{
    use SuperMockerTrait;
    
    /**
     * @param array|null $config
     * @param array $methods
     * @return KafkaAutoCommitConsumer
     */
    public function createMockerKafkaAutoCommitConsumer(array $config = null, $methods = [])
    {
        if ($config === null) {
            $config = [];
        }
        $consumerConfig = new ConsumerConfig($config);
        $entity = new SuperMockerEntity();
        $entity->setClassName(KafkaAutoCommitConsumer::class);
        $entity->setMethods([
            'getLog' => null,
            'getConsumeFailLog' => null,
        ]);
        $entity->addMethods($methods);
        return $this->createSuperMocker($entity, [$consumerConfig]);
    }
    
    /**
     * @param array $methods
     * @return Logger|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createMockLogger(array $methods = [])
    {
        $entity = new SuperMockerEntity();
        $entity->setClassName(Logger::class);
        $entity->setMethods([
            'info' => null,
            'error' => null,
        ]);
        $entity->addMethods($methods);
        return $this->createModelSuperMocker($entity);
    }
    
    /**
     * 测试消费没有消息日志
     *
     * @throws \Zwei\Emq\Exception\BaseException
     * @throws \Zwei\Emq\Exception\Consumer\EventConsumeFailRetryCountMaximumException
     * @throws \Zwei\Emq\Exception\Consumer\EventResultInstanceException
     */
    public function testConsumeKafkaMessageNoMoreMessages()
    {
        $thisObj = $this;
        $config = [];
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.noMoreMessage', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR__PARTITION_EOF;
        try {
            $consumer->consume($message);
        } catch (KafkaMessageException $e) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * 测试消费消息超时
     *
     * @throws \Zwei\Emq\Exception\BaseException
     * @throws \Zwei\Emq\Exception\Consumer\EventConsumeFailRetryCountMaximumException
     * @throws \Zwei\Emq\Exception\Consumer\EventResultInstanceException
     */
    public function testConsumeKafkaMessageTimeout()
    {
        $thisObj = $this;
        $config = [];
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.timeout', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR__TIMED_OUT;
        try {
            $consumer->consume($message);
        } catch (KafkaMessageException $e) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * 测试消费消息默认出错误
     *
     * @throws \Zwei\Emq\Exception\BaseException
     * @throws \Zwei\Emq\Exception\Consumer\EventConsumeFailRetryCountMaximumException
     * @throws \Zwei\Emq\Exception\Consumer\EventResultInstanceException
     */
    public function testConsumeKafkaMessageDefaultError()
    {
        $thisObj = $this;
        $config = [];
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.error', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $message = new \RdKafka\Message();
        $message->err = -999999999;
        try {
            $consumer->consume($message);
        } catch (KafkaMessageException $e) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * 测试事件成功
     * @throws \Zwei\Emq\Exception\BaseException
     * @throws \Zwei\Emq\Exception\Consumer\EventConsumeFailRetryCountMaximumException
     * @throws \Zwei\Emq\Exception\Consumer\EventResultInstanceException
     */
    public function testConsumeSuccess()
    {
        $thisObj = $this;
        $config = [
            'consumeEvents' => [
                'TEST_EVENT' => function(Event $event) use ($thisObj) {
                    $this->assertEquals('TEST_EVENT', $event->getName());
                    $this->assertEquals(1, $event->getData()['id']);
                    $eventResult = new EventResult();
                    $eventResult->setIsSuccess(true);
                    return $eventResult;
                }
            ],
        ];
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.noError', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
    
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $event = new Event('TEST_EVENT', ['id' => 1]);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = (string)$event;
        try {
            /* @var EventResultAbstract $eventResult*/
            list ($event, $eventResult) = $consumer->consume($message);
            $this->assertEquals(true, $eventResult->isSuccess());
        } catch (KafkaMessageException $e) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * 测试消费时没有配置事件异常
     *
     * @expectedException \Zwei\Emq\Exception\Consumer\NotConfigEventCallbackException
     */
    public function testConsumeKafkaMessageNotConfigEventCallbackException()
    {
        $thisObj = $this;
        $config = [
            'consumeEvents' => [
                'TEST_EVENT' => function(Event $event) use ($thisObj) {
                    $this->assertEquals('TEST_EVENT', $event->getName());
                    $this->assertEquals(1, $event->getData()['id']);
                    $eventResult = new EventResult();
                    $eventResult->setIsSuccess(true);
                    return $eventResult;
                }
            ],
        ];
        $logCount = 0;
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj, &$logCount) {
                $logCount ++;
                if ($logCount == 1) {
                    $thisObj->assertEquals('consumer.kafka.message.noError', $message);
                } else if ($logCount == 2) {
                    $thisObj->assertEquals('consumer.kafka.message.noConfig', $message);
                }
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
        ]);
    
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $event = new Event('TEST_EVENT_NO_CONFIG', ['id' => 1]);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = (string)$event;
        /* @var EventResultAbstract $eventResult*/
        list ($event, $eventResult) = $consumer->consume($message);
        $this->assertEquals(true, $eventResult->isSuccess());
        
    }
    
    
    /**
     * 测试消费时没有配置事件callback错误
     *
     * @expectedException \Zwei\Emq\Exception\Consumer\EventCallbackConfigException
     */
    public function testConsumeKafkaMessageEventCallbackConfigException()
    {
        $thisObj = $this;
        $config = [
            'consumeEvents' => [
                'TEST_EVENT' => 1
            ],
        ];
        $logCount = 0;
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj, &$logCount) {
                $logCount ++;
                if ($logCount == 1) {
                    $thisObj->assertEquals('consumer.kafka.message.noError', $message);
                }
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.configError', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.configError', $message);
            },
        ]);
        
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $event = new Event('TEST_EVENT', ['id' => 1]);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = (string)$event;
        /* @var EventResultAbstract $eventResult*/
        list ($event, $eventResult) = $consumer->consume($message);
        $this->assertEquals(true, $eventResult->isSuccess());
        
    }
    
    /**
     * 事件消费时事件返回值错误
     *
     * @expectedException Zwei\Emq\Exception\Consumer\EventResultInstanceException
     */
    public function testConsumeKafkaMessageEventResultInstanceException()
    {
        $thisObj = $this;
        $config = [
            'consumeEvents' => [
                'TEST_EVENT' => function(Event $event) use ($thisObj) {
                    $this->assertEquals('TEST_EVENT', $event->getName());
                    $this->assertEquals(1, $event->getData()['id']);
                    $eventResult = new EventResult();
                    $eventResult->setIsSuccess(true);
                    return null;
                }
            ],
        ];
        $logCount = 0;
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj, &$logCount) {
                $logCount ++;
                if ($logCount == 1) {
                    $thisObj->assertEquals('consumer.kafka.message.noError', $message);
                }
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.eventResult.instanceError', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.eventResult.instanceError', $message);
            },
        ]);
    
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $event = new Event('TEST_EVENT', ['id' => 1]);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = (string)$event;
        /* @var EventResultAbstract $eventResult*/
        list ($event, $eventResult) = $consumer->consume($message);
        $this->assertEquals(true, $eventResult->isSuccess());
    }
    
    /**
     * 事件消费时事件消费失败异常
     *
     * @expectedException Zwei\Emq\Exception\Consumer\EventConsumeFailException
     */
    public function testConsumeKafkaMessageEventConsumeFailException()
    {
        $thisObj = $this;
        $config = [
            'consumeEvents' => [
                'TEST_EVENT' => function(Event $event) use ($thisObj) {
                    $this->assertEquals('TEST_EVENT', $event->getName());
                    $this->assertEquals(1, $event->getData()['id']);
                    $eventResult = new EventResult();
                    $eventResult->setIsSuccess(false);
                    return $eventResult;
                }
            ],
        ];
        $logCount = 0;
        $getLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj, &$logCount) {
                $logCount ++;
                if ($logCount == 1) {
                    $thisObj->assertEquals('consumer.kafka.message.noError', $message);
                }
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.eventResult.isFail', $message);
            },
        ]);
        $getConsumeFailLog = $this->createMockLogger([
            'info' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('', $message);
            },
            'error' => function($message, array $context = array()) use($thisObj) {
                $thisObj->assertEquals('consumer.kafka.message.eventResult.isFail', $message);
            },
        ]);
        
        $methods = [
            'getLog' => $getLog,
            'getConsumeFailLog' => $getConsumeFailLog,
        ];
        $consumer = $this->createMockerKafkaAutoCommitConsumer($config, $methods);
        $event = new Event('TEST_EVENT', ['id' => 1]);
        $message = new \RdKafka\Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = (string)$event;
        /* @var EventResultAbstract $eventResult*/
        list ($event, $eventResult) = $consumer->consume($message);
        $this->assertEquals(true, $eventResult->isSuccess());
    }
}
