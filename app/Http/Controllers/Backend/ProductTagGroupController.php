<?php namespace App\Http\Controllers\Backend;


/**
 * ProductTagGroupController
 *
 * This is the controller of the product tag groups of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductTagGroupFacade as ProductTagGroupService;

use Request;
use Notification;

class ProductTagGroupController extends Controller
{
    public function index()
    {
        if (Request::wantsJson()) {

            $query = ProductTagGroupService::getModel()
            ->select(['id','tag'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = \Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = \Form::deleteajax(url()->route('product-tag-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('product-tag-group.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.product_tag_group.index')->with('productTagGroup', ProductTagGroupService::selectAll());
    }

    public function create()
    {
        return view('backend.product_tag_group.create')->with(array(
            'products' => ProductService::selectAll()->pluck('title', 'id')
        ));
    }

    public function store()
    {
        $result  = ProductTagGroupService::create(\Request::all());
        return ProductTagGroupService::notificationRedirect('product-tag-group.index', $result, 'The product group tag was inserted.');
    }

    public function edit($productTagGroupId)
    {
        return view('backend.product_tag_group.edit')->with(
            array(
                'products' => ProductService::selectAll()->pluck('title', 'id'),
                'productTagGroup' => ProductTagGroupService::find($productTagGroupId)
            )
        );
    }

    public function update($productTagGroupId)
    {
        $result  = ProductTagGroupService::updateById(\Request::all(), $productTagGroupId);
        return ProductTagGroupService::notificationRedirect('product-tag-group.index', $result, 'The product group tag was updated.');
    }

    public function destroy($productTagGroupId)
    {
        $result  = ProductTagGroupService::destroy($productTagGroupId);

        if ($result) {
            Notification::success('The product group tag was deleted.');
            return redirect()->route('product-tag-group.index');
        }
    }
}
