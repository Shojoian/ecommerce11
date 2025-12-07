<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();

        foreach (range(1, 12) as $i) {
            Product::create([
                'category_id' => $category->id,
                'name'        => "Sample Product {$i}",
                'slug'        => Str::slug("Sample Product {$i}") . '-' . Str::random(3),
                'description' => 'A demo product for testing things.',
                'price'       => rand(500, 5000),
                'stock'       => rand(5, 50),
                'image'       => null,
                'is_active'   => true,
            ]);
        }
    }
}
