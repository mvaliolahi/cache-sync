<?php

namespace Tests;


use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase
 * @package Tests
 */
class TestCase extends PHPUnitTestCase
{
    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @param $method
     * @param $object
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function callMethod($method, $object, $args = [])
    {
        $translator = new \ReflectionClass(get_class($object));
        $method = $translator->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    /**
     * @param $data
     */
    public function dd($data)
    {
        die(var_dump($data));
    }
}