<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 21:12
 */
namespace Zwei\Emq;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Zwei\Emq\Config\ConnectionConfig;
use Zwei\Emq\Config\ConsumerConfig;
use Zwei\Emq\Connection\ConnectionManager;
use Zwei\Emq\Connection\KafkaConnection;
use Zwei\Emq\Consumer\ConsumerManager;
use Zwei\Emq\Logger\Config\LoggerConfig;
use Zwei\Emq\Logger\LoggerManager;
use Zwei\Emq\Logger\Config\FileLoggerConfig;

class Emq
{
    /**
     * @var ConnectionManager
     */
    protected $connectionManager;
    
    /**
     * @var ConsumerManager
     */
    protected $consumerManager;
    
    /**
     * @var LoggerManager
     */
    protected $loggerManager;
    
    public function __construct()
    {
        $this->init();
    }
    
    /**
     * 初始化
     */
    protected function init()
    {
        $this->connectionManager = new ConnectionManager();
        $this->consumerManager = new ConsumerManager();
        $this->loggerManager = new LoggerManager();
        $this->initConsumerDrivers();
        $this->initLoggerDrivers();
    }
    
    /**
     * 初始化驱动
     */
    protected function initConsumerDrivers()
    {
        // ****** kafka 驱动 start ******
        // 自动提交
        $this->getConnectionManager()->extend('kafkaAutoCommit', function (ConnectionConfig $config) {
            return new KafkaConnection($config);
        });
    
        // 手动同步提交
        $this->getConnectionManager()->extend('kafkaHandSyncCommit', function (ConnectionConfig $config) {
            return new KafkaConnection($config);
        });
    
        // 手动异步提交
        $this->getConnectionManager()->extend('kafkaHandASyncCommit', function (ConnectionConfig $config) {
            return new KafkaConnection($config);
        });
        // ****** kafka 驱动 end ******
    }
    
    /**
     * 初始化日志驱动
     */
    protected function initLoggerDrivers()
    {
        // 文件日志驱动
        $this->getLoggerManager()->extend('file', function (FileLoggerConfig $config) {
            $log = new Logger($config->getName());
            $log->pushHandler(new StreamHandler($config->getFile()));
            return $log;
        });
        
        // 消费者失败文件日志驱动
        $this->getLoggerManager()->extend('consumeFailFile', function (FileLoggerConfig $config) {
            $log = new Logger($config->getName());
            $log->pushHandler(new StreamHandler($config->getFile()));
            return $log;
        });
    }
    
    /**
     * @return ConnectionManager
     */
    public function getConnectionManager()
    {
        return $this->connectionManager;
    }
    
    /**
     * @return ConsumerManager
     */
    public function getConsumerManager()
    {
        return $this->consumerManager;
    }
    
    /**
     * @return LoggerManager
     */
    public function getLoggerManager()
    {
        return $this->loggerManager;
    }
    
    /**
     * 添加消费连接
     * @param ConnectionConfig $config
     */
    public function addConnection(ConnectionConfig $config)
    {
        $connection = $this->getConnectionManager()->makeDriver($config->getDriver(), $config);
        $this->getConnectionManager()->add($config->getName(), $connection);
    }
    
    /**
     * 添加消费者配置
     *
     * @param ConsumerConfig $config
     * @return $this
     */
    public function addConsumer(ConsumerConfig $config)
    {
        $connection = $config->getConnection();
        $consumer = $this->getConnectionManager()->get($connection)->createConsumer($config);
        $consumer->setConnectionManager($this->getConnectionManager());
        $consumer->setLoggerManager($this->getLoggerManager());
        $this->getConsumerManager()->add($config->getName(), $consumer);
        return $this;
    }
    
    /**
     * @param LoggerConfig $config
     * @return $this
     */
    public function addLogger(LoggerConfig $config)
    {
        $driver = $config->getDriver();
        $logger = $this->getLoggerManager()->makeDriver($driver, $config);
        $this->getLoggerManager()->set($logger);
        return $this;
    }
}
