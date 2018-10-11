<?php namespace App\Http\Controllers\Backend;

/**
 * ShopController
 *
 * This is the controller of the shops
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;
use Hideyo\Ecommerce\Framework\Services\Shop\ShopFacade as ShopService;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = ShopService::getModel()
            ->select(['id', 'title', 'logo_file_name']);
            $datatables = Datatables::of($query)

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('shop.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('shop.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            })

            ->addColumn('image', function ($query) {
                if ($query->logo_file_name) {
                    return '<img src="http://shop.brulo.nl/files/'.$query->id.'/logo/'.$query->logo_file_name.'"  />';
                }
            });

            return $datatables->make(true);
        }
        
        return view('backend.shop.index')->with('shop', ShopService::selectAll());
    }

    public function create()
    {
        return view('backend.shop.create');
    }

    public function store(Request $request)
    {
        $result  = ShopService::create($request->all());
        return ShopService::notificationRedirect('shop.index', $result, 'The shop was inserted.');
    }

    public function edit($shopId)
    {
        return view('backend.shop.edit')->with(array('shop' => ShopService::find($shopId)));
    }

    public function update(Request $request, $shopId)
    {
        $result  = ShopService::updateById($request->all(), $shopId);
        return ShopService::notificationRedirect('shop.index', $result, 'The shop was updated.');
    }

    public function destroy($shopId)
    {
        $result  = ShopService::destroy($shopId);

        if ($result) {
            Notification::success('The shop was deleted.');
            return redirect()->route('shop.index');
        }
    }
}