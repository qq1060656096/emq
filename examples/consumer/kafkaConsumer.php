<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-13
 * Time: 14:29
 *
 * php examples/consumer/kafkaConsumer.php 消费者名
 * php examples/consumer/kafkaConsumer.php kafkaAutoCommitConsumer
 */
include_once dirname(dirname(__DIR__)).'/vendor/autoload.php';

$consumerName = $argv[1];

$configs = include __DIR__.'/config/kafkaConsumer.config.php';
$emq = new \Zwei\Emq\Emq();
\Zwei\Emq\Helper\EmqHelper::addLoggersFromArrayConfig($emq, $configs['loggers']);
\Zwei\Emq\Helper\EmqHelper::addConnectionsFromArrayConfig($emq, $configs['connections']);
\Zwei\Emq\Helper\EmqHelper::addConsumersFromArrayConfig($emq, $configs['consumers']);
$emq->getConsumerManager()->get($consumerName)->scheduleTaskConsume();
