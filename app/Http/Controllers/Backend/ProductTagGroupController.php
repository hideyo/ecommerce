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
use Illuminate\Http\Request;

class ProductTagGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = ProductTagGroupService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = \DataTables::of($query)->addColumn('action', function ($query) {
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

    public function store(Request $request)
    {
        $result  = ProductTagGroupService::create($request->all());
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

    public function update(Request $request, $productTagGroupId)
    {
        $result  = ProductTagGroupService::updateById($request->all(), $productTagGroupId);
        return ProductTagGroupService::notificationRedirect('product-tag-group.index', $result, 'The product group tag was updated.');
    }

    public function destroy($productTagGroupId)
    {
        $result  = ProductTagGroupService::destroy($productTagGroupId);

        if ($result) {
            flash('The product group tag was deleted.');
            return redirect()->route('product-tag-group.index');
        }
    }
}
