<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_json = file_get_contents("/data/www/public/data/products.json");
        $products     = json_decode($product_json, true);

        foreach ($products as $product){
            Product::create($product);
        }
    }
}
