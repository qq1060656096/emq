<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 23:35
 */
namespace Zwei\Emq\tests;

use Zwei\Emq\Connection\Config\ConnectionConfig;
use Zwei\Emq\Emq;
use Zwei\Emq\Logger\Config\FileLoggerConfig;

class EmqTest extends \PHPUnit\Framework\TestCase
{
    /**
     * 测试日志管理
     */
    public function testLoggerManager()
    {
        $emq = new Emq();
        $configArr = [
            'name' => 'file',
            'driver' => 'file',
            'file' => __DIR__.'/tmp/file.log',
        ];
        $config = new FileLoggerConfig($configArr);
        $emq->addLogger($config);
        $emq->getLoggerManager()->get('file')->info('test', ['s' => $config]);
        $this->assertTrue(true);
    }
    
    /**
     * 测试kafka自动驱动
     */
    public function testConnectionManager()
    {
        $emq = new Emq();
        $config = new ConnectionConfig([
            'name' => 'kafkaAutoCommit',
            'driver' => 'kafkaAutoCommit',
        ]);
        $driver = $config->getDriver();
        $connection = $emq->getConnectionManager()->makeDriver($driver, $config);
        $emq->getConnectionManager()->add($connection);
        $this->assertEquals(1, count($emq->getConnectionManager()->getAll()));
    }
}
