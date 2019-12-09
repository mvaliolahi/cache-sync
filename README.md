## Cache-Sync

[![Latest Stable Version](https://poser.pugx.org/mvaliolahi/cache-sync/v/stable)](https://packagist.org/packages/mvaliolahi/cache-sync)
[![Total Downloads](https://poser.pugx.org/mvaliolahi/cache-sync/downloads)](https://packagist.org/packages/mvaliolahi/cache-sync)
[![Build Status](https://travis-ci.org/mvaliolahi/cache-sync.svg?branch=master)](https://travis-ci.org/mvaliolahi/cache-sync)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan) 
<!-- [![codecov](https://codecov.io/gh/mvaliolahi/cache-sync/branch/master/graph/badge.svg)](https://codecov.io/gh/mvaliolahi/cache-sync) --> 

synchronize cache data using elegant syntax!

###### Install
```bash
    composer require mvaliolahi/cache-sync
```

###### Setup
```php            
    $cacheSync =  new CacheSync(ArrayDriver::class);
```            


- note: ArrayDriver can be replaced with any implementation of 'Mvaliolahi\CacheSync\Contracts\CacheSyncDriver'.

###### Update single key inside array
```php
        $data = [
            'name' => 'Meysam Valiolahi',
            'email' => 'mvaliolahi@gmail.com',
        ];

        $updatedData = $this->cacheSync
            ->data($data)
            ->change('name', 'Sohrab Valiolahi')
            ->change('email', 'sohrab_valiolahi@gmail.com')
            ->get()
```            
            
###### Update Nested key inside array
```php
        $data = [
            'data' => [
                'houses' => [
                    [
                        'id' => 101,
                        'location' => 'Dubai'
                    ],
                    [
                        'id' => 102,
                        'location' => 'Germany'
                    ]
                ]
            ]
        ];
        
        $this->cacheSync
            ->data($data)
            ->change('data.houses@id=101', [
                'location' => 'Iran'
            ])
            ->get()        
```                  
            
###### Persis changes
```php  
    $cacheSync->persistTo('users:user.1');
```                        