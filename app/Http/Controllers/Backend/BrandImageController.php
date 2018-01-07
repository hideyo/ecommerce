<?php namespace App\Http\Controllers\Backend;
/**
 * BrandImageController
 *
 * This is the controller for the images of a brand item
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\BrandRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class BrandImageController extends Controller
{
    public function __construct(Request $request, BrandRepositoryInterface $brand)
    {
        $this->brand = $brand;
        $this->request = $request;
    }

    public function index($brandId)
    {
        $brand = $this->brand->find($brandId);
        if ($this->request->wantsJson()) {

            $image = $this->brand->getModelImage()
            ->select(['id','file', 'brand_id'])
            ->where('brand_id', '=', $brandId);
            
            $datatables = Datatables::of($image)

            ->addColumn('thumb', function ($image) use ($brandId) {
                return '<img src="'.config('hideyo.public_path').'/brand/100x100/'.$image->brand_id.'/'.$image->file.'"  />';
            })
            ->addColumn('action', function ($image) use ($brandId) {
                $deleteLink = Form::deleteajax(url()->route('brand-image.destroy', array('brandId' => $brandId, 'id' => $image->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('brand-image.edit', array('brandId' => $brandId, 'id' => $image->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.brand_image.index')->with(array( 'brand' => $brand));
    }

    public function create($brandId)
    {
        $brand = $this->brand->find($brandId);
        return view('backend.brand_image.create')->with(array('brand' => $brand));
    }

    public function store($brandId)
    {
        $result  = $this->brand->createImage($this->request->all(), $brandId);
 
        if (isset($result->id)) {
            Notification::success('The brand image was inserted.');
            return redirect()->route('brand-image.index', $brandId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);
    }

    public function edit($brandId, $brandImageId)
    {
        $brand = $this->brand->find($brandId);
        return view('backend.brand_image.edit')->with(array('brandImage' => $this->brand->findImage($brandImageId), 'brand' => $brand));
    }

    public function update($brandId, $brandImageId)
    {
        $result  = $this->brand->updateImageById($this->request->all(), $brandId, $brandImageId);

        if (isset($result->id)) {
            Notification::success('The brand image was updated.');
            return redirect()->route('brand-image.index', $brandId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);
    }

    public function destroy($brandId, $brandImageId)
    {
        $result  = $this->brand->destroyImage($brandImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('brand-image.index', $brandId);
        }
    }
}
