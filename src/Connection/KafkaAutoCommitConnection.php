<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:03
 */

namespace Zwei\Emq\Connection;

use RdKafka\Conf;
use RdKafka\KafkaConsumer as RdKafkaConsumer;
use RdKafka\Message;
use Zwei\Emq\Config\ProducerConfig;
use Zwei\Emq\Consumer\Config\ConsumerConfig;
use Zwei\Emq\Consumer\KafkaAutoCommitConsumer;

class KafkaAutoCommitConnection extends KafkaAbstractConnection
{
    /**
     * @return Conf
     */
    public function connection()
    {
        $rdKafkaConfig = parent::connection();
        $rdKafkaConfig->set('enable.auto.commit', 'true');
        $rdKafkaConfig->set('offset.store.method', 'broker');// offset保存在broker上
        $rdKafkaConfig->set('metadata.broker.list', implode(',', $this->getConfig()->getBrokers()));
        return $rdKafkaConfig;
    }
    
    
    /**
     * @param ConsumerConfig $config
     * @return KafkaAutoCommitConsumer $consumerQueue
     */
    public function createConsumer(ConsumerConfig $config)
    {
        $consumer = new KafkaAutoCommitConsumer($config);
        $rdKafkaConfig = $this->connection();
        $rdKafkaConfig->set('group.id', $config->getQueueName()); //定义消费组
        $this->consumerRebalance($rdKafkaConfig, $consumer);
        $this->rdKafkaErrorCb($rdKafkaConfig, $consumer);
        $consumerQueue = new RdKafkaConsumer($rdKafkaConfig);
        $consumerQueue->subscribe($config->getAppNameTopics());
        $consumer->setConsumerQueue($consumerQueue);
        return $consumer;
    }
    
    /**
     * 消费者重连
     *
     * @param KafkaAutoCommitConsumer $consumer
     */
    public function reconnectionConsumerDemo(KafkaAutoCommitConsumer $consumer)
    {
        $rdKafkaConfig = $this->connection();
        $this->consumerRebalance($rdKafkaConfig, $consumer);
        $this->rdKafkaErrorCb($rdKafkaConfig, $consumer);
        $consumerQueue = new RdKafkaConsumer($rdKafkaConfig);
        $consumerQueue->subscribe($consumer->getConfig()->getAppNameTopics());
        $consumer->setConsumerQueue($consumerQueue);
    }
    
    public function createProducer(ProducerConfig $config)
    {
        // TODO: Implement createProducer() method.
    }
}
