<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-12-07
 * Time: 15:09
 */

namespace Zwei\Emq\Config;


class Arr
{
    /**
     * 获取数组中指定key的值
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (! is_array($array) || ! array_key_exists($segment, $array)) {
                return static::closureValue($default);
            }
            
            $array = $array[$segment];
        }
        
        return $array;
    }
    
    /**
     * 设置数组值
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        
        $keys = explode('.', $key);
        
        while (count($keys) > 1) {
            $key = array_shift($keys);
            
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            
            $array = &$array[$key];
        }
        
        $array[array_shift($keys)] = $value;
        
        return $array;
    }
    
    /**
     * 添加数组值
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public static function add($array, $key, $value)
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        
        return $array;
    }
    
    /**
     * 移除数组中的值
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;
        
        $keys = (array) $keys;
        
        if (count($keys) === 0) {
            return;
        }
        
        foreach ($keys as $key) {
            $parts = explode('.', $key);
            
            while (count($parts) > 1) {
                $part = array_shift($parts);
                
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    $parts = [];
                }
            }
            
            unset($array[array_shift($parts)]);
            
            // clean up after each pass
            $array = &$original;
        }
    }
    
    /**
     *
     * 检测数组中key是否存在
     *
     * @param  array   $array
     * @param  string  $key
     * @return bool
     */
    public static function has($array, $key)
    {
        if (empty($array) || is_null($key)) {
            return false;
        }
        
        if (array_key_exists($key, $array)) {
            return true;
        }
        
        foreach (explode('.', $key) as $segment) {
            if (! is_array($array) || ! array_key_exists($segment, $array)) {
                return false;
            }
            
            $array = $array[$segment];
        }
        
        return true;
    }
    
    /**
     * 获取数组第一个值
     *
     * @param  array  $array
     * @param  mixed  $default
     * @return mixed
     */
    public static function first($array, $default = null)
    {
        foreach ($array as $key => $value) {
            return $value;
        }
        return static::closureValue($default);
    }
    
    /**
     * 获取最后一个元素值
     * @param  array  $array
     * @param  mixed  $default
     * @return mixed
     */
    public static function last($array, $default = null)
    {
        foreach ($array as $key => $value) {
            $value = end($array);
            return $value;
        }
        return static::closureValue($default);
    }
    
    /**
     *
     * @param mixed $value
     * @return mixed
     */
    public static function closureValue($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
