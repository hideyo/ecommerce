<?php namespace App\Http\Controllers\Admin;

/**
 * CouponController
 *
 * This is the controller of the sending methods of the shop
 * @author Matthijs Neijenhuijs <matthijs@dutchbridge.nl>
 * @version 1.0
 */

use App\Http\Controllers\Controller;
use Dutchbridge\Repositories\SendingMethodRepositoryInterface;
use Dutchbridge\Repositories\TaxRateRepositoryInterface;
use Dutchbridge\Repositories\PaymentMethodRepositoryInterface;

use Illuminate\Http\Request;
use Notification;

class SendingMethodController extends Controller
{
    public function __construct(
        Request $request, 
        SendingMethodRepositoryInterface $sendingMethod,
        TaxRateRepositoryInterface $taxRate,
        PaymentMethodRepositoryInterface $paymentMethod
    ) {
        $this->request = $request;
        $this->taxRate = $taxRate;
        $this->sendingMethod = $sendingMethod;
        $this->paymentMethod = $paymentMethod;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {
            $query = $this->sendingMethod->getModel()
            ->select([\DB::raw('@rownum  := @rownum  + 1 AS rownum'),'id','title'])
            ->where('shop_id', '=', \Auth::guard('admin')->user()->selected_shop_id);
            
            $datatables = \Datatables::of($query)->addColumn('action', function ($query) {
                $delete = \Form::deleteajax('/admin/sending-method/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $link = '<a href="/admin/sending-method/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$delete;
            
                return $link;
            });

            return $datatables->make(true);
        } else {
            return view('admin.sending_method.index')->with('sendingMethod', $this->sendingMethod->selectAll());
        }
    }

    public function create()
    {
        return view('admin.sending_method.create')->with(array(
            'taxRates' => $this->taxRate->selectAll()->lists('title', 'id'),
            'paymentMethods' => $this->paymentMethod->selectAll()->lists('title', 'id')
        ));
    }

    public function store()
    {
        $result  = $this->sendingMethod->create($this->request->all());

        if (isset($result->id)) {
            \Notification::success('The sending method was inserted.');
            return redirect()->route('admin.sending-method.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            \Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($id)
    {    
        return view('admin.sending_method.edit')->with(array(
            'taxRates' => $this->taxRate->selectAll()->lists('title', 'id'),
            'sendingMethod' => $this->sendingMethod->find($id),
            'paymentMethods' => $this->paymentMethod->selectAll()->lists('title', 'id'),
            ));
    }

    public function update($id)
    {
        $result  = $this->sendingMethod->updateById($this->request->all(), $id);

        if (isset($result->id)) {
            \Notification::success('The sending method was updated.');
            return redirect()->route('admin.sending-method.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            \Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function destroy($id)
    {
        $result  = $this->sendingMethod->destroy($id);

        if ($result) {
            Notification::success('The sending method was deleted.');
            return redirect()->route('admin.sending-method.index');
        }
    }
}