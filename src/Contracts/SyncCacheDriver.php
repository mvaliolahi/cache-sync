<?php


namespace Mvaliolahi\CacheSync\Contracts;


/**
 * Interface SyncCacheDriver
 * @package Mvaliolahi\CacheSync\Contracts
 */
interface SyncCacheDriver
{
    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);
}