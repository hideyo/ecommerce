<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;
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

        if (!Cart::getContent()->count()) {
            return redirect()->to('cart');
        }

        $paymentMethodsList = Cart::getConditionsByType('sending_method')->first()->getAttributes()['data']['related_payment_methods_list'];
     
        if(!Cart::getConditionsByType('sending_method')->count()) {
            Notification::error('Selecteer een verzendwijze');
            return redirect()->to('cart');
        }

        if(!Cart::getConditionsByType('payment_method')->count()) {
            Notification::error('Selecteer een betaalwijze');
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
        $validateLogin = ClientService::validateLogin($request->all());

        if ($validateLogin->fails()) {
            foreach ($validateLogin->errors()->all() as $error) {
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

        if (auth('web')->attempt($userdata)) {
            return redirect()->to('cart/checkout');
        }

        Notification::error(trans('message.error.data-is-incorrect'));
        return redirect()->to('cart/checkout')->withErrors(true, 'login')->withInput(); 
    }

    public function postCheckoutRegister(Request $request)
    {
        if (!Cart::getContent()->count()) {  
            return redirect()->to('cart/checkout');
        }

        $noAccount = true;
        if ($request->get('password')) {
            $noAccount = false;
        } 

        $validateRegister = ClientService::validateRegister($request->all(), $noAccount);

        if($validateRegister->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->to('cart/checkout')
            ->withErrors(true, 'register')->withInput();;
        }

        if ($request->get('password')) {
            $registerAttempt = ClientService::validateRegister($request->all(), config()->get('app.shop_id'));

            if ($registerAttempt) {
                $register = ClientService::register($request->all(), config()->get('app.shop_id'), true);
            } else {
                $client = ClientService::findByEmail($request->get('email'), config()->get('app.shop_id'));

                if ($client->account_created) {
                    Notification::error('Je hebt al een account. Login aan de linkerkant of vraag een nieuw wachtwoord aan.');
                    return redirect()->to('cart/checkout')->withInput()->withErrors('Dit emailadres is al in gebruik. Je kan links inloggen.', 'register');
                }
                
                $register = ClientService::createAccount($request->all(), config()->get('app.shop_id'));
            }

            if ($register) {
                $data = $register;
                $data['shop'] = app('shop');
        
                Mail::send('frontend.email.register-mail', array('password' => $request->get('password'), 'user' => $data->toArray(), 'billAddress' => $data->clientBillAddress->toArray()), function ($message) use ($data) {
            
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
            }
            
            Notification::error('Je hebt al een account');
            return redirect()->to('cart/checkout')->withErrors(true, 'register')->withInput();    
        }
        
        $userdata = $request->all();
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
        }

        if ($noAccountUser){
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

        $orderInsertAttempt = OrderService::createOrderFrontend($data, config()->get('app.shop_id'), $noAccountUser);

        if ($orderInsertAttempt AND $orderInsertAttempt->count()) {
            if ($orderInsertAttempt->OrderPaymentMethod and $orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id) {
                $orderStatus = OrderService::updateStatus($orderInsertAttempt->id, $orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id);
                if ($orderInsertAttempt->OrderPaymentMethod->paymentMethod->order_confirmed_order_status_id) {
                    Event::dispatch(new OrderChangeStatus($orderStatus));
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
        
        $validate = ClientService::validateAddress($request->all());

        if ($validate->fails()) {
            foreach ($validate->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->to('cart/edit-address/'.$type)
            ->with(array('type' => $type))->withInput();
        }

        if (auth('web')->guest()) {
            $noAccountUser = session()->get('noAccountUser');
            if ($noAccountUser) {
                if ($type == 'bill') {
                    $noAccountUser = array_merge($noAccountUser, $request->all());
                } elseif ($type == 'delivery') {
                    $noAccountUser['delivery'] = array_merge($noAccountUser['delivery'], $request->all());
                }

                session()->put('noAccountUser', $noAccountUser);
            }
        } else {
            $user = auth('web')->user();
            $id = $user->clientBillAddress->id;
            if ($type == 'delivery') {
                $id = $user->clientDeliveryAddress->id;
            }

            if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                $clientAddress = ClientService::createAddress($request->all(), $user->id);
                ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
            } else {
                $clientAddress = ClientService::editAddress($user->id, $id, $request->all());
            }
        }

        return redirect()->to('cart/checkout');        
    }
}