<?php


namespace Tests\Feature;


use Mvaliolahi\CacheSync\CacheSync;
use Tests\TestCase;

/**
 * Class CacheSyncTest
 * @package Tests\Feature
 */
class CacheSyncTest extends TestCase
{
    /**
     * @var CacheSync
     */
    protected $cacheSync;

    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();
        $this->cacheSync = new CacheSync();
    }

    /**
     * @test
     */
    public function it_should_change_entire_key()
    {
        $data = [
            'name' => 'Meysam Valiolahi',
            'email' => 'mvaliolahi@gmail.com',
        ];;

        $this->assertEquals(
            [
                'name' => 'Sohrab Valiolahi',
                'email' => 'sohrab@gmail.com',
            ],
            $this->cacheSync
                ->data($data)
                ->change('name', 'Sohrab Valiolahi')
                ->change('email', 'sohrab@gmail.com')
                ->get()
        );
    }

    /**
     * @test
     */
    public function it_should_change_nested_keys()
    {
        $data = [
            'data' => [
                'houses' => [
                    [
                        'id' => 90,
                        'location' => 'Dubai'
                    ],
                    [
                        'id' => 100,
                        'location' => 'Dubai - 2'
                    ],
                    [
                        'id' => 101,
                        'location' => 'American'
                    ],
                    [
                        'id' => 102,
                        'location' => 'Germany'
                    ]
                ]
            ]
        ];

        $this->assertEquals(
            [
                'data' => [
                    'houses' => [
                        [
                            'id' => 90,
                            'location' => 'Dubai'
                        ],
                        [
                            'id' => 100,
                            'location' => 'Dubai - 2'
                        ],
                        [
                            'id' => 101,
                            'location' => 'Iran'
                        ],
                        [
                            'id' => 102,
                            'location' => 'Germany'
                        ]
                    ]
                ]
            ],
            $this->cacheSync
                ->data($data)
                ->change('data.houses@id=101', [
                    'id' => 101,
                    'location' => 'Iran'
                ],)
                ->get()
        );
    }

    /**
     * @skip
     */
    public function it_should_persist_modified_data_using_cache_key()
    {
    }
}