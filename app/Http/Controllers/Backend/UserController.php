<?php namespace App\Http\Controllers\Backend;

/**
 * UserController
 *
 * This is the controller of users of the shop
 * @author Matthijs Neijenhuijs <matthijs@io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\User\UserFacade as UserService;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;
use Illuminate\Http\Request;
use DataTables;
use Form;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = UserService::getModel()->select(['id','email', 'username']);
            $datatables = DataTables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('user.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
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

    public function store(Request $request)
    {
        $result  = UserService::signup($request->all());
        return UserService::notificationRedirect('user.index', $result, 'The user was inserted.');
    }

    public function edit($id)
    {
        $shops = ShopService::selectAll()->pluck('title', 'id');
        return view('backend.user.edit')->with(array('user' => UserService::find($id), 'shops' => $shops));
    }

    public function changeShopProfile($shopId)
    {
        if (auth('hideyobackend')->user()) {
            $id = auth('hideyobackend')->id();
        }

        $shop = ShopService::find($shopId);
        $result  = UserService::updateShopProfileById($shop, $id);
        flash('The shop changed.');
        return redirect()->route('shop.index');
    }

    public function update(Request $request, $id)
    {
        $result  = UserService::updateById($request->all(), $request->file('avatar'), $id);
        return UserService::notificationRedirect('user.index', $result, 'The user was updated.');
    }

    public function destroy($id)
    {
        $result  = UserService::destroy($id);

        if ($result) {
            flash('The user was deleted.');
            return redirect()->route('user.index');
        }
    }
}
