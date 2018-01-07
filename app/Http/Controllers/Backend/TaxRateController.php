<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\TaxRateRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;
use Auth;

class TaxRateController extends Controller
{
    public function __construct(
        Request $request, 
        TaxRateRepositoryInterface $taxRate)
    {
        $this->taxRate = $taxRate;
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = $this->taxRate->getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('tax-rate.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('tax-rate.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.tax_rate.index')->with('taxRate', $this->taxRate->selectAll());
    }

    public function create()
    {
        return view('backend.tax_rate.create')->with(array());
    }

    public function store()
    {
        $result  = $this->taxRate->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The tax rate was inserted.');
            return redirect()->route('tax-rate.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function edit($taxRateId)
    {
        return view('backend.tax_rate.edit')->with(array('taxRate' => $this->taxRate->find($taxRateId)));
    }

    public function update($taxRateId)
    {
        $result  = $this->taxRate->updateById($this->request->all(), $taxRateId);

        if (isset($result->id)) {
            Notification::success('The tax rate was updated.');
            return redirect()->route('tax-rate.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput();
    }

    public function destroy($taxRateId)
    {
        $result  = $this->taxRate->destroy($taxRateId);
        if ($result) {
            Notification::error('The tax_rate was deleted.');
            return redirect()->route('tax-rate.index');
        }
    }
}
