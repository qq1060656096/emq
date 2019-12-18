<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-17
 * Time: 23:58
 */
return [
    // ********** loggers start ***********
    'loggers' => [
        [
            'name' => 'kafkaAutoCommitConsumer.fileLog',
            'driver' => 'fileLog',
            'file' => __DIR__.'/log/file1.log',
        ],
        [
            'name' => 'kafkaAutoCommitConsumer.ConsumeFailFileLog',
            'driver' => 'fileLog',
            'file' => __DIR__.'/log/file2.log',
        ],
    ],
    // ********** loggers end ***********
    
    
    // ********** connections start ***********
    'connections' => [
        [
            'name' => 'kafkaAutoCommitConnection',
            'driver' => 'kafkaAutoCommit',
            'brokers' => [
                '199.199.199.199:9092'
            ],
        ],
    ],
    // ********** connections end ***********
    
    
    // ********** consumers start ***********
    'consumers' => [
        [
            'name' => 'kafkaAutoCommitConsumer',
            'queueName' => 'test',
            'appNameTopics' => [
                'test6'
            ],
            'connectionName' => 'kafkaAutoCommitConnection',
            'log' => 'kafkaAutoCommitConsumer.fileLog',
            'consumeEvents' => [
                'TEST' => function(\Zwei\Emq\Event\Event $event) {
                    var_dump($event);
                    $eventResult = new \Zwei\Emq\Event\EventResult();
                    return $eventResult;
                },
                'TEST_OK' => function(\Zwei\Emq\Event\Event $event) {
                    var_dump($event);
                    var_dump('ooooooooo');
                    var_dump('ooooooooo');
                    var_dump('ooooooooo');
                    var_dump('ooooooooo');
                    var_dump('ooooooooo');
                    var_dump('ooooooooo');
    
                    $eventResult = new \Zwei\Emq\Event\EventResult();
                    $eventResult->setIsSuccess(true);
                    return $eventResult;
                }
            ],
            'consumeFailLog' => 'kafkaAutoCommitConsumer.ConsumeFailFileLog',
            'timeoutMs' => 5000,
        ],
    ],
    // ********** consumers end ***********
];
