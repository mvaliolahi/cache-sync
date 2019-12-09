<?php


namespace Tests\Feature;


use Mvaliolahi\CacheSync\CacheSync;
use Mvaliolahi\CacheSync\Drivers\ArrayDriver;
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
        $this->cacheSync = new CacheSync(ArrayDriver::class);
    }

    /**
     * @test
     */
    public function it_should_change_entire_key()
    {
        $data = [
            'name' => 'Meysam Valiolahi',
            'email' => 'mvaliolahi@gmail.com',
        ];

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
                        'location' => 'Dubai -4'
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
     * @test
     */
    public function it_should_replace_specific_field_for_nested_key()
    {
        $data = [
            'data' => [
                'houses' => [
                    'records' => [
                        'trends' => [
                            [
                                'id' => 1,
                                'city' => 'Tehran'
                            ],
                            [
                                'id' => 2,
                                'city' => 'Borujerd',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals(
            [
                'data' => [
                    'houses' => [
                        'records' => [
                            'trends' => [
                                [
                                    'id' => 1,
                                    'city' => 'Yazd',
                                ],
                                [
                                    'id' => 2,
                                    'city' => 'Borujerd',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            $this->cacheSync->data($data)
                ->change('data.houses.records.trends@city=Tehran', [
                    'city' => 'Yazd',
                ])
                ->get()
        );
    }

    /**
     * @test
     */
    public function it_should_replace_specific_field_for_single_key()
    {
        $data = [
            'name' => 'Meysam Valiolahi',
            'favorites' => [
                'Book',
                'Music',
            ],
        ];

        $this->assertEquals(
            [
                'name' => 'Meysam Valiolahi',
                'favorites' => [
                    'Programming',
                    'Music',
                ],
            ],
            $this->cacheSync->data($data)
                ->change('favorites', [
                    'Programming'
                ])
                ->get()
        );
    }

    /**
     * @test
     */
    public function it_should_persist_data_after_changed()
    {
        $data = [
            'name' => 'Meysam Valiolahi',
            'age'  => 28,
        ];

        $this->cacheSync
            ->data($data)
            ->change('name', 'Sohrab Valiolahi')
            ->change('age', 2)
            ->persisTo('users:user.1');

        $this->assertEquals(
            [
                'name' => 'Sohrab Valiolahi',
                'age'  => 2
            ],
            $this->cacheSync
                ->driver()
                ->get('users:user.1')
        );
    }
}