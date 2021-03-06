<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\Product\ProductTagGroupFacade as ProductTagGroupService;
use Illuminate\Http\Request;
use Validator;
use Notification;
use Mail;

class BasicController extends Controller
{
    public function index()
    {        
        $populairProducts = ProductTagGroupService::selectAllByTagAndShopId(config()->get('app.shop_id'), 'home-populair');
        return view('frontend.basic.index')->with(array('populairProducts' => $populairProducts));
    }

    public function getContact()
    {
        return view('frontend.basic.contact');
    }

    public function postContact(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'email'            => 'required'
        );

        $input = $request->all();
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            Notification::error($validator->errors()->all());  
        }

        Mail::send('frontend.email.contact', ['data' => $input], function ($m) use ($input) {
            $m->from('info@dutchbridge.nl', 'Dutchbridge');
            $m->replyTo($input['email'], $input['name']);
            $m->to('info@dutchbridge.nl')->subject(': thnx for your contact!');
        });
      
        Notification::success(trans('thnx for your contact!'));
        return redirect()->route('contact');  
    }
}