<?php namespace App\Http\Controllers\Backend;
/**
 * BrandController
 *
 * This is the controller of the brands of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Repositories\BrandRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class BrandController extends Controller
{
    public function __construct(
        Request $request, 
        BrandRepositoryInterface $brand)
    {
        $this->brand = $brand;
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $brand = $this->brand->getModel()
            ->select(['id', 'rank','title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($brand)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('brand.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('brand.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.brand.index')->with('brand', $this->brand->selectAll());
    }

    public function create()
    {
        return view('backend.brand.create')->with(array());
    }

    public function store()
    {
        $result  = $this->brand->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The brand was inserted.');
            return redirect()->route('brand.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function editSeo($brandId)
    {
        return view('backend.brand.edit_seo')->with(array('brand' => $this->brand->find($brandId)));
    }

    public function edit($brandId)
    {
        return view('backend.brand.edit')->with(array('brand' => $this->brand->find($brandId)));
    }

    public function update($brandId)
    {
        $result  = $this->brand->updateById($this->request->all(), $brandId);

        if (isset($result->id)) {
            if ($this->request->get('seo')) {
                Notification::success('Brand seo was updated.');
                return redirect()->route('brand.edit_seo', $brandId);
            } elseif ($this->request->get('brand-combination')) {
                Notification::success('Brand combination leading attribute group was updated.');
                return redirect()->route('brand.{brandId}.brand-combination.index', $brandId);
            }

            Notification::success('Brand was updated.');
            return redirect()->route('brand.edit', $brandId);            
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }        
       
        return redirect()->back()->withInput();
    }

    public function destroy($brandId)
    {
        $result  = $this->brand->destroy($brandId);
        if ($result) {
            Notification::error('The brand was deleted.');
            return redirect()->route('brand.index');
        }
    }
}
