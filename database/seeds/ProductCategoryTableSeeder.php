<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\ProductCategory as ProductCategory;

class ProductCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $productCategory = new ProductCategory;

        DB::table($productCategory->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $productCategory->active = 1;
        $productCategory->title = 'Pants';
        $productCategory->short_description = 'Great pants';
        $productCategory->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';
        $productCategory->meta_title = 'Pants';
        $productCategory->meta_description = 'Great pants';          
        $productCategory->shop_id = $shop->id;
        $productCategory->save();




        $productCategory2 = new ProductCategory;
        $productCategory2->active = 1;
        $productCategory2->title = 'T-shirts';
        $productCategory2->short_description = 'Soft t-shirts';
        $productCategory2->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';
        $productCategory2->meta_title = 'T-shirts';
        $productCategory2->meta_description = 'Soft t-shirts';         
        $productCategory2->shop_id = $shop->id;
        $productCategory2->save();

        $productCategory3 = new ProductCategory;
        $productCategory3->active = 1;
        $productCategory3->title = 'Underwear';
        $productCategory3->short_description = 'Good underwear';
        $productCategory3->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';   
        $productCategory3->meta_title = 'Underwear';
        $productCategory3->meta_description = 'Good underwear';             
        $productCategory3->shop_id = $shop->id;
        $productCategory3->save();

        $productCategory4 = new ProductCategory;
        $productCategory4->active = 1;
        $productCategory4->title = 'Dresses';
        $productCategory4->short_description = 'Lovely dresses';
        $productCategory4->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';    
        $productCategory4->meta_title = 'Dresses';
        $productCategory4->meta_description = 'Lovely dresses';
        $productCategory4->shop_id = $shop->id;
        $productCategory4->save();

        $productCategory5 = new ProductCategory;
        $productCategory5->active = 1;
        $productCategory5->title = 'Hats';
        $productCategory5->short_description = 'Nice hats';
        $productCategory5->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';         
        $productCategory5->meta_title = 'Hats';
        $productCategory5->meta_description = 'Nice hats';
        $productCategory5->shop_id = $shop->id;
        $productCategory5->save();

        $productCategory6 = new ProductCategory;
        $productCategory6->active = 1;
        $productCategory6->title = 'Leather hats';
        $productCategory6->short_description = 'Leather hats';
        $productCategory6->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';         
        $productCategory6->meta_title = 'Hats';
        $productCategory6->meta_description = 'Leather hats';
        $productCategory6->shop_id = $shop->id;
        $productCategory6->parent_id = $productCategory5->id;
        $productCategory6->save();


        $productCategory7 = new ProductCategory;
        $productCategory7->active = 1;
        $productCategory7->title = 'Cotton hats';
        $productCategory7->short_description = 'Cotton hats';
        $productCategory7->description = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat est, mollis vel laoreet sit amet, facilisis et magna. Mauris ultricies bibendum arcu sit amet tristique. Vivamus consequat enim at arcu iaculis blandit. Sed vestibulum metus nec nulla lacinia, nec ultrices ligula pellentesque. Suspendisse et consectetur est. Maecenas viverra metus et est iaculis, id scelerisque nisl hendrerit. Aliquam efficitur sem mi, non volutpat neque consectetur facilisis. Morbi eget nunc rutrum, dictum lectus sit amet, molestie lacus.</p>';         
        $productCategory7->meta_title = 'Hats';
        $productCategory7->meta_description = 'Cotton hats';
        $productCategory7->shop_id = $shop->id;
        $productCategory7->parent_id = $productCategory5->id;
        $productCategory7->save();

    }
}
