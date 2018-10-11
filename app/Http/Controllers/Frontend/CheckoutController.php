<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\Sendingmethod\SendingmethodFacade as SendingmethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\Order\OrderFacade as OrderService;
use Hideyo\Ecommerce\Framework\Services\Order\Events\OrderChangeStatus;
use Cart;
use Validator;
use Notification;
use BrowserDetect;
use Mail;
use Event;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $sendingMethodsList = SendingMethodService::selectAllActiveByShopId(config()->get('app.shop_id'));

        if (Cart::getContent()->count()) {

            $paymentMethodsList = Cart::getConditionsByType('sending_method')->first()->getAttributes()['data']['related_payment_methods_list'];
         
            if(!Cart::getConditionsByType('sending_method')->count()) {
                Notification::error('Selecteer een verzendwijze');
                return redirect()->to('cart');
            }

            if(!Cart::getConditionsByType('payment_method')->count()) {

                Notification::error('Selecteer een betaalwijze');
                return redirect()->to('cart');
            }

        } else {
            return redirect()->to('cart');
        }



        if (auth('web')->guest()) {
            $noAccountUser = session()->get('noAccountUser');
            if ($noAccountUser) {
                if (!isset($noAccountUser['delivery'])) {
                    $noAccountUser['delivery'] = $noAccountUser;
                    session()->put('noAccountUser', $noAccountUser);
                }
  
                return view('frontend.checkout.no-account')->with(array( 
                    'noAccountUser' =>  $noAccountUser, 
                    'sendingMethodsList' => $sendingMethodsList, 
                    'paymentMethodsList' => $paymentMethodsList));
            }
              
             return view('frontend.checkout.login')->with(array(  'sendingMethodsList' => $sendingMethodsList, 'paymentMethodsList' => $paymentMethodsList));
        }

        $user = auth('web')->user();
        self::checkCountryPrice($user->clientDeliveryAddress->country);

        if (!$user->clientDeliveryAddress()->count()) {
            ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $user->clientBillAddress->id, 'delivery');
            return redirect()->to('cart/checkout');
        }

        return view('frontend.checkout.index')->with(array(
            'user' =>  $user, 
            'sendingMethodsList' => $sendingMethodsList, 
            'paymentMethodsList' => $paymentMethodsList));
    }


    public function postCheckoutLogin(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'email'         => 'required|email',     // required and must be unique in the ducks table
            'password'      => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->to('cart/checkout')
            ->withErrors(true, 'login')->withInput();
        }

        $userdata = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'confirmed' => 1,
            'active' => 1,
            'shop_id' => config()->get('app.shop_id')
        );

        /* Try to authenticate the credentials */
        if (auth('web')->attempt($userdata)) {
            // we are now logged in, go to admin
            return redirect()->to('cart/checkout');
        }

        Notification::error(trans('message.error.data-is-incorrect'));
        return redirect()->to('cart/checkout')->withErrors(true, 'login')->withInput(); 
    }

    //to-do: transfer logic to repo
    public function postCheckoutRegister(Request $request)
    {
        if (!Cart::getContent()->count()) {  
            return redirect()->to('cart/checkout');
        }

        $userdata = $request->all();

        $rules = array(
            'email'         => 'required|email',     // required and must be unique in the ducks table
            'password'      => 'required',
            'firstname'     => 'required',
            'lastname'      => 'required',
            'zipcode'       => 'required',
            'housenumber'   => 'required|numeric',
            'street'        => 'required',
            'city'          => 'required'
            );

        if (!$userdata['password']) {
            unset($rules['email']);
            unset($rules['password']);
        } 

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }
            // redirect our user back to the form with the errors from the validator
            return redirect()->to('cart/checkout')
            ->withErrors(true, 'register')->withInput();
        }

        if ($userdata['password']) {
            $registerAttempt = ClientService::validateRegister($userdata, config()->get('app.shop_id'));

            if ($registerAttempt) {
                $register = ClientService::register($userdata, config()->get('app.shop_id'), true);
            } else {
                $client = ClientService::findByEmail($userdata['email'], config()->get('app.shop_id'));

                if ($client->account_created) {
                    Notification::error('Je hebt al een account. Login aan de linkerkant of vraag een nieuw wachtwoord aan.');
                    return redirect()->to('cart/checkout')->withInput()->withErrors('Dit emailadres is al in gebruik. Je kan links inloggen.', 'register');
                } else {
                    $register = ClientService::createAccount($userdata, config()->get('app.shop_id'));
                }
            }

            if ($register) {
                $data = $register;
                $data['shop'] = app('shop');
        
                Mail::send('frontend.email.register-mail', array('password' => $userdata['password'], 'user' => $data->toArray(), 'billAddress' => $data->clientBillAddress->toArray()), function ($message) use ($data) {
            
                    $message->to($data['email'])->from($data['shop']->email, $data['shop']->title)->subject('Je bent geregistreerd.');
                });

                $userdata = array(
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                    'confirmed' => 1,
                    'active' => 1
                );

                auth('web')->attempt($userdata);

                return redirect()->to('cart/checkout')->withErrors('Je bent geregistreerd. Er is een bevestigingsmail gestuurd.', 'login');
            } else {
                Notification::error('Je hebt al een account');
                return redirect()->to('cart/checkout')->withErrors(true, 'register')->withInput();
            }
        }
        
        unset($userdata['password']);
        $registerAttempt = ClientService::validateRegisterNoAccount($userdata, config()->get('app.shop_id'));

        if ($registerAttempt) {
            $register = ClientService::register($userdata, config()->get('app.shop_id'));   
            $userdata['client_id'] = $register->id;
        } else {
            $client = ClientService::findByEmail($userdata['email'], config()->get('app.shop_id'));
            if ($client) {
                $userdata['client_id'] = $client->id;
            }
        }

        session()->put('noAccountUser', $userdata);
        return redirect()->to('cart/checkout');
       
        
    }

    public function postComplete(Request $request)
    {
        $noAccountUser = session()->get('noAccountUser');
        if (auth('web')->guest() and !$noAccountUser) {
            return view('frontend.checkout.login');
        }

        if (!Cart::getContent()->count()) {        
            return redirect()->to('cart/checkout');
        }

        $data = array(
            'products' => Cart::getContent()->toArray(),
            'price_with_tax' => Cart::getTotalWithTax(false),
            'price_without_tax' => Cart::getTotalWithoutTax(false),
            'comments' => $request->get('comments'),
            'browser_detect' => serialize(BrowserDetect::toArray())
        );


        if (auth('web')->check()) {
            $data['user_id'] = auth('web')->user()->id;
        } else {
            $data['user_id'] = $noAccountUser['client_id'];
        }     

        if(Cart::getConditionsByType('sending_method')->count()) {
            $data['sending_method'] = Cart::getConditionsByType('sending_method');
        }

        if(Cart::getConditionsByType('sending_method_country_price')->count()) {
            $data['sending_method_country_price'] = Cart::getConditionsByType('sending_method_country_price');
        }

        if(Cart::getConditionsByType('payment_method')->count()) {
            $data['payment_method'] = Cart::getConditionsByType('payment_method');
        }

        $orderInsertAttempt = OrderService::createByUserAndShopId($data, config()->get('app.shop_id'), $noAccountUser);

        if ($orderInsertAttempt AND $orderInsertAttempt->count()) {
            if ($orderInsertAttempt->OrderPaymentMethod and $orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id) {
                $orderStatus = OrderService::updateStatus($orderInsertAttempt->id, $orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id);
                if ($orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id) {
                    Event::fire(new OrderChangeStatus($orderStatus));
                }
            }

            session()->put('orderData', $orderInsertAttempt);

            if ($orderInsertAttempt->OrderPaymentMethod and $orderInsertAttempt->OrderPaymentMethod->paymentMethod->payment_external) {
                return redirect()->to('cart/payment');
            }

            app('cart')->clear();
            app('cart')->clearCartConditions();  
            session()->flush('noAccountUser');
            $body = "";
            return view('frontend.checkout.complete')->with(array('body' => $body));            
        }

        return redirect()->to('cart/checkout');
    }

    public function getEditAddress(Request $request, $type) {

        if (!Cart::getContent()->count()) {        
            return redirect()->to('cart/checkout');
        }              

        if (auth('web')->guest()) {
            $noAccountUser = session()->get('noAccountUser');
            if ($noAccountUser) {
                
                $address = $noAccountUser;
                if ($type == 'delivery') {
                    $address = $noAccountUser['delivery'];
                }

                return view('frontend.checkout.edit-address-no-account')->with(array('type' => $type, 'noAccountUser' =>  $noAccountUser, 'clientAddress' => $address));
            }
        }

        $user = auth('web')->user();

        if ($type == 'delivery') {
            $address = $user->clientDeliveryAddress->toArray();
        } else {
            $address = $user->clientBillAddress->toArray();
        }

        return view('frontend.checkout.edit-address')->with(array('type' => $type, 'user' => $user, 'clientAddress' => $address));
    }

    public function postEditAddress(Request $request, $type)
    {
        if (!Cart::getContent()->count()) {        
            return redirect()->to('cart/checkout');
        } 
        
        $userdata = $request->all();

        // create the validation rules ------------------------
        $rules = array(
            'firstname'     => 'required',
            'lastname'      => 'required',
            'zipcode'       => 'required',
            'housenumber'   => 'required|numeric',
            'street'        => 'required',
            'city'          => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            // redirect our user back to the form with the errors from the validator
            return redirect()->to('cart/edit-address/'.$type)
            ->with(array('type' => $type))->withInput();
        }

        if (auth('web')->guest()) {
            $noAccountUser = session()->get('noAccountUser');
            if ($noAccountUser) {
                if ($type == 'bill') {
                    $noAccountUser = array_merge($noAccountUser, $userdata);
                } elseif ($type == 'delivery') {
                    $noAccountUser['delivery'] = array_merge($noAccountUser['delivery'], $userdata);
                }

                session()->put('noAccountUser', $noAccountUser);
            }
        } else {
            $user = auth('web')->user();

            if ($type == 'bill') {
                $id = $user->clientBillAddress->id;

                if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                    $clientAddress = $this->clientAddress->createByClient($userdata, $user->id);
                    ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
                } else {
                    $clientAddress = ClientService::editAddress($user->id, $id, $userdata);
                }
            } elseif ($type == 'delivery') {
                $id = $user->clientDeliveryAddress->id;

                if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                    $clientAddress = $this->clientAddress->createByClient($userdata, $user->id);
                    ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
                } else {
                    $clientAddress = ClientService::editAddress($user->id, $id, $userdata);
                }
            }
        }

        return redirect()->to('cart/checkout');        
    }
}