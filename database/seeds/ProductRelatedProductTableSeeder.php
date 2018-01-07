<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\ProductRelatedProduct as ProductRelatedProduct;

class ProductRelatedProductTableSeeder extends Seeder
{
    public function run()
    {
        $productRelatedProduct = new ProductRelatedProduct;

        DB::table($productRelatedProduct->getTable())->delete();

        for ($x = 1; $x <= 10; $x++) {
	        $productRelatedProduct = new ProductRelatedProduct;
	        $productRelatedProduct->product_id = $x;
	        $productRelatedProduct->related_product_id = $x + 1;
	        $productRelatedProduct->save();
	    }

        for ($x = 2; $x <= 10; $x++) {
	        $productRelatedProduct = new ProductRelatedProduct;
	        $productRelatedProduct->product_id = $x;
	        $productRelatedProduct->related_product_id = $x + 1;
	        $productRelatedProduct->save();
	    }

        for ($x = 3; $x <= 10; $x++) {
	        $productRelatedProduct = new ProductRelatedProduct;
	        $productRelatedProduct->product_id = $x;
	        $productRelatedProduct->related_product_id = $x + 1;
	        $productRelatedProduct->save();
	    }

        for ($x = 4; $x <= 10; $x++) {
	        $productRelatedProduct = new ProductRelatedProduct;
	        $productRelatedProduct->product_id = $x;
	        $productRelatedProduct->related_product_id = $x + 1;
	        $productRelatedProduct->save();
	    }
    }
}
