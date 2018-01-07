<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\User as User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $user = new User;

        DB::table($user->getTable())->delete();

        $user->username = 'admin@admin.com';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('admin');

        $shop = Shop::where('title', '=', 'hideyo')->first();
        $user->selected_shop_id = $shop->id;

        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = 1;
        $user->save();
    }
}