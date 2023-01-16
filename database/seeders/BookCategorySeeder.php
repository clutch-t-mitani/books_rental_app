<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookCategory::create([
            'book_id' => 6,
            'category_id' => 5,
        ]);
        BookCategory::create([
            'book_id' => 7,
            'category_id' => 7,
        ]);
        BookCategory::create([
            'book_id' => 8,
            'category_id' => 8,
        ]);
        BookCategory::create([
            'book_id' => 9,
            'category_id' => 9,
        ]);
        BookCategory::create([
            'book_id' => 10,
            'category_id' => 10,
        ]);
        BookCategory::create([
            'book_id' => 11,
            'category_id' => 13,
        ]);
        BookCategory::create([
            'book_id' => 12,
            'category_id' =>4,
        ]);
        BookCategory::create([
            'book_id' => 13,
            'category_id' => 5,
        ]);
        BookCategory::create([
            'book_id' => 14,
            'category_id' => 7,
        ]);
        BookCategory::create([
            'book_id' => 15,
            'category_id' => 10,
        ]);
        BookCategory::create([
            'book_id' => 16,
            'category_id' => 9,
        ]);
        BookCategory::create([
            'book_id' => 17,
            'category_id' => 4,
        ]);
    }
}
