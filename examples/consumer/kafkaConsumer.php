<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-13
 * Time: 14:29
 */
include_once dirname(dirname(__DIR__)).'/vendor/autoload.php';

$loggersConfig = [
    [
        'name' => 'fileLog',
        'driver' => 'fileLog',
        'file' => __DIR__.'/tmp/file1.log',
    ],
    [
        'name' => 'consumeFailFileLog',
        'driver' => 'consumeFailFileLog',
        'file' => __DIR__.'/tmp/file1.log',
    ],
];
$connectionsConfig = [
    'kafkaAutoCommitConnection' => [
        'name' => 'kafkaAutoCommitConnection',
        'driver' => 'kafkaAutoCommit',
        
    ],
];


$consumersConfig = [
    'kafkaAutoCommitConsumer' => [
        'name' => 'kafkaAutoCommitConsumer',
        'queueName' => 'test',
        'appNameTopics' => [
            'test'
        ],
        'connection' => 'kafkaAutoCommitConnection',
        'log' => 'fileLog',
        'consumeEvents' => [
        
        ],
        'consumeFailLog' => 'consumeFailLog',
        'timeoutMs' => 2000,
    ],
];
$emq = new \Zwei\Emq\Emq();
\Zwei\Emq\Helper\EmqHelper::addLoggersFromArrayConfig($emq, $loggersConfig);
\Zwei\Emq\Helper\EmqHelper::addConnectionsFromArrayConfig($emq, $connectionsConfig);
\Zwei\Emq\Helper\EmqHelper::addConsumersFromArrayConfig($emq, $consumersConfig);
$emq->getConsumerManager()->get("kafkaAutoCommitConsumer")->scheduleTaskConsume();
