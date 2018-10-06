<?php namespace App\Http\Controllers\Backend;

/**
 * ProductCategoryImageController
 *
 * This is the controller of the product category images of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;


class ProductCategoryImageController extends Controller
{
    public function index(Request $request, $productCategoryId)
    {
        $productCategory = ProductCategoryService::find($productCategoryId);
        if ($request->wantsJson()) {

            $image = ProductCategoryService::getImageModel()->select(
                ['id','file', 'product_category_id']
            )->where('product_category_id', '=', $productCategoryId);
            
            $datatables = Datatables::of($image)

            ->addColumn('thumb', function ($image) use ($productCategoryId) {
                return '<img src="/files/product_category/100x100/'.$image->product_category_id.'/'.$image->file.'"  />';
            })
            ->addColumn('action', function ($image) use ($productCategoryId) {
                $deleteLink = Form::deleteajax(url()->route('product-category.images.destroy', array('productCategoryId' => $productCategoryId, 'id' => $image->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('product-category.images.edit', array('productCategoryId' => $productCategoryId, 'id' => $image->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        


        }
        
        return view('backend.product_category_image.index')->with(array('productCategory' => $productCategory));
    }

    public function create($productCategoryId)
    {
        $productCategory = ProductCategoryService::find($productCategoryId);

        return view('backend.product_category_image.create')->with(array('productCategory' => $productCategory));
    }

    public function store(Request $request, $productCategoryId)
    {
        $result  = ProductCategoryService::createImage($request->all(), $productCategoryId);
 
        if (isset($result->id)) {
            Notification::success('The category image was inserted.');
            return redirect()->route('product-category.images.index', $productCategoryId);
        } else {
            foreach ($result->errors()->all() as $error) {
                Notification::error($error);
            }
            return redirect()->back()->withInput()->withErrors($result);
        }
    }

    public function edit(Request $request, $productCategoryId, $productCategoryImageId)
    {
        $productCategory = ProductCategoryService::find($productCategoryId);
        return view('backend.product_category_image.edit')->with(array('productCategoryImage' => ProductCategoryService::findImage($productCategoryImageId), 'productCategory' => $productCategory));
    }

    public function update(Request $request, $productCategoryId, $productCategoryImageId)
    {
        $result  = ProductCategoryService::updateImageById($request->all(), $productCategoryId, $productCategoryImageId);

        if (isset($result->id)) {
            Notification::success('The category image was updated.');
            return redirect()->route('product-category.images.index', $productCategoryId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);
    }

    public function destroy($productCategoryId, $productCategoryImageId)
    {
        $result  = ProductCategoryService::destroyImage($productCategoryImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('product-category.images.index', $productCategoryId);
        }
    }
}
