<?php


namespace Mvaliolahi\CacheSync\Drivers;


use Mvaliolahi\CacheSync\Contracts\SyncCacheDriver;

/**
 * Class ArrayDriver
 * @package Tests\Drivers
 */
class ArrayDriver implements SyncCacheDriver
{
    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->storage[md5($key)];
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        $this->storage[md5($key)] = $value;

        return $this;
    }
}