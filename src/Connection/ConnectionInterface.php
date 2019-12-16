<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 10:54
 */
namespace Zwei\Emq\Connection;

use Zwei\Emq\Config\ProducerConfig;
use Zwei\Emq\Consumer\Config\ConsumerConfig;
use Zwei\Emq\Consumer\ConsumerInterface;
use Zwei\Emq\Producer\ProducerInterface;

interface ConnectionInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return array
     */
    public function getConfig();
    
    /**
     * @return mixed
     */
    public function connection();
    
    /**
     * @param ConsumerConfig $config
     * @return ConsumerInterface
     */
    public function createConsumer(ConsumerConfig $config);
    
    /**
     * @param ProducerConfig $config
     * @return ProducerInterface
     */
    public function createProducer(ProducerConfig $config);
}
