<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Dutchbridge\Repositories\BrandRepositoryInterface;

use Illuminate\Http\Request;
use Notification;

class BrandController extends Controller
{
    public function __construct(Request $request, BrandRepositoryInterface $brand)
    {
        $this->brand = $brand;
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $brand = $this->brand->getModel()
            ->select([\DB::raw('@rownum  := @rownum  + 1 AS rownum'),'id', 'rank','title'])
            ->where('shop_id', '=', \Auth::guard('admin')->user()->selected_shop_id);
            
            $datatables = \Datatables::of($brand)->addColumn('action', function ($brand) {
                $delete = \Form::deleteajax('/admin/brand/'. $brand->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'), $brand->title);
                $link = '<a href="/admin/brand/'.$brand->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$delete;
            
                return $link;
            });

            return $datatables->make(true);
        } else {
            return view('admin.brand.index')->with('brand', $this->brand->selectAll());
        }
    }

    public function create()
    {
        return view('admin.brand.create')->with(array());
    }

    public function store()
    {
        $result  = $this->brand->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The brand was inserted.');
            return redirect()->route('admin.brand.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            \Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function editSeo($id)
    {
        return view('admin.brand.edit_seo')->with(array('brand' => $this->brand->find($id)));
    }

    public function edit($id)
    {
        return view('admin.brand.edit')->with(array('brand' => $this->brand->find($id)));
    }

    public function update($brandId)
    {
        $result  = $this->brand->updateById($this->request->all(), $brandId);

        if (isset($result->id)) {
            if ($this->request->get('seo')) {
                Notification::success('Brand seo was updated.');
                return redirect()->route('admin.brand.edit_seo', $brandId);
            } elseif ($this->request->get('brand-combination')) {
                Notification::success('Brand combination leading attribute group was updated.');
                return redirect()->route('admin.brand.{brandId}.brand-combination.index', $brandId);
            } else {
                Notification::success('Brand was updated.');
                return redirect()->route('admin.brand.edit', $brandId);
            }
        }

        foreach ($result->errors()->all() as $error) {
            \Notification::error($error);
        }        
       
        return redirect()->back()->withInput();
    }

    public function destroy($id)
    {
        $result  = $this->brand->destroy($id);
        if ($result) {
            Notification::error('The brand was deleted.');
            return redirect()->route('admin.brand.index');
        }
    }
}