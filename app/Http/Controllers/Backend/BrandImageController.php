<?php namespace App\Http\Controllers\Backend;
/**
 * BrandImageController
 *
 * This is the controller for the images of a brand item
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Brand\BrandFacade as BrandService;

class BrandImageController extends Controller
{
    public function index(Request $request, $brandId)
    {
        $brand = BrandService::find($brandId);
        if ($request->wantsJson()) {

            $image = BrandService::getModelImage()->where('brand_id', '=', $brandId);
            
            $datatables = Datatables::of($image)

            ->addColumn('thumb', function ($image) {
                return '<img src="'.config('hideyo.public_path').'/brand/100x100/'.$image->brand_id.'/'.$image->file.'"  />';
            })
            ->addColumn('action', function ($image) use ($brandId) {
                $deleteLink = Form::deleteajax(url()->route('brand.images.destroy', array('brandId' => $brandId, 'id' => $image->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('brand.images.edit', array('brandId' => $brandId, 'id' => $image->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.brand_image.index')->with(array( 'brand' => $brand));
    }

    public function create($brandId)
    {
        $brand = BrandService::find($brandId);
        return view('backend.brand_image.create')->with(array('brand' => $brand));
    }

    public function store(Request $request, $brandId)
    {
        $result  = BrandService::createImage($request->all(), $brandId);
        return BrandService::notificationRedirect(array('brand.images.index', $brandId), $result, 'The brand image was inserted.');
    }

    public function edit($brandId, $brandImageId)
    {
        $brand = BrandService::find($brandId);
        return view('backend.brand_image.edit')->with(array('brandImage' => BrandService::findImage($brandImageId), 'brand' => $brand));
    }

    public function update(Request $request, $brandId, $brandImageId)
    {
        $result  = BrandService::updateImageById($request->all(), $brandId, $brandImageId);
        return BrandService::notificationRedirect(array('brand.images.index', $brandId), $result, 'The brand image was updated.');
    }

    public function destroy($brandId, $brandImageId)
    {
        $result  = BrandService::destroyImage($brandImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('brand.images.index', $brandId);
        }
    }
}
