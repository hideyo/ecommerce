<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\Sendingmethod\SendingmethodFacade as SendingmethodService;
use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\Order\OrderFacade as OrderService;
use Validator;
use Mail;
use Notification;

class AccountController extends Controller
{
    
    public function __construct()
    {
        $this->auth = auth('web');
        session()->forget('category_id');
    }

    public function getIndex()
    {
        return view('frontend.account.index')->with(array('user' => $this->auth->user()));
    }

    public function getDownloadOrder($orderId)
    {
        $order = $this->order->find($orderId);
        
        $pdfText = false;
        if ($order->orderSendingMethod and $order->orderPaymentMethod) {
            $text = "";
            //$text = $this->sendingPaymentMethodRelated->selectOneByShopIdAndPaymentMethodIdAndSendingMethodId($order->shop->id, $order->orderSendingMethod->sending_method_id, $order->orderPaymentMethod->payment_method_id);

            if ($text) {
                $pdfText = $this->replaceTags($text->pdf_text, $order);
            }
        }

        if ($order) {
            $pdf = \PDF::loadView('admin.order.pdf', array('order' => $order, 'pdfText' => $pdfText));
            return $pdf->download('order-'.$order->generated_custom_order_id.'.pdf');
        }
        
        return redirect()->to('account');
    }

    public function replaceTags($content, $order)
    {
        $replace = array(
            'orderId' => $order->id,
            'orderCreated' => $order->created_at,
            'orderTotalPriceWithTax' => $order->price_with_tax,
            'orderTotalPriceWithoutTax' => $order->price_without_tax,
            'clientEmail' => $order->client->email,
            'clientFirstname' => $order->orderBillAddress->firstname,
            'clientLastname' => $order->orderBillAddress->lastname,
        );

        foreach ($replace as $key => $val) {
            $content = str_replace("[" . $key . "]", $val, $content);
        }

        return $content;
    }

    public function getEditAccount()
    {
        return view('frontend.account.edit-account')->with(array('user' => $this->auth->user()));
    }

    public function getResetAccount($confirmationCode, $email)
    {
        $result = ClientService::changeAccountDetails($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            Notification::success('Je account gegevens zijn gewijzigd en je dient opnieuw in te loggen met de nieuwe gegevens.');
        }
        
        Notification::error('Wijziging is niet mogelijk.');
        $this->auth->logout();
        return redirect()->to('account/login');
    }

    public function getEditAddress($type)
    {
        $shop = app('shop');
        return view('frontend.account.edit-account-address-'.$type)->with(array('sendingMethods' => $shop->sendingMethods, 'user' => $this->auth->user()));
    }

