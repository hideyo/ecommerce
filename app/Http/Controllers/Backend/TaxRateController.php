<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;
use Auth;

class TaxRateController extends Controller
{
    public function __construct(
        Request $request)
    {

        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = TaxRateService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('tax-rate.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('tax-rate.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.tax_rate.index')->with('taxRate', TaxRateService::selectAll());
    }

    public function create()
    {
        return view('backend.tax_rate.create')->with(array());
    }

    public function store()
    {
        $result  = TaxRateService::create($this->request->all());
        return TaxRateService::notificationRedirect('tax-rate.index', $result, 'The tax rate was inserted.');
    }

    public function edit($taxRateId)
    {
        return view('backend.tax_rate.edit')->with(array('taxRate' => TaxRateService::find($taxRateId)));
    }

    public function update($taxRateId)
    {
        $result  = TaxRateService::updateById($this->request->all(), $taxRateId);
        return TaxRateService::notificationRedirect('tax-rate.index', $result, 'The tax rate was updated.');
    }

    public function destroy($taxRateId)
    {
        $result  = TaxRateService::destroy($taxRateId);
        if ($result) {
            Notification::error('The tax rate was deleted.');
            return redirect()->route('tax-rate.index');
        }
    }
}
