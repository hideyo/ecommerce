<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $guard = 'hideyobackend';

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('backend.auth.login');
    }
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        auth('hideyobackend')->logout();

        return redirect()->intended('/admin');
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

        if (auth('hideyobackend')->attempt($credentials)) {
            return redirect()->intended('/admin');
        }

        flash('inlog is incorrect');
     
        return redirect('/admin/security/login')
                    ->withInput($request->only('email', 'remember'));
    }
}
