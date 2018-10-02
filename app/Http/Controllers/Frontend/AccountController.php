<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Repositories\ClientRepository;
use Hideyo\Ecommerce\Framework\Repositories\ClientAddressRepository;
use Hideyo\Ecommerce\Framework\Repositories\OrderRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductRepository;
use Hideyo\Ecommerce\Framework\Repositories\SendingPaymentMethodRelatedRepository;
use Hideyo\Ecommerce\Framework\Repositories\SendingMethodRepository;
use Validator;
use Mail;
use Notification;

class AccountController extends Controller
{
    
    public function __construct(
        SendingMethodRepository $sendingMethod, 
        ProductRepository $product, 
        SendingPaymentMethodRelatedRepository $sendingPaymentMethodRelated, 
        OrderRepository $order, 
        ClientRepository $client, 
        ClientAddressRepository $clientAddress)
    {
        $this->auth = auth('web');
        $this->client = $client;
        $this->clientAddress = $clientAddress;
        $this->order = $order;
        $this->product = $product;
        $this->sendingPaymentMethodRelated = $sendingPaymentMethodRelated;
        $this->sendingMethod = $sendingMethod;
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
            $text = $this->sendingPaymentMethodRelated->selectOneByShopIdAndPaymentMethodIdAndSendingMethodId($order->shop->id, $order->orderSendingMethod->sending_method_id, $order->orderPaymentMethod->payment_method_id);

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

    public function getResetAccount($code, $email)
    {
        $result = $this->client->resetAccount($code, $email, config()->get('app.shop_id'));

        if ($result) {
            Notification::success('Je account gegevens zijn gewijzigd en je dient opnieuw in te loggen met de nieuwe gegevens.');
        } else {
            Notification::error('Wijziging is niet mogelijk.');
        }

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
            return redirect()->to(null, 'account/edit-address/'.$type)
                ->with(array('type' => $type))->withInput();
        } else {
                $user = $this->auth->user();

            if ($type == 'bill') {
                $id = $user->clientBillAddress->id;

                if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                    $clientAddress = $this->clientAddress->createByClient($userdata, $user->id);
                    $this->client->setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
                } else {
                    $clientAddress = $this->client->editAddress(config()->get('app.shop_id'), $user->id, $id, $userdata);
                }
            } elseif ($type == 'delivery') {
                $id = $user->clientDeliveryAddress->id;

                if ($user->clientDeliveryAddress->id == $user->clientBillAddress->id) {
                    $clientAddress = $this->clientAddress->createByClient($userdata, $user->id);
                    $this->client->setBillOrDeliveryAddress(config()->get('app.shop_id'), $user->id, $clientAddress->id, $type);
                } else {
                    $clientAddress = $this->client->editAddress(config()->get('app.shop_id'), $user->id, $id, $userdata);
                }
            }

            return redirect()->to('account');
        }
    }


    public function postEditAccount(Request $request)
    {

        if ($this->auth->check()) {
            $result = $this->client->setAccountChange($this->auth->user(), $request->all(), config()->get('app.shop_id'));

            if ($result) {
                $firstname = false;

                if ($result->clientBillAddress->count()) {
                    $firstname = $result->clientBillAddress->firstname;
                }

                $data = array(
                    'email' => $result->new_email,
                    'firstname' => $firstname,
                    'confirmation_code' => $result->confirmation_code
                );

                Mail::send('frontend.email.reset-account-settings-mail', $data, function ($message) use ($data) {
                
                    $message->to($data['email'])->from('info@philandphae.com', 'Phil & Phae')->subject('Bevestig het wijzigen van jouw accountgegevens');
                });

                Notification::success('Er is een e-mail gestuurd. Deze dient goedgekeurd te worden voor de wijzigingen.');
            } else {
                Notification::error('Wij kunnen het niet veranderen. Een ander account maakt er gebruik van.');
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

        if ($shop->wholesale) {
            return view('frontend.account.register-wholesale')->with(array('sendingMethods' => $shop->sendingMethods));
        }
        
        return view('frontend.account.register')->with(array('sendingMethods' => $shop->sendingMethods));
    }

    public function getForgotPassword()
    {
        return view('frontend.account.forgot-password');
    }

    public function getResetPassword($confirmationCode, $email)
    {
        $result = $this->client->validateConfirmationCodeByConfirmationCodeAndEmail($confirmationCode, $email, config()->get('app.shop_id'));

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

        $result = $this->client->validateConfirmationCodeByConfirmationCodeAndEmail($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            $result = $this->client->resetPasswordByConfirmationCodeAndEmail(array('confirmation_code' => $confirmationCode, 'email' => $email, 'password' => $request->get('password')), config()->get('app.shop_id'));
            Notification::success('Je wachtwoord is veranderd en je kan nu inloggen.');
            return redirect()->to('account/login');
        }
    }

    public function postSubscriberNewsletter(Request $request)
    {
        $userData = $request->all();
        $result = $this->client->subscribeNewsletter($userData['email'], config()->get('app.shop_id'));
        $result = array(
            "result" => true,
            "html" => view('frontend.newsletter.completed')->render()
        );

        $this->client->registerMailChimp($userData['email']);
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

        $forgotPassword = $this->client->getConfirmationCodeByEmail($userdata['email'], config()->get('app.shop_id'));

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
        $result = $this->client->validateConfirmationCodeByConfirmationCodeAndEmail($code, $email, config()->get('app.shop_id'));

        if ($result->count()) {
            $this->client->confirm($code, $email, config()->get('app.shop_id'));
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
        // create the validation rules ------------------------
        $rules = array(
            'email'            => 'required|email',     // required and must be unique in the ducks table
            'password'         => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            Notification::error(implode('<br/>', $validator->errors()->all()));
            return redirect()->back()->withInput();
        }

        $userdata = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'confirmed' => 1,
            'active' => 1,
            'shop_id' => config()->get('app.shop_id')
        );

        /* Try to authenticate the credentials */
        if ($this->auth->attempt($userdata)) {
        // we are now logged in, go to admin
            return redirect()->to('/');
        } else {
            Notification::error('Not correct.');
            return redirect()->back()->withInput();
        }  
    }

    public function postRegister(Request $request)
    {
        $userdata = $request->all();
        $shop = app('shop');

        // create the validation rules ------------------------
        $rules = array(
            'email'         => 'required|email',     // required and must be unique in the ducks table
            'password'      => 'required',
            'firstname'     => 'required',
            'lastname'      => 'required',
            'zipcode'       => 'required',
            'housenumber'   => 'required|numeric',
            'houseletter'   => 'alpha',
            'street'        => 'required',
            'city'          => 'required',
            'country'       => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            foreach ($validator->errors()->all() as $error) {

                Notification::error($error);
            }

            // redirect our user back to the form with the errors from the validator
            return redirect()->back()->withInput();
        }
            
        $registerAttempt = $this->client->validateRegister($userdata, config()->get('app.shop_id'));

        if ($registerAttempt) {
            $register = $this->client->register($userdata, config()->get('app.shop_id'));
        } else {
             $client = $this->client->findByEmail($userdata['email'], config()->get('app.shop_id'));
            
            if ($client->account_created) {
                Notification::error('Email already exists.');
                return redirect()->back()->withInput();
            } else {
                $register = $this->client->register($userdata, config()->get('app.shop_id'));
            }
        }

        if ($register) {
            $data = $register;
            Mail::send('frontend.email.register-mail', array('user' => $data->toArray(), 'password' => $request->get('password'), 'billAddress' => $data->clientBillAddress->toArray()), function ($message) use ($data) {
            
                $message->to($data['email'])->from('info@hidey.io', 'Hideyo')->subject(trans('register-completed-subject'));
            });
            Notification::success(trans('you-are-registered-consumer'));
        
       
            return redirect()->to('account/login');
        }
        
        return redirect()->back()->withErrors($register['errors'], 'register')->withInput();
    }
}