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
use Zwei\Emq\Config\ConsumerConfig;
use Zwei\Emq\Config\ProducerConfig;
use Zwei\Emq\Connection\Config\ConnectionConfig;
use Zwei\Emq\Connection\Config\KafkaConnectionConfig;
use Zwei\Emq\Consumer\ConsumerInterface;
use Zwei\Emq\Consumer\KafkaAutoCommitConsumer;
use Zwei\Emq\Helper\Helper;
use Zwei\Emq\Producer\ProducerInterface;

abstract class KafkaAbstractConnection implements ConnectionInterface
{
    /**
     * @var ConnectionConfig
     */
    protected $config;
    
    public function __construct(ConnectionConfig $config)
    {
        $this->config = new KafkaConnectionConfig($config->getData());
    }
    
    public function getName()
    {
        return $this->getConfig()->getName();
    }
    
    /**
     * @return KafkaConnectionConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * @return Conf
     */
    public function connection()
    {
        $rdKafkaConfig = new Conf();
        $rdKafkaConfig = $this->setConf($rdKafkaConfig, $this->getConfig()->getOptions());
        return $rdKafkaConfig;
    }
    
    /**
     * 设置kafka配置
     * @param Conf $conf 配置实例
     * @param array $options 选项键值数组
     * @return Conf
     */
    protected function setConf(Conf $conf, array $options)
    {
        foreach ($options as $key => $value) {
            $conf->set($key, $value);
        }
        return $conf;
    }
    
    
    public function createProducer(ProducerConfig $config)
    {
        // TODO: Implement createProducer() method.
    }
    
    
    /**
     * 在均衡
     *
     * @param Conf $rdKafkaConf
     * @param KafkaAutoCommitConsumer $consumer
     */
    protected function consumerRebalance(Conf $rdKafkaConf, KafkaAutoCommitConsumer $consumer)
    {
        $rdKafkaConf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) use ($consumer) {
            $partitionsVar = Helper::varDump($partitions);
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    $consumer->getLog()->info("consumer.rdKafka.rebalance.assign", [$partitionsVar]);
                    $kafka->assign($partitions);
                    break;
                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $consumer->getLog()->info("consumer.rdKafka.rebalance.revoke", [$partitionsVar]);
                    $kafka->assign(NULL);
                    break;
                default:
                    $consumer->getLog()->error("consumer.rdKafka.rebalance.err", [$partitionsVar]);
                    throw new \Exception($err);
            }
        });
    }
    
    /**
     * 错误回调
     *
     * @param Conf $rdKafkaConf
     * @param ConsumerInterface|null $consumer
     * @param ProducerInterface|null $producer
     */
    protected function rdKafkaErrorCb(Conf $rdKafkaConf, ConsumerInterface $consumer = null, ProducerInterface $producer = null)
    {
        if ($consumer === null && $producer == null) {
            return;
        }
        $rdKafkaConf->setErrorCb(function ($kafka, $err, $reason) use ($consumer, $producer) {
            if ($consumer !== null) {
                $consumer->getLogger()->error("producer.setErrorCb.callback", [
                    '$kafka' => Helper::varDump($kafka),
                    '$err' => Helper::varDump($err),
                    '$errStr' => rd_kafka_err2str($err),
                    '$reason' => Helper::varDump($reason),
                ]);
            } else {
                $producer->getLogger()->error("producer.setErrorCb.callback", [
                    '$kafka' => Helper::varDump($kafka),
                    '$err' => Helper::varDump($err),
                    '$errStr' => rd_kafka_err2str($err),
                    '$reason' => Helper::varDump($reason),
                ]);
            }
        });
    }
    
    /**
     * 发送消息错误回调
     *
     * @param Conf $rdKafkaConf
     * @param ProducerInterface $producer
     */
    public function rdKafkaDrMsgCb(Conf $rdKafkaConf, ProducerInterface $producer)
    {
        $rdKafkaConf->setDrMsgCb(function ($kafka, $message) use ($producer) {
            if ($message->err) {
                // 消息发送失败
                // message permanently failed to be delivered
                $producer->getLogger()->error("producer.setDrMsgCb.report", [
                    '$kafka' => Helper::varDump($kafka),
                    '$message' => Helper::varDump($message),
                ]);
            } else {
                // message successfully delivered
            }
        });
    }
}
