<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ShippingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Elektronik', 'Aksesoris', 'Peralatan Rumah'] as $name) {
<<<<<<< HEAD
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        $services = [
            ['courier' => 'JNE', 'service' => 'REG', 'eta' => '2-4 hari', 'cost' => 18000],
            ['courier' => 'J&T', 'service' => 'EZ', 'eta' => '2-4 hari', 'cost' => 17000],
            ['courier' => 'SiCepat', 'service' => 'REG', 'eta' => '2-3 hari', 'cost' => 16000],
=======
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        $services = [
            [
                'courier' => 'JNE',
                'service' => 'REG',
                'eta' => '2-4 hari',
                'cost' => 18000
            ],
            [
                'courier' => 'J&T',
                'service' => 'EZ',
                'eta' => '2-4 hari',
                'cost' => 17000
            ],
            [
                'courier' => 'SiCepat',
                'service' => 'REG',
                'eta' => '2-3 hari',
                'cost' => 16000
            ],
>>>>>>> milestone/03-inventory-shipping
        ];

        foreach ($services as $service) {
            ShippingService::firstOrCreate(
<<<<<<< HEAD
                ['courier' => $service['courier'], 'service' => $service['service']],
=======
                [
                    'courier' => $service['courier'],
                    'service' => $service['service']
                ],
>>>>>>> milestone/03-inventory-shipping
                $service + ['is_active' => true]
            );
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> milestone/03-inventory-shipping
