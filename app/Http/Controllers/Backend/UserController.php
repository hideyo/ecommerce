<?php namespace App\Http\Controllers\Backend;

/**
 * UserController
 *
 * This is the controller of users of the shop
 * @author Matthijs Neijenhuijs <matthijs@io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Dutchbridge\Validators\UserValidator;
use Dutchbridge\Datatable\UserNumberDatatable;
use Hideyo\Repositories\UserRepositoryInterface;

use Hideyo\Repositories\UserLogRepositoryInterface;
use Hideyo\Repositories\ShopRepositoryInterface;
use Auth;
use Notification;
use Redirect;
use Request;

class UserController extends Controller
{
    public function __construct(
        UserRepositoryInterface $user,
        ShopRepositoryInterface $shop
    ) {
        $this->user         = $user;
        $this->shop         = $shop;
    }

    public function index()
    {

        if (Request::wantsJson()) {

            $query = $this->user->getModel()->select(
                [
                
                'id',
                'email', 'username']
            );
            
            $datatables = \Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax(url()->route('user.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('user.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.user.index')->with('users', $this->user->selectAll());
    }

    public function show($id)
    {
        $user = $this->user->find($id);
        $userProfileData = $user->getUserProfileData()->get();
        return view('backend.user.show')->with(array('user' => $user, 'user_profile_data' => $userProfileData));
    }

    public function create()
    {
        $shops = $this->shop->selectAll()->pluck('title', 'id');
        return view('backend.user.create', array('shops' => $shops));
    }

    public function selectNumber($id)
    {
        $numbers = $this->number->selectNewNumbers();

        return view('backend.user.select_number', array('numbers' => $numbers, 'user' => $this->user->find($id)));
    }

    public function storeNumber($user_id)
    {
        $result  = $this->userNumber->create(Request::all(), $user_id);
 
        if ($result->user_id) {
            return Redirect::route('user.numbers', $user_id);
        } else {
            Notification::error('field are required');
        }

        return Redirect::back()->withInput()->withErrors($result->errors);
    }

    public function store()
    {
        $result  = $this->user->signup(Request::all());
 

        if (isset($result->id)) {
            Notification::success('The user was inserted.');
            return Redirect::route('user.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            \Notification::error($error);
        }
        
        return \Redirect::back()->withInput();
    }

    public function edit($id)
    {
        $shops = $this->shop->selectAll()->pluck('title', 'id');
        return view('backend.user.edit')->with(array('user' => $this->user->find($id), 'shops' => $shops));
    }

    public function editProfile()
    {
        if (Auth::user()) {
            $id = Auth::id();
        }

        $shops = $this->shop->selectAll()->pluck('title', 'id');
        $languages = $this->language->getModel()->pluck('language', 'id');
        return view('backend.user.profile')->with(array('user' => User::find($id), 'languages' => $languages, 'shops' => $shops));
    }

    public function changeShopProfile($shopId)
    {
        if (auth('hideyobackend')->user()) {
            $id = auth('hideyobackend')->id();
        }

        $shop = $this->shop->find($shopId);
        $result  = $this->user->updateShopProfileById($shop, $id);
        Notification::success('The shop changed.');
        return Redirect::route('shop.index');
    }

    public function updateProfile()
    {
        if (Auth::user()) {
            $id = Auth::id();
        }

        $result  = $this->user->updateProfileById(Request::all(), Request::file('avatar'), $id);

        if ($result->errors) {
            return Redirect::back()->withInput()->withErrors($result->errors);
        } else {
            //$this->userLog->create('info', 'My Profile '.$result->email.' updated', $result->id);
            Notification::success('The user was updated.');
            return Redirect::route('edit.profile');
        }
    }

    public function updateLanguage()
    {
        $rules = [
        'language' => 'in:en,fr' //list of supported languages of your application.
        ];

        $language = Request::get('lang'); //lang is name of form select field.

        $validator = Validator::make(compact($language), $rules);

        if ($validator->passes()) {
            Session::put('language', $language);
            App::setLocale($language);
        }
    }

    public function update($id)
    {
        $result  = $this->user->updateById(Request::all(), Request::file('avatar'), $id);
    
        if ($result->errors) {
            return Redirect::back()->withInput()->withErrors($result->errors);
        } else {
            // $this->userLog->create('info', 'Profile '.$result->email.' updated', $result->id);
            Notification::success('The user was updated.');
            return Redirect::route('user.edit', $result->id);
        }
    }

    public function destroy($id)
    {
        $result  = $this->user->destroy($id);

        if ($result) {
            Notification::success('The user was deleted.');
            return Redirect::route('user.index');
        }
    }
}
