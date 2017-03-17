<?php namespace Hideyo\Backend\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Notification;

class AuthController extends Controller
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $guard = 'admin';

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('admin.auth.login');
    }
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::guard('admin')->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('/');
        }

        Notification::error('inloggegevens zijn fout');
     
        return redirect('/admin/security/login')
                    ->withInput($request->only('email', 'remember'));
    }
}