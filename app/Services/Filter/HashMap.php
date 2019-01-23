<?php

namespace App\Services\Filter;

/**
 * Class HashMap.
 *
 * @author v_haodouliu <haodouliu@gmail.com>
 */
class HashMap
{
    /**
     * 哈希表变量.
     *
     * @var array|null
     */
    protected $hashTable = [];

    /**
     * 向HashMap中添加一个键值对.
     *
     * @param $key
     * @param $value
     *
     * @return mixed|null
     */
    public function put($key, $value)
    {
        if (!array_key_exists($key, $this->hashTable)) {
            $this->hashTable[$key] = $value;

            return null;
        }

        $_temp = $this->hashTable[$key];

        $this->hashTable[$key] = $value;

        return $_temp;
    }

    /**
     * 根据key获取对应的value.
     *
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->hashTable)) {
            return $this->hashTable[$key];
        }

        return null;
    }

    /**
     * 删除指定key的键值对.
     *
     * @param $key
     *
     * @return mixed|null
     */
    public function remove($key)
    {
        $temp_table = [];

        if (array_key_exists($key, $this->hashTable)) {
            $tempValue = $this->hashTable[$key];

            while ($curValue = current($this->hashTable)) {
                if (!(key($this->hashTable) == $key)) {
                    $temp_table[key($this->hashTable)] = $curValue;
                }

                next($this->hashTable);
            }

            $this->hashTable = null;
            $this->hashTable = $temp_table;

            return $tempValue;
        }
    }

    /**
     * 获取HashMap的所有键值.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->hashTable);
    }

    /**
     * 获取HashMap的所有value值.
     *
     * @return array
     */
    public function values()
    {
        return array_values($this->hashTable);
    }

    /**
     * 将一个HashMap的值全部put到当前HashMap中.
     *
     * @param HashMap $map
     *
     * @return bool
     */
    public function putAll($map)
    {
        if (!$map->isEmpty() && $map->size() > 0) {
            $keys = $map->keys();

            foreach ($keys as $key) {
                $this->put($key, $map->get($key));
            }
        }

        return true;
    }

    /**
     * 移除HashMap中所有元素.
     *
     * @return bool
     */
    public function removeAll()
    {
        $this->hashTable = null;

        return true;
    }

    /**
     * 判断HashMap中是否包含指定的值.
     *
     * @param $value
     *
     * @return bool
     */
    public function containsValue($value)
    {
        while ($curValue = current($this->hashTable)) {
            if ($curValue == $value) {
                return true;
            }

            next($this->hashTable);
        }

        return false;
    }

    /**
     * 判断HashMap中是否包含指定的键key.
     *
     * @param $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->hashTable);
    }

    /**
     * 获取HashMap中元素个数.
     *
     * @return int
     */
    public function size()
    {
        return count($this->hashTable);
    }

    /**
     * 判断HashMap是否为空.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return 0 == count($this->hashTable);
    }
}
