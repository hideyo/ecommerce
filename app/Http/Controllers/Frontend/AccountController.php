<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;
use Validator;
use Mail;
use Notification;

class AccountController extends Controller
{
    public function getIndex()
    {
        return view('frontend.account.index')->with(array('user' => auth('web')->user()));
    }

    public function getEditAccount()
    {
        return view('frontend.account.edit-account')->with(array('user' => auth('web')->user()));
    }

    public function getResetAccount($confirmationCode, $email)
    {
        $result = ClientService::changeAccountDetails($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            Notification::success('Je account gegevens zijn gewijzigd en je dient opnieuw in te loggen met de nieuwe gegevens.');
        }
        
        Notification::error('Wijziging is niet mogelijk.');
        auth('web')->logout();
        return redirect()->to('account/login');
    }

    public function getEditAddress($type)
    {
        $shop = app('shop');
        return view('frontend.account.edit-account-address-'.$type)->with(array('sendingMethods' => $shop->sendingMethods, 'user' => auth('web')->user()));
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
     
        $user = auth('web')->user();

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
        if (auth('web')->check()) {
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
        return view('frontend.account.login');
    }

    public function postLogin(Request $request)
    {
        $validateLogin = ClientService::validateLogin($request->all());

        if ($validateLogin->fails()) {
            foreach ($validateLogin->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->back()->withInput();
        }

        $login = ClientService::login($request);

        if($login) {
            return redirect()->to('/account');
        }
        
        Notification::error('Not correct.');
        return redirect()->back()->withInput(); 
    }

    public function getRegister()
    {     
        return view('frontend.account.register')->with(array('sendingMethods' => app('shop')->sendingMethods));
    }

    public function postRegister(Request $request)
    {
        $userdata = $request->all();
        $validateRegister = ClientService::validateRegister($userdata);

        if($validateRegister->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->back()->withInput();
        }

        $register = ClientService::register($userdata, config()->get('app.shop_id'));

        if ($register) {
            $data = $register->toArray();
            Mail::send('frontend.email.register-mail', array('user' => $register->toArray(), 'password' => $request->get('password'), 'billAddress' => $register->clientBillAddress->toArray()), function ($message) use ($data) {
                $message->to($data['email'])->from('info@hidey.io', 'Hideyo')->subject(trans('register-completed-subject'));
            });
            Notification::success(trans('you-are-registered-consumer'));
            return redirect()->to('account/login');
        }

        Notification::error('Email already exists.');
        return redirect()->back()->withInput();
    }

    public function getForgotPassword()
    {
        return view('frontend.account.forgot-password');
    }

    public function postForgotPassword(Request $request)
    {
        $rules = array(
            'email'            => 'required|email'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
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
                $message->to($data['email'])->from('info@hideyo.io', 'Hideyo')->subject('Wachtwoord vergeten');
            });

            Notification::success('Er is een e-mail gestuurd. Hiermee kan je je wachtwoord resetten.');

            return redirect()->back();
        }
        
        Notification::error('Account not exist.');
        return redirect()->back()->withErrors($forgotPassword['errors'], 'forgot')->withInput();
    }

    public function getResetPassword($confirmationCode, $email)
    {
        $result = ClientService::validateConfirmationCode($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            return view('frontend.account.reset-password')->with(array('confirmationCode' => $confirmationCode, 'email' => $email));
        }

        Notification::error('wachtwoord vergeten is mislukt');
        return redirect()->to('account/forgot-password')->withErrors(true, 'forgot')->withInput();
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

    public function getConfirm($code, $email)
    {
        $result = ClientService::validateConfirmationCode($code, $email, config()->get('app.shop_id'));

        if ($result->count()) {
            ClientService::confirmClient($code, $email, config()->get('app.shop_id'));
            Notification::success('Account is activated.');
            return redirect()->to('account/login');
        }

        Notification::error('Information is incorrrect.');
        return redirect()->to('account/login');
    }

    public function getLogout(Request $request)
    {
        auth('web')->logout();
        $referrer = $request->headers->get('referer');
        if ($referrer) {
            if (strpos($referrer, 'checkout') !== false) {
                return redirect()->to('cart/checkout');
            }
        }

        return redirect()->to('account/login');
    } 
}