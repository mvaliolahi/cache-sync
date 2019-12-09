## Cache-Sync

###### Setup

    $cacheSync =  new CacheSync(ArrayDriver::class);
    
    * note: ArrayDriver implements CacheSyncDriver 


###### Update single key inside array

        $data = [
            'name' => 'Meysam Valiolahi',
            'email' => 'mvaliolahi@gmail.com',
        ];

        $updatedData = $this->cacheSync
            ->data($data)
            ->change('name', 'Sohrab Valiolahi')
            ->change('email', 'sohrab_valiolahi@gmail.com')
            ->get()
            
            
###### Update Nested key inside array

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
            ],)
            ->get()        
            
            
###### Persis changes

    $cacheSync->persistTo('users:user.1');
            