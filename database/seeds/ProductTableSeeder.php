<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\Product as Product;
use Hideyo\Ecommerce\Framework\Models\ProductCategory as ProductCategory;
use Hideyo\Ecommerce\Framework\Models\ProductImage as ProductImage;
use Hideyo\Ecommerce\Framework\Models\TaxRate as TaxRate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class ProductTableSeeder extends Seeder
{
    public function run()
    {

        Illuminate\Support\Facades\File::deleteDirectory(storage_path().'/app/files/product');
        Illuminate\Support\Facades\File::deleteDirectory(public_path().'/files/product');
        $directory = resource_path('assets/images/product');

        $productFiles = Illuminate\Support\Facades\File::allFiles($directory);


        $storageImagePath = "/files/product/";
        $publicImagePath = public_path() .config('hideyo.public_path'). "/product/";


        $productCategory = ProductCategory::where('title', '=', 'Pants')->first();
        $taxRate = TaxRate::where('title', '=', '21%')->first();
        $product = new Product;

        DB::table($product->getTable())->delete();

        for ($x = 0; $x <= 10; $x++) {
  
            $product = new Product;
            $shop = Shop::where('title', '=', 'hideyo')->first();
            $product->id = 1 + $x;
            $product->active = 1;
            $product->title = 'Cotton pants '.$x;
            $product->short_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
            $product->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nec nulla dignissim, tempus neque quis, pharetra orci. Pellentesque scelerisque odio vitae dolor pretium, in luctus eros convallis. Etiam nec leo porta, dapibus lectus a, convallis ligula. Morbi in dui aliquet, mattis justo at, egestas nisi. Suspendisse lobortis felis enim, venenatis venenatis elit pretium id. Duis a magna eros. Proin auctor orci eu posuere tincidunt. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam ac tempus urna. Quisque mattis mauris quis elit elementum, porta tincidunt erat scelerisque. Donec ornare lacus quis purus consequat cursus. Maecenas fringilla interdum purus id semper. Donec non eros sem. Maecenas sit amet augue ut lacus commodo venenatis.';
            $product->meta_title = 'Cotton pants';
            $product->meta_description = 'Cotton pants';   
            $product->price = '99.50' * $x;
            $product->amount = 10;
            $product->reference_code = '12343443';        
            $product->shop_id = $shop->id;
            $product->product_category_id = $productCategory->id;
            $product->tax_rate_id = $taxRate->id;
            $product->save();

            $productImage = new productImage;
            $productImage->product_id = $product->id;
            $productImage->extension = 'jpg';
            $productImage->size = '0';
            Storage::put($storageImagePath.$product->id.'/'.$productFiles[0]->getFileName(), $productFiles[0]->getContents());
            $productImage->file = $productFiles[0]->getFileName();
            $productImage->path = $storageImagePath.$product->id.'/'.$productFiles[0]->getFileName();
            $productImage->save();


            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueSize) {

                        $image = Image::make(storage_path().'/app/'.$storageImagePath.$product->id.'/'.$productFiles[0]->getFileName());
                        $explode = explode('x', $valueSize);

                        if ($image->width() >= $explode[0] and $image->height() >= $explode[1]) {
                            $image->resize($explode[0], $explode[1]);
                        }

                        if (!File::exists($publicImagePath.$valueSize."/".$product->id."/")) {
                            File::makeDirectory($publicImagePath.$valueSize."/".$product->id."/", 0777, true);
                        }

                        $image->interlace();

                        $image->save($publicImagePath.$valueSize."/".$product->id."/".$productFiles[0]->getFileName());
                    }
                }
            }
        } 

        $productImage2 = new productImage;
        $productImage2->product_id = $product->id;
        $productImage2->extension = 'jpg';
        $productImage2->size = '0';
        Storage::put($storageImagePath.$product->id.'/'.$productFiles[1]->getFileName(), $productFiles[1]->getContents());
        $productImage2->file = $productFiles[1]->getFileName();
        $productImage2->path = $storageImagePath.$product->id.'/'.$productFiles[1]->getFileName();
        $productImage2->save();

        if ($shop->thumbnail_square_sizes) {
            $sizes = explode(',', $shop->thumbnail_square_sizes);
            if ($sizes) {
                foreach ($sizes as $valueSize) {

                    $image = Image::make(storage_path().'/app/'.$storageImagePath.$product->id.'/'.$productFiles[1]->getFileName());
                    $explode = explode('x', $valueSize);

                    if ($image->width() >= $explode[0] and $image->height() >= $explode[1]) {
                        $image->resize($explode[0], $explode[1]);
                    }

                    if (!File::exists($publicImagePath.$valueSize."/".$product->id."/")) {
                        File::makeDirectory($publicImagePath.$valueSize."/".$product->id."/", 0777, true);
                    }

                    $image->interlace();

                    $image->save($publicImagePath.$valueSize."/".$product->id."/".$productFiles[1]->getFileName());
                }
            }
        }



        $product2 = new Product;
        $product2->id = 12;
        $product2->active = 1;
        $product2->title = 'Jeans';
        $product2->short_description = 'Slimfit jeans';
        $product2->description = 'Slimfit jeans';
        $product2->meta_title = 'Jeans';
        $product2->meta_description = 'Slimfit jeans';   
        $product2->price = '124.99'; 
        $product2->amount = 5;
        $product2->reference_code = '12343445';       
        $product2->shop_id = $shop->id;
        $product2->product_category_id = $productCategory->id;
        $product2->tax_rate_id = $taxRate->id;

        if (! $product2->save()) {
            Log::info('Unable to create product '.$product2->title, (array)$product2->errors());
        } else {
            Log::info('Created product "'.$product2->title.'" <'.$product2->title.'>');
        }



        $productImage = new productImage;
        $productImage->product_id = $product2->id;
        $productImage->extension = 'jpg';
        $productImage->size = '0';
        Storage::put($storageImagePath.$product2->id.'/'.$productFiles[2]->getFileName(), $productFiles[2]->getContents());
        $productImage->file = $productFiles[2]->getFileName();
        $productImage->path = $storageImagePath.$product2->id.'/'.$productFiles[2]->getFileName();
        $productImage->save();


        if ($shop->thumbnail_square_sizes) {
            $sizes = explode(',', $shop->thumbnail_square_sizes);
            if ($sizes) {
                foreach ($sizes as $valueSize) {

                    $image = Image::make(storage_path().'/app/'.$storageImagePath.$product2->id.'/'.$productFiles[2]->getFileName());
                    $explode = explode('x', $valueSize);

                    if ($image->width() >= $explode[0] and $image->height() >= $explode[1]) {
                        $image->resize($explode[0], $explode[1]);
                    }

                    if (!File::exists($publicImagePath.$valueSize."/".$product2->id."/")) {
                        File::makeDirectory($publicImagePath.$valueSize."/".$product2->id."/", 0777, true);
                    }

                    $image->interlace();

                    $image->save($publicImagePath.$valueSize."/".$product2->id."/".$productFiles[2]->getFileName());
                }
            }
        }


        $productCategory = ProductCategory::where('title', '=', 'T-shirts')->first();
        $product3 = new Product;
        $product3->id = 13;
        $product3->active = 1;
        $product3->title = 'Cotton t-shirt';
        $product3->short_description = 'Cotton t-shirt';
        $product3->description = 'Cotton t-shirt';
        $product3->meta_title = 'T-shirt';
        $product3->meta_description = 'Cotton t-shirt';   
        $product3->price = '99'; 
        $product3->amount = 5;
        $product3->reference_code = '1222343445';       
        $product3->shop_id = $shop->id;
        $product3->product_category_id = $productCategory->id;
        $product3->tax_rate_id = $taxRate->id;
        $product3->save();

        $productImage = new productImage;
        $productImage->product_id = $product3->id;
        $productImage->extension = 'jpg';
        $productImage->size = '0';
        Storage::put($storageImagePath.$product3->id.'/'.$productFiles[2]->getFileName(), $productFiles[2]->getContents());
        $productImage->file = $productFiles[2]->getFileName();
        $productImage->path = $storageImagePath.$product3->id.'/'.$productFiles[2]->getFileName();
        $productImage->save();

        if ($shop->thumbnail_square_sizes) {
            $sizes = explode(',', $shop->thumbnail_square_sizes);
            if ($sizes) {
                foreach ($sizes as $valueSize) {

                    $image = Image::make(storage_path().'/app/'.$storageImagePath.$product3->id.'/'.$productFiles[2]->getFileName());
                    $explode = explode('x', $valueSize);

                    if ($image->width() >= $explode[0] and $image->height() >= $explode[1]) {
                        $image->resize($explode[0], $explode[1]);
                    }

                    if (!File::exists($publicImagePath.$valueSize."/".$product3->id."/")) {
                        File::makeDirectory($publicImagePath.$valueSize."/".$product3->id."/", 0777, true);
                    }

                    $image->interlace();

                    $image->save($publicImagePath.$valueSize."/".$product3->id."/".$productFiles[2]->getFileName());
                }
            }
        }



        $productCategory = ProductCategory::where('title', '=', 'T-shirts')->first();
        $product4 = new Product;
        $product4->id = 14;
        $product4->active = 1;
        $product4->title = 'Sport t-shirt';
        $product4->short_description = 'Sport t-shirt';
        $product4->description = 'Sport t-shirt';
        $product4->meta_title = 'T-shirt';
        $product4->meta_description = 'Sport t-shirt';   
        $product4->price = '89'; 
        $product4->amount = 5;
        $product4->reference_code = '222343445';       
        $product4->shop_id = $shop->id;
        $product4->product_category_id = $productCategory->id;
        $product4->tax_rate_id = $taxRate->id;
        $product4->save();

        $productImage = new productImage;
        $productImage->product_id = $product4->id;
        $productImage->extension = 'jpg';
        $productImage->size = '0';
        Storage::put($storageImagePath.$product4->id.'/'.$productFiles[2]->getFileName(), $productFiles[2]->getContents());
        $productImage->file = $productFiles[2]->getFileName();
        $productImage->path = $storageImagePath.$product4->id.'/'.$productFiles[2]->getFileName();
        $productImage->save();


        if ($shop->thumbnail_square_sizes) {
            $sizes = explode(',', $shop->thumbnail_square_sizes);
            if ($sizes) {
                foreach ($sizes as $valueSize) {

                    $image = Image::make(storage_path().'/app/'.$storageImagePath.$product4->id.'/'.$productFiles[2]->getFileName());
                    $explode = explode('x', $valueSize);

                    if ($image->width() >= $explode[0] and $image->height() >= $explode[1]) {
                        $image->resize($explode[0], $explode[1]);
                    }

                    if (!File::exists($publicImagePath.$valueSize."/".$product4->id."/")) {
                        File::makeDirectory($publicImagePath.$valueSize."/".$product4->id."/", 0777, true);
                    }

                    $image->interlace();

                    $image->save($publicImagePath.$valueSize."/".$product4->id."/".$productFiles[2]->getFileName());
                }
            }
        }
    }
}
