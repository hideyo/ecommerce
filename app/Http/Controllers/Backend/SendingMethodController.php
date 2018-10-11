<?php namespace App\Http\Controllers\Backend;

/**
 * SendingMethodController
 *
 * This is the controller of the sending methods of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;
use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class SendingMethodController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = SendingMethodService::getModel()

            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('sending-method.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('sending-method.country-prices.index', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Country prices ('.$query->countryPrices()->count().')</a>  <a href="/admin/sending-method/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            

                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.sending_method.index')->with('sendingMethod', SendingMethodService::selectAll());
    }

    public function create()
    {
        return view('backend.sending_method.create')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'),
            'paymentMethods' => PaymentMethodService::selectAll()->pluck('title', 'id')
        ));
    }

    public function store()
    {
        $result  = SendingMethodService::create($this->request->all());
        return SendingMethodService::notificationRedirect('sending-method.index', $result, 'The sending method was inserted.');
    }

    public function edit($sendingMethodId)
    {    
        return view('backend.sending_method.edit')->with(
            array(
                'taxRates'          => TaxRateService::selectAll()->pluck('title', 'id'),
                'sendingMethod'     => SendingMethodService::find($sendingMethodId),
                'paymentMethods'    => PaymentMethodService::selectAll()->pluck('title', 'id'),
            )
        );
    }

    public function update($sendingMethodId)
    {
        $result  = SendingMethodService::updateById($this->request->all(), $sendingMethodId);
        return SendingMethodService::notificationRedirect('sending-method.index', $result, 'The sending method was updated.');
    }

    public function destroy($sendingMethodId)
    {
        $result  = SendingMethodService::destroy($sendingMethodId);

        if ($result) {
            Notification::success('The sending method was deleted.');
            return redirect()->route('sending-method.index');
        }
    }
}
