<?php


namespace Mvaliolahi\CacheSync;


use Mvaliolahi\CacheSync\Contracts\SyncCacheDriver;
use Mvaliolahi\CacheSync\Exceptions\CacheSyncDriverNotSupportException;

/**
 * Class CacheSync
 * @package Mvaliolahi\CacheSync
 */
class CacheSync
{
    /**
     * @var SyncCacheDriver
     */
    protected $driver;

    /**
     * @var string
     */
    protected $identifierSeparator = '@';

    /**
     * @var null
     */
    private $data = null;

    /**
     * CacheSync constructor.
     * @param SyncCacheDriver | mixed $driver
     * @throws CacheSyncDriverNotSupportException
     */
    public function __construct($driver)
    {
        if (is_string($driver)) {
            $driver = new $driver;
        }

        if (!$driver instanceof SyncCacheDriver) {
            throw  new CacheSyncDriverNotSupportException(
                'Cache driver should implements Mvaliolahi\CacheSync\Contracts\CacheSyncDriver'
            );
        }

        $this->driver = $driver;
    }

    /**
     * @param $data
     * @return $this
     */
    public function data(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $key
     * @param $given
     * @return $this
     */
    public function change($key, $given)
    {
        if ($this->isNotNested($key)) {

            if (is_array($given)) {
                $this->data[$key] = array_replace($this->data[$key], $given);
            } else {
                $this->data[$key] = $given;
            }

            return $this;
        }

        [$isExist, $index] = $this->existsKeyInsideData($key);

        if ($isExist) {
            $this->updateData($key, $given, $index);
        }

        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    private function isNotNested($key): bool
    {
        return count($this->convertDotToKey($key)) == 1;
    }

    /**
     * @param $mainKey
     * @return array
     */
    private function convertDotToKey($mainKey): array
    {
        return explode('.',
            explode($this->identifierSeparator, $mainKey)[0]
        );
    }

    /**
     * @param $key
     * @return array
     */
    private function existsKeyInsideData($key): array
    {
        [$identifier, $needleValue] = $this->getIdentifierAndNeedleValue($key);

        $needleIndex = null;
        $exits = false;
        foreach (eval($this->getNeedleKeys($key)) as $index => $item) {
            if ($item[$identifier] == $needleValue) {
                $exits = true;
                $needleIndex = $index;
            }
        }
        return [$exits, $needleIndex];
    }

    /**
     * @param $key
     * @return array
     */
    private function getIdentifierAndNeedleValue($key): array
    {
        return explode('=', explode($this->identifierSeparator, $key)[1]);
    }

    /**
     * @param $mainKey
     * @param null $append
     * @return mixed | array
     */
    private function getNeedleKeys($mainKey, $append = null)
    {
        $keys = $this->convertDotToKey($mainKey);

        $query = "";
        foreach ($keys as $key => $value) {
            $query .= "['$value']";
        }
        $query = 'this->data' . $query;

        return ("return $$query$append;");
    }

    /**
     * @param $key
     * @param $given
     * @param $needleIndex
     */
    private function updateData($key, $given, $needleIndex): void
    {
        $combinedData = array_replace(
            eval($this->getNeedleKeys($key, "[$needleIndex]")),
            $given
        );

        $givenJson = json_encode($combinedData);
        eval($this->getNeedleKeys($key, "[$needleIndex] =  (array)json_decode('$givenJson')"));
    }

    /**
     * @return array | null
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * @param $key
     * @return CacheSync
     */
    public function persisTo($key)
    {
        $this->driver->set($key, $this->data);

        return $this;
    }

    /**
     * @return mixed|SyncCacheDriver
     */
    public function driver()
    {
        return $this->driver;
    }
}