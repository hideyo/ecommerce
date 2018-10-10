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
use Hideyo\Ecommerce\Framework\Services\User\UserFacade as UserService;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;
use Notification;
use Redirect;
use Request;

class UserController extends Controller
{
    public function index()
    {

        if (Request::wantsJson()) {

            $query = UserService::getModel()->select(
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
        
        return view('backend.user.index');
    }
    
    public function create()
    {
        $shops = ShopService::selectAll()->pluck('title', 'id');
        return view('backend.user.create', array('shops' => $shops));
    }

    public function store()
    {
        $result  = UserService::signup(Request::all());
        return UserService::notificationRedirect('user.index', $result, 'The user was inserted.');
    }

    public function edit($id)
    {
        $shops = ShopService::selectAll()->pluck('title', 'id');
        return view('backend.user.edit')->with(array('user' => UserService::find($id), 'shops' => $shops));
    }

    public function editProfile()
    {
        if (auth()->user()) {
            $id = auth()->id();
        }

        $shops = ShopService::selectAll()->pluck('title', 'id');
        $languages = $this->language->getModel()->pluck('language', 'id');
        return view('backend.user.profile')->with(array('user' => User::find($id), 'languages' => $languages, 'shops' => $shops));
    }

    public function changeShopProfile($shopId)
    {
        if (auth('hideyobackend')->user()) {
            $id = auth('hideyobackend')->id();
        }

        $shop = ShopService::find($shopId);
        $result  = UserService::updateShopProfileById($shop, $id);
        Notification::success('The shop changed.');
        return redirect()->route('shop.index');
    }

    public function updateProfile()
    {
        if (auth()->user()) {
            $id = auth()->id();
        }

        $result  = UserService::updateProfileById(Request::all(), Request::file('avatar'), $id);
        return UserService::notificationRedirect('user.index', $result, 'The user was updated.');
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
        $result  = UserService::updateById(Request::all(), Request::file('avatar'), $id);
        return UserService::notificationRedirect('user.index', $result, 'The user was updated.');
    }

    public function destroy($id)
    {
        $result  = UserService::destroy($id);

        if ($result) {
            Notification::success('The user was deleted.');
            return redirect()->route('user.index');
        }
    }
}
