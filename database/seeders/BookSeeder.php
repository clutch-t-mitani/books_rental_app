<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::create([
            'name' => 'マスカレード',
            'author' => '東の',
        ]);
        Book::create([
            'name' => '告白',
            'author' => '田中',
        ]);
        Book::create([
            'name' => 'ばっや',
            'author' => '今田',
        ]);
        Book::create([
            'name' => 'ほぐるま',
            'author' => '板尾',
        ]);
        Book::create([
            'name' => 'コインローっかー',
            'author' => 'ただ',
        ]);
        Book::create([
            'name' => 'ストライク',
            'author' => 'らああ子供',
        ]);
        Book::create([
            'name' => '名たん天気',
            'author' => '米',
        ]);
        Book::create([
            'name' => 'カナリア',
            'author' => 'あゆ',
        ]);
        Book::create([
            'name' => 'Live OR Die',
            'author' => '今田',
        ]);
        Book::create([
            'name' => '田中角栄の歴史',
            'author' => '田中心臓',
        ]);
        Book::create([
            'name' => 'めっちゃイケ',
            'author' => '岡村隆史',
        ]);
        Book::create([
            'name' => '猫ふんずあ',
            'author' => 'どうよう',
        ]);
    }
}
