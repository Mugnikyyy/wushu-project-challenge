<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            [
                'title' => null,
                'media' => 'default.png',
                'parent_id' => null,
                'description' => 'sdads',
                'created_at' => now()
            ],
        ];

        Image::insert($images);
    }
}
