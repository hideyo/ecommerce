<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;

class ShopTableSeeder extends Seeder
{
    public function run()
    {
        $shop = new Shop;

        DB::table($shop->getTable())->delete();

        $shop->title = 'hideyo';
        $shop->url = config()->get('app.url');
        $shop->secret_key = 'RQlqvrjpGWPVqY1gPerKstoO4X6xhGGE';
        $shop->active = 1;
        $shop->description = "description";
        $shop->currency_code = "EUR";
        $shop->thumbnail_widescreen_sizes = "100x100,200x200,500x500,800x800";
        $shop->thumbnail_square_sizes = "100x100,200x200,500x500,800x800"; 
        $shop->meta_title = "Hideyo - laravel e-commerce platform";
        $shop->meta_description = "Clean Laravel e-commerce platform for building your custom and unique webshop";     
        $shop->save();  


        $shop2 = new Shop;  


        $shop2->title = 'wholesale';
        $shop2->url = 'http://wholesaledemo.hideyo.io';
        $shop2->secret_key = 'RQlqvrjpGWPVqY1gPerKstoO4X6xhGGF';
        $shop2->active = 1;
        $shop2->description = "description";
        $shop2->currency_code = "EUR";
        $shop2->thumbnail_widescreen_sizes = "100x100,200x200,500x500,800x800";
        $shop2->thumbnail_square_sizes = "100x100,200x200,500x500,800x800"; 
        $shop2->meta_title = "Hideyo - laravel e-commerce platform";
        $shop2->meta_description = "Clean Laravel e-commerce platform for building your custom and unique webshop";     
        $shop2->save();  


    }
}