<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\News as News;
use Hideyo\Ecommerce\Framework\Models\NewsGroup as NewsGroup;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        $news = new News;

        DB::table($news->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $newsGroup = new NewsGroup;
        $newsGroup->title = "general";
        $newsGroup->active = 1;
        $newsGroup->shop_id = $shop->id;
        $newsGroup->save();

        $news->title = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';
        $news->short_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus.';
        $news->content = '
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>';     
        $news->shop_id = $shop->id;
        $news->news_group_id = $newsGroup->id;
        $news->published_at = '2016-01-01';
        $news->save();

        $news2 = new News;
        $news2->title = 'Integer fermentum venenatis tempus';
        $news2->short_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus.';
        
        $news2->content = '
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>';      
        $news2->shop_id = $shop->id;
        $news2->news_group_id = $newsGroup->id;
        $news2->published_at = '2016-01-01';
        $news2->save();


        $news3 = new News;
        $news3->title = 'Suspendisse scelerisque urna bibendum';
        $news3->short_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus.';
        
        
        $news3->content = '
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum venenatis tempus. Suspendisse scelerisque urna bibendum pretium consectetur. Fusce eu enim tempus odio imperdiet pharetra. Ut varius nunc eget condimentum auctor. Morbi lacinia augue est, id tristique augue faucibus a.
            </p>';      
        $news3->shop_id = $shop->id;
        $news3->news_group_id = $newsGroup->id;
        $news3->published_at = '2016-01-01';
        $news3->save();

    }
}
