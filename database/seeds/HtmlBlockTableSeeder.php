<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\HtmlBlock as HtmlBlock;

class HtmlBlockTableSeeder extends Seeder
{
    public function run()
    {
        $htmlBlock = new HtmlBlock;

        DB::table($htmlBlock->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $htmlBlock->title = 'about us';
        $htmlBlock->active = 1;
        $htmlBlock->position = 'footer-about-us';
        $htmlBlock->content = '
            <h5>About us</h5>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>';     
        $htmlBlock->shop_id = $shop->id;
        $htmlBlock->save();

        $htmlBlock2 = new HtmlBlock;
        $htmlBlock2->title = 'contact';
        $htmlBlock2->active = 1;
        $htmlBlock2->position = 'footer-contact';
        $htmlBlock2->content = '
              <h5>Contact</h5>
            <p>
                De Binderij 34<br>
                1321 EJ Almere<br>
                +31682008200<br>
                <a href="https://github.com/hideyo/ecommerce">github</a>
            </p>';     
        $htmlBlock2->shop_id = $shop->id;
        $htmlBlock2->save();
    }
}
