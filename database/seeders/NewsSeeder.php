<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $news = [
            [
                'title' => 'helo',
                'image' => 'default.png',
                'content' => null,
            ],
            [
                'title' => 'helo 2',
                'image' => null,
                'content' => null,
            ],
        ];

        News::insert($news);
    }
}