    public function postEditAddress(Request $request, $type)
    {
        $userdata = $request->all();

        // create the validation rules ------------------------
        $rules = array(
            'firstname'     => 'required',
            'lastname'      => 'required',
            'zipcode'       => 'required|max:8',
            'housenumber'   => 'required|numeric',
            'street'        => 'required',
            'city'          => 'required',
            'country'       => 'required'
        );

        $validator = Validator::make($userdata, $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            // redirect our user back to the form with the errors from the validator
            return redirect()->to('account/edit-address/'.$type)
                ->with(array('type' => $type))->withInput();
        }
     
        $user = $this->auth->user();

        if ($type == 'bill') {
            $id = $user->clientBillAddress->id;

            if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                $clientAddress = ClientService::createAddress($userdata, $user->id);
                ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
            } else {
                $clientAddress = ClientService::editAddress($user->id, $id, $userdata);
            }
        } elseif ($type == 'delivery') {
            $id = $user->clientDeliveryAddress->id;

            if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                $clientAddress = ClientService::createAddress($userdata, $user->id);
                ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
            } else {
                $clientAddress = ClientService::editAddress($user->id, $id, $userdata);
            }
        }

        return redirect()->to('account');
        
    }

    public function postEditAccount(Request $request)
    {
        if ($this->auth->check()) {
            $requestChange = ClientService::requestChangeAccountDetails($request->all(), config()->get('app.shop_id'));

            if ($requestChange) {
                $firstname = false;

                if ($requestChange->clientBillAddress->count()) {
                    $firstname = $requestChange->clientBillAddress->firstname;
                }

                $data = array(
                    'email' => $requestChange->new_email,
                    'firstname' => $firstname,
                    'confirmation_code' => $requestChange->confirmation_code
                );

                Mail::send('frontend.email.reset-account-settings-mail', $data, function ($message) use ($data) {
                
                    $message->to($data['email'])->from('info@hideyo.io', 'Hideyo')->subject('confirm changing account details');
                });

                Notification::success('E-mail sent');
            } else {
                Notification::error('error');
            }
        }

        return redirect()->to('account');
    }

    public function getLogin()
    {
        $shop = app('shop');

        if ($shop->wholesale) {
            return view('frontend.account.login-wholesale')->with(array());
        }

        return view('frontend.account.login')->with(array());
    }

    public function getRegister()
    {
        $shop = app('shop');        
        return view('frontend.account.register')->with(array('sendingMethods' => $shop->sendingMethods));
    }

    public function getForgotPassword()
    {
        return view('frontend.account.forgot-password');
    }

    public function getResetPassword($confirmationCode, $email)
    {
        $result = ClientService::validateConfirmationCode($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            return view('frontend.account.reset-password')->with(array('confirmationCode' => $confirmationCode, 'email' => $email));
        }

        Notification::error('wachtwoord vergeten is mislukt');
        return redirect()->to('account/forgot-password')
          ->withErrors(true, 'forgot')->withInput();
    }

    public function postResetPassword(Request $request, $confirmationCode, $email)
    {
        $rules = array(
            'password'            => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect()->to('account/reset-password/'.$confirmationCode.'/'.$email)
                ->withErrors($validator, 'reset')->withInput();
        }

        $result = ClientService::validateConfirmationCode($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            $result = ClientService::changePassword(array('confirmation_code' => $confirmationCode, 'email' => $email, 'password' => $request->get('password')), config()->get('app.shop_id'));
            Notification::success('Je wachtwoord is veranderd en je kan nu inloggen.');
            return redirect()->to('account/login');
        }
    }

    public function postSubscriberNewsletter(Request $request)
    {
        $userData = $request->all();
        $result = ClientService::subscribeNewsletter($userData['email'], config()->get('app.shop_id'));
        $result = array(
            "result" => true,
            "html" => view('frontend.newsletter.completed')->render()
        );

        ClientService::registerMailChimp($userData['email']);
        return response()->json($result);
    }

    public function postForgotPassword(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'email'            => 'required|email'
        );

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect()->back()
                ->withErrors($validator, 'forgot')->withInput();
        }

        $userdata = $request->all();

        $forgotPassword = ClientService::getConfirmationCodeByEmail($userdata['email'], config()->get('app.shop_id'));

        if ($forgotPassword) {
            $firstname = false;

            if ($forgotPassword->clientBillAddress->count()) {
                $firstname = $forgotPassword->clientBillAddress->firstname;
            }

            $data = array(
                'email' => $userdata['email'],
                'firstname' => $firstname,
                'code' => $forgotPassword->confirmation_code
            );

            Mail::send('frontend.email.reset-password-mail', $data, function ($message) use ($data) {
            
                $message->to($data['email'])->from('info@philandphae.com', 'Phil & Phae')->subject('Wachtwoord vergeten');
            });

            Notification::success('Er is een e-mail gestuurd. Hiermee kan je je wachtwoord resetten.');

            return redirect()->back();
        } else {
            Notification::error('Account komt niet bij ons voor.');
            return redirect()->back()
            ->withErrors($forgotPassword['errors'], 'forgot')->withInput();
        }

        return redirect()->to('/account/forgot-password'); 
    }

    public function getConfirm($code, $email)
    {
        $result = ClientService::validateConfirmationCode($code, $email, config()->get('app.shop_id'));

        if ($result->count()) {
            ClientService::confirmClient($code, $email, config()->get('app.shop_id'));
            Notification::success('Uw account is geactiveerd.');
            return redirect()->to('account/login');
        }

        Notification::error('Wij kunnen dit niet verwerken.');
        return redirect()->to('account/login');
    }

    public function getLogout(Request $request)
    {
        $this->auth->logout();
        $referrer = $request->headers->get('referer');
        if ($referrer) {
            if (strpos($referrer, 'checkout') !== false) {
                return redirect()->to('cart/checkout');
            }
        }

        return redirect()->to('account/login');
    }

    public function postLogin(Request $request)
    {
        $validateLogin = ClientService::validateLogin($request->all());

        if ($validateLogin->fails()) {
            // get the error messages from the validator
            $messages = $validateLogin->messages();
            // redirect our user back to the form with the errors from the validator
            Notification::error(implode('<br/>', $validateLogin->errors()->all()));
            return redirect()->back()->withInput();
        }

        $loginData = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'confirmed' => 1,
            'active' => 1,
            'shop_id' => config()->get('app.shop_id')
        );

        /* Try to authenticate the credentials */
        if ($this->auth->attempt($loginData)) {
            // we are now logged in
            return redirect()->to('/');
        }
        
        Notification::error('Not correct.');
        return redirect()->back()->withInput(); 
    }

    public function postRegister(Request $request)
    {
        $userdata = $request->all();
        $shop = app('shop');

        $validateRegister = ClientService::validateRegister($userdata);

        if($validateRegister->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->back()->withInput();
        }

        $register = ClientService::register($userdata, config()->get('app.shop_id'));

        if ($register) {
            Mail::send('frontend.email.register-mail', array('user' => $register->toArray(), 'password' => $request->get('password'), 'billAddress' => $register->clientBillAddress->toArray()), function ($message) use ($data) {
                $message->to($data['email'])->from('info@hidey.io', 'Hideyo')->subject(trans('register-completed-subject'));
            });
            Notification::success(trans('you-are-registered-consumer'));
            return redirect()->to('account/login');
        }

        Notification::error('Email already exists.');
        return redirect()->back()->withInput();
    }
}