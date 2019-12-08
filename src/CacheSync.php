<?php


namespace Mvaliolahi\CacheSync;


use Mvaliolahi\CacheSync\Exceptions\CacheIsNotSupportException;

/**
 * Class CacheSync
 * @package Mvaliolahi\CacheSync
 */
class CacheSync
{
    /**
     * @var string
     */
    protected $identifierSeparator = '@';

    /**
     * @var null
     */
    private $data = null;

    /**
     * @param $data
     * @return $this
     * @throws CacheIsNotSupportException
     */
    public function data($data)
    {
        $this->dataShouldBeArray($this->data = $data);

        return $this;
    }

    /**
     * @param $data
     * @return string
     * @throws CacheIsNotSupportException
     */
    private function dataShouldBeArray($data)
    {
        if (!is_array($data)) {
            throw new CacheIsNotSupportException('Only array can accept by cache sync.');
        }
    }

    /**
     * @param $key
     * @param $given
     * @return $this
     */
    public function change($key, $given)
    {
        if ($this->isNotNested($key)) {
            $this->data[$key] = $given;
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
        $givenJson = json_encode($given);
        eval($this->getNeedleKeys($key, "[$needleIndex] =  (array)json_decode('$givenJson')"));
    }

    /**
     * @return null
     */
    public function get()
    {
        return $this->data;
    }
}