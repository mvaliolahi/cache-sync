<?php


namespace Tests\Unit;


use Mvaliolahi\CacheSync\CacheSync;
use Tests\TestCase;

/**
 * Class CacheSyncTest
 * @package Tests\Unit
 */
class CacheSyncTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_accept_array()
    {
        $data = [
            'name' => 'Meysam Valiolahi',
            'email' => 'mvaliolahi@gmail.com',
        ];

         $this->assertNotEmpty((new CacheSync)->data($data));
    }

    /**
     * @test
     * @expectedException \Mvaliolahi\CacheSync\Exceptions\CacheIsNotSupportException
     */
    public function it_should_trows_and_exception_when_object_passed()
    {
        $obj = (object)['name' => 'Meysam Valiolahi'];
        $cacheSync = (new CacheSync)->data($obj);
    }
}