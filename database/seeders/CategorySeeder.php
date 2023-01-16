<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => '小説',
        ]);
        Category::create([
            'name' => '漫画',
        ]);
        Category::create([
            'name' => '新刊',
        ]);
        Category::create([
            'name' => '歴史',
        ]);
        Category::create([
            'name' => 'ビジネス',
        ]);
        Category::create([
            'name' => 'ベストセラー',
        ]);
        Category::create([
            'name' => 'ホラー',
        ]);
        Category::create([
            'name' => 'フィクション',
        ]);
    }
}
