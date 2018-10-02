<?php namespace App\Http\Controllers\Backend;

/**
 * SendingMethodController
 *
 * This is the controller of the sending methods of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\SendingMethodRepository;
use Hideyo\Ecommerce\Framework\Repositories\TaxRateRepository;
use Hideyo\Ecommerce\Framework\Repositories\PaymentMethodRepository;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;
use Auth;

class SendingMethodController extends Controller
{
    public function __construct(
        Request $request, 
        SendingMethodRepository $sendingMethod,
        TaxRateRepository $taxRate,
        PaymentMethodRepository $paymentMethod
    ) {
        $this->request = $request;
        $this->taxRate = $taxRate;
        $this->sendingMethod = $sendingMethod;
        $this->paymentMethod = $paymentMethod;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = $this->sendingMethod->getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = Datatables::of($query)->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('sending-method.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'), $query->title);
                $links = '<a href="'.url()->route('sending-method.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.sending_method.index')->with('sendingMethod', $this->sendingMethod->selectAll());
    }

    public function create()
    {
        return view('backend.sending_method.create')->with(array(
            'taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'),
            'paymentMethods' => $this->paymentMethod->selectAll()->pluck('title', 'id')
        ));
    }

    public function store()
    {
        $result  = $this->sendingMethod->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The sending method was inserted.');
            return redirect()->route('sending-method.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($sendingMethodId)
    {    
        return view('backend.sending_method.edit')->with(
            array(
                'taxRates'          => $this->taxRate->selectAll()->pluck('title', 'id'),
                'sendingMethod'     => $this->sendingMethod->find($sendingMethodId),
                'paymentMethods'    => $this->paymentMethod->selectAll()->pluck('title', 'id'),
            )
        );
    }

    public function update($sendingMethodId)
    {
        $result  = $this->sendingMethod->updateById($this->request->all(), $sendingMethodId);

        if (isset($result->id)) {
            Notification::success('The sending method was updated.');
            return redirect()->route('sending-method.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function destroy($sendingMethodId)
    {
        $result  = $this->sendingMethod->destroy($sendingMethodId);

        if ($result) {
            Notification::success('The sending method was deleted.');
            return redirect()->route('sending-method.index');
        }
    }
}
