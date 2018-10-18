<?php namespace App\Http\Controllers\Backend;

/**
 * ProductAmountSeriesController
 *
 * This is the controller of the product amount series of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductAmountSeriesFacade as ProductAmountSeriesService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Illuminate\Http\Request;
use Notification;
use DataTables;
use Form;

class ProductAmountSeriesController extends Controller
{
    public function index(Request $request, $productId)
    {
        $product = ProductService::find($productId);
        if ($request->wantsJson()) {
            $query = ProductAmountSeriesService::getModel()->where('product_id', '=', $productId);
            
            $datatables = DataTables::of($query)

            ->addColumn('active', function ($query) {
                if ($query->active) {
                    return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-ok icon-green"></span></a>';
                }
                
                return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-remove icon-red"></span></a>';
            })
            ->addColumn('action', function ($query) use ($productId) {
                $deleteLink = Form::deleteajax(url()->route('product.product-amount-series.destroy', array('productId' => $productId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('product.product-amount-series.edit', array('productId' => $productId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                
                return $links;
            });

            return $datatables->rawColums(['active', 'action'])->make(true);
        }
        
        return view('backend.product-amount-series.index')->with(array('product' => $product));
    }

    public function store(Request $request, $productId)
    {
        $result  = ProductAmountSeriesService::create($request->all(), $productId);
        return ProductService::notificationRedirect(array('product.product-amount-series.index', $productId), $result, 'The product amount series is inserted.');
    }

    public function edit(Request $request, $productId, $id)
    {
        $product = ProductService::find($productId);
        $productAmountSeries = ProductAmountSeriesService::find($id);

        return view('backend.product-amount-series.edit')->with(
            array(
                'productAmountSeries' => $productAmountSeries, 
                'product' => $product, 
            )
        );
    }

    public function update(Request $request, $productId, $id)
    {
        $result  = ProductAmountSeriesService::updateById($request->all(), $productId, $id);
        return ProductService::notificationRedirect(array('product.product-amount-series.index', $productId), $result, 'The product amount series is updated.');
    }

    public function destroy($productId, $id)
    {
        $result  = ProductAmountSeriesService::destroy($id);

        if ($result) {
            Notification::success('The product amount series is deleted.');
            return redirect()->route('product.product-amount-series.index', $productId);
        }
    }
}
