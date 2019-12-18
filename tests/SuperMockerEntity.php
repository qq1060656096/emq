<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-09-26
 * Time: 23:04
 */

namespace Zwei\Emq\Tests;


class SuperMockerEntity
{
    protected $className = null;

    protected $methods = [];

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * 添加方法列表
     * @param array $methods
     */
    public function addMethods(array $methods)
    {
        $this->methods = array_merge($this->methods, $methods);
    }

}
