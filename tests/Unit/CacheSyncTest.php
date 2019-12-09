<?php


namespace Tests\Unit;


use Mvaliolahi\CacheSync\CacheSync;
use Mvaliolahi\CacheSync\Drivers\ArrayDriver;
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

         $this->assertNotEmpty((new CacheSync(ArrayDriver::class))->data($data));
    }

    /**
     * @expectedException \Mvaliolahi\CacheSync\Exceptions\CacheSyncDriverNotSupportException
     */
    public function it_Should_trow_exception_if_driver_does_not_implement_contract()
    {
        (new CacheSync(null));
    }
}