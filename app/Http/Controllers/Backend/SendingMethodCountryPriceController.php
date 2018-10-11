<?php namespace App\Http\Controllers\Backend;

/**
 * CouponController
 *
 * This is the controller of the sending methods of the shop
 * @author Matthijs Neijenhuijs <matthijs@dutchbridge.nl>
 * @version 1.0
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;

use Illuminate\Http\Request;
use Notification;
use Input;
use Excel;

class SendingMethodCountryPriceController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function index($sendingMethodId)
    {
        if ($this->request->wantsJson()) {

            $users = SendingMethodService::getCountryModel()->select([
                
                'id',
                'name']
            )->where('sending_method_id', '=', $sendingMethodId);
            
            $datatables = \Datatables::of($users)->addColumn('action', function ($users) use ($sendingMethodId) {
                $delete = \Form::deleteajax(url()->route('sending-method.country-prices.destroy', array('sendingMethodId' => $sendingMethodId, 'id' => $users->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $link = '<a href="'.url()->route('sending-method.country-prices.edit', array('sendingMethodId' => $sendingMethodId, 'id' => $users->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$delete;
            
                return $link;
            });

            return $datatables->make(true);
        }
        
        return view('backend.sending_method_country_price.index')->with('sendingMethod', SendingMethodService::find($sendingMethodId));
    }

    public function create($sendingMethodId)
    {
        return view('backend.sending_method_country_price.create')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('rate', 'id'),
            'sendingMethod' => SendingMethodService::find($sendingMethodId)
        ));
    }

    public function getImport($sendingMethodId)
    {
        return view('backend.sending_method_country_price.import')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('rate', 'id'),
            'sendingMethod' => SendingMethodService::find($sendingMethodId)
        ));
    }

    public function postImport($sendingMethodId)
    {
        $file = $this->request->file('file');
        $countries = \Excel::load($file, function($reader) {
        })->get();

        if($countries) {
            $result  = SendingMethodService::importCountries($countries, $this->request->get('tax_rate_id'), $sendingMethodId);
            Notification::success('The countries are inserted.');
            return redirect()->route('sending-method.country-prices.index', $sendingMethodId);
        }         
    }

    public function store($sendingMethodId)
    {
        $result  = SendingMethodService::createCountry($this->request->all(), $sendingMethodId);
        return SendingMethodService::notificationRedirect(array('sending-method.country-prices.index', $sendingMethodId), $result, 'The country was inserted.');
    }

    public function edit($sendingMethodId, $id)
    {
        return view('backend.sending_method_country_price.edit')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('rate', 'id'),
            'sendingMethod' => SendingMethodService::find($sendingMethodId),
            'sendingMethodCountry' => SendingMethodService::findCountry($id)
            ));
    }

    public function update($sendingMethodId, $id)
    {
        $result  = SendingMethodService::updateCountryById($this->request->all(), $id);
        return SendingMethodService::notificationRedirect(array('sending-method.country-prices.index', $sendingMethodId), $result, 'The country was updated.');
    }

    public function destroy($sendingMethodId, $id)
    {
        $result  = SendingMethodService::destroyCountry($id);

        if ($result) {
            Notification::success('The country price was deleted.');
            return redirect()->route('sending-method.country-prices.index', $sendingMethodId);
        }
    }
}