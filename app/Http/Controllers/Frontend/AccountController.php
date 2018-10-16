<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;
use Illuminate\Http\Request;
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
            Notification::success('Your account details has been changed. Please login with your new credentials');
            auth('web')->logout();
            return redirect()->to('account/login');
        }
        
        Notification::error('Not confirmed.');
        return redirect()->to('account');
    }

    public function getEditAddress($type)
    {
        return view('frontend.account.edit-account-address-'.$type)->with(array('sendingMethods' => app('shop')->sendingMethods, 'user' => auth('web')->user()));
    }

    public function postEditAddress(Request $request, $type)
    {
        $validate = ClientService::validateAddress($request->all());

        if ($validate->fails()) {
            foreach ($validate->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->to('account/edit-address/'.$type)->with(array('type' => $type))->withInput();
        }

        $id = auth('web')->user()->clientBillAddress->id;
        if ($type == 'delivery') {
            $id = auth('web')->user()->clientDeliveryAddress->id; 
        }

        if (auth('web')->user()->clientDeliveryAddress->id == auth('web')->user()->clientBillAddress->id) {
            $clientAddress = ClientService::createAddress($request->all(), auth('web')->user()->id);
            ClientService::setBillOrDeliveryAddress(config()->get('app.shop_id'), auth('web')->user()->id, $clientAddress->id, $type);  
        } else {
            $clientAddress = ClientService::editAddress(auth('web')->user()->id, $id, $request->all());
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
        $validateRegister = ClientService::validateRegister($request->all());

        if($validateRegister->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Notification::error($error);
            }

            return redirect()->back()->withInput();
        }

        $register = ClientService::register($request->all(), config()->get('app.shop_id'));

        if ($register) {
            $data = $register->toArray();
            Mail::send('frontend.email.register-mail', array('user' => $register->toArray(), 'password' => $request->get('password'), 'billAddress' => $register->clientBillAddress->toArray()), function ($message) use ($data) {
                $message->to($data['email'])->from('info@hideyo.io', 'Hideyo')->subject(trans('register-completed-subject'));
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

        $forgotPassword = ClientService::getConfirmationCodeByEmail($request->get('email'), config()->get('app.shop_id'));

        if ($forgotPassword) {
            $firstname = false;

            if ($forgotPassword->clientBillAddress->count()) {
                $firstname = $forgotPassword->clientBillAddress->firstname;
            }

            $data = array(
                'email' => $request->get('email'),
                'firstname' => $firstname,
                'code' => $forgotPassword->confirmation_code
            );

            Mail::send('frontend.email.reset-password-mail', $data, function ($message) use ($data) {
                $message->to($data['email'])->from('info@hideyo.io', 'Hideyo')->subject('Forgot password');
            });

            Notification::success('Email is sent with password reset link.');
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
            return redirect()->to('account/reset-password/'.$confirmationCode.'/'.$email)
                ->withErrors($validator, 'reset')->withInput();
        }

        $result = ClientService::validateConfirmationCode($confirmationCode, $email, config()->get('app.shop_id'));

        if ($result) {
            $result = ClientService::changePassword(array('confirmation_code' => $confirmationCode, 'email' => $email, 'password' => $request->get('password')), config()->get('app.shop_id'));
            Notification::success('Password resetting completed. You can now login');
            return redirect()->to('account/login');
        }
    }

    public function getConfirm($code, $email)
    {
        $result = ClientService::validateConfirmationCode($code, $email, config()->get('app.shop_id'));

        if ($result) {
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
        $referer = $request->headers->get('referer');
        if ($referer) {
            if (strpos($referer, 'checkout') !== false) {
                return redirect()->to('cart/checkout');
            }
        }

        return redirect()->to('account/login');
    } 
}