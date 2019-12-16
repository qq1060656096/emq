<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 16:40
 */
namespace Zwei\Emq\Helper;

use Zwei\Emq\Config\Arr;

trait ManagerTrait
{
    protected $data;
    
    /**
     * 添加，注意存在不会被覆盖
     *
     * @param string $name
     * @param string $value
     */
    protected function addRaw($name, $value)
    {
        $this->data = Arr::add($this->data, $name, $value);
    }
    
    /**
     * 设置值，值会被覆盖
     *
     * @param string $name
     * @param string $value
     */
    protected function setRaw($name, $value)
    {
        Arr::set($this->data, $name, $value);
    }
    
    /**
     * 删除
     *
     * @param string $name
     * @return bool
     */
    protected function removeRaw($name)
    {
        $bool = Arr::has($this->data, $name);
        if (!$bool) {
            return $bool;
        }
        Arr::forget($this->data, $name);
        return $bool;
    }
    
    /**
     * 获取
     *
     * @param string $name
     * @return mixed
     */
    protected function getRaw($name)
    {
        return Arr::get($this->data, $name);
    }
    
    public function getAll()
    {
        return Arr::get($this->data, null);
    }
}
