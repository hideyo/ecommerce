<?php namespace App\Http\Controllers\Backend;
/**
 * BrandController
 *
 * This is the controller of the brands of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

use Hideyo\Ecommerce\Framework\Services\Brand\BrandFacade as BrandService;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $brand = BrandService::getModel()
            ->select(['id', 'rank','title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($brand)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('brand.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('brand.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.brand.index')->with('brand', BrandService::selectAll());
    }

    public function create()
    {
        return view('backend.brand.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = BrandService::create($request->all());
        return BrandService::notificationRedirect('brand.index', $result, 'The brand was inserted.');
    }

    public function edit($brandId)
    {
        return view('backend.brand.edit')->with(array('brand' => BrandService::find($brandId)));
    }

    public function update(Request $request, $brandId)
    {
        $result  = BrandService::updateById($request->all(), $brandId);
        return BrandService::notificationRedirect('brand.index', $result, 'The brand was updated.');
    }

    public function destroy($brandId)
    {
        $result  = BrandService::destroy($brandId);
        if ($result) {
            Notification::error('The brand was deleted.');
            return redirect()->route('brand.index');
        }
    }
}
