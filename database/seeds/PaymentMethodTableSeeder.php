<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\PaymentMethod as PaymentMethod;
use Hideyo\Ecommerce\Framework\Models\SendingMethod as SendingMethod;

use Hideyo\Ecommerce\Framework\Models\TaxRate as TaxRate;

class PaymentMethodTableSeeder extends Seeder
{
    public function run()
    {
        $taxRate = TaxRate::where('title', '=', '21%')->first();
        $paymentMethod = new PaymentMethod;

        DB::table($paymentMethod->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $paymentMethod->active = 1;
        $paymentMethod->title = 'Bank transfer';
        $paymentMethod->tax_rate_id = $taxRate->id; 
        $paymentMethod->price = '0';  
        $paymentMethod->shop_id = $shop->id;
        $paymentMethod->save();

        $sendingMethod = SendingMethod::where('title', '=', 'China')->first();
        $sendingMethod->relatedPaymentMethods()->sync(array($paymentMethod->id));


        $sendingMethod = SendingMethod::where('title', '=', 'Netherlands')->first();
        $sendingMethod->relatedPaymentMethods()->sync(array($paymentMethod->id));



    }
}
