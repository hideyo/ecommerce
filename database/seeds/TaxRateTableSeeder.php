<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\TaxRate as TaxRate;


class TaxRateTableSeeder extends Seeder
{
    public function run()
    {
        $taxRate = new TaxRate;

        DB::table($taxRate->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $taxRate->title = '21%';
        $taxRate->rate = '21';     
        $taxRate->shop_id = $shop->id;
        $taxRate->save();

        $taxRate2 = new TaxRate;
        $taxRate2->title = '6%';
        $taxRate2->rate = '6';     
        $taxRate2->shop_id = $shop->id;
        $taxRate2->save();
    }
}
