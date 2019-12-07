<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 11:41
 */

namespace Zwei\Emq\Consumer;



use Zwei\Emq\Config\ConsumerConfig;

class ConsumerManagerBuilder
{
    /**
     * @var ConsumerManager
     */
    protected $consumerManager;
    
    public function __construct()
    {
        $this->consumerManager = new ConsumerManager();
    }
    
    /**
     * @return ConsumerManager
     */
    public function getConsumerManager()
    {
        return $this->consumerManager;
    }
    
    public function addConsumerFromConfig(ConsumerConfig $config)
    {
    }

}
