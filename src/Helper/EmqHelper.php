<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-15
 * Time: 15:14
 */

namespace Zwei\Emq\Helper;


use Zwei\Emq\Connection\Config\ConnectionConfig;
use Zwei\Emq\Consumer\Config\ConsumerConfig;
use Zwei\Emq\Emq;
use Zwei\Emq\Logger\Config\LoggerConfig;

class EmqHelper
{
    
    /**
     * 添加一组日志配置
     *
     * @param Emq $emq
     * @param array $configs
     */
    public static function addLoggersFromArrayConfig(Emq $emq, array $configs)
    {
        foreach ($configs as $config) {
            $obj = new LoggerConfig($config);
            $emq->addLogger($obj);
        }
    }
    
    /**
     * 添加一组连接
     *
     * @param Emq $emq
     * @param array $configs
     */
    public static function addConnectionsFromArrayConfig(Emq $emq, array $configs)
    {
        foreach ($configs as $config) {
            $obj = new ConnectionConfig($config);
            $emq->addConnection($obj);
        }
    }
    
    /**
     * 添加一组消费者
     *
     * @param Emq $emq
     * @param array $configs
     */
    public static function addConsumersFromArrayConfig(Emq $emq, array $configs)
    {
        foreach ($configs as $config) {
            $obj = new ConsumerConfig($config);
            $emq->addConsumer($obj);
        }
    }
}
