<?php namespace App\Http\Controllers\Backend;

/**
 * PaymentMethodController
 *
 * This is the controller for the shop payment methods
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */
use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\PaymentMethodRepository;
use Hideyo\Ecommerce\Framework\Repositories\TaxRateRepository;
use Hideyo\Ecommerce\Framework\Repositories\OrderStatusRepository;


use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;
use Auth;

class PaymentMethodController extends Controller
{
    public function __construct(
        Request $request, 
        PaymentMethodRepository $paymentMethod,
        TaxRateRepository $taxRate,
        OrderStatusRepository $orderStatus
    ) {
        $this->request = $request;
        $this->taxRate = $taxRate;
        $this->paymentMethod = $paymentMethod;
        $this->orderStatus = $orderStatus;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->paymentMethod->getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
            ->with(array('orderConfirmedOrderStatus', 'orderPaymentCompletedOrderStatus', 'orderPaymentFailedOrderStatus'));
            
            $datatables = Datatables::of($query)

            ->addColumn('orderconfirmed', function ($query) {
                if ($query->orderConfirmedOrderStatus) {
                    return $query->orderConfirmedOrderStatus->title;
                }
            })
            ->addColumn('paymentcompleted', function ($query) {
                if ($query->orderPaymentCompletedOrderStatus) {
                    return $query->orderPaymentCompletedOrderStatus->title;
                }
            })
            ->addColumn('paymentfailed', function ($query) {
                if ($query->orderPaymentFailedOrderStatus) {
                    return $query->orderPaymentFailedOrderStatus->title;
                }
            })
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('payment-method.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'));
                $links = '<a href="'.url()->route('payment-method.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });


            return $datatables->make(true);
        }
        
        return view('backend.payment_method.index')->with('paymentMethod', $this->paymentMethod->selectAll());
    }

    public function create()
    {
        return view('backend.payment_method.create')->with(
            array(
                'taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'),
                'orderStatuses' => $this->orderStatus->selectAll()->pluck('title', 'id')                
            )
        );
    }

    public function store()
    {
        $result  = $this->paymentMethod->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The payment method was inserted.');
            return redirect()->route('payment-method.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($paymentMethodId)
    {
        return view('backend.payment_method.edit')->with(
            array(
                'paymentMethod' => $this->paymentMethod->find($paymentMethodId),
                'taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'),
                'orderStatuses' => $this->orderStatus->selectAll()->pluck('title', 'id')
            )
        );
    }

    public function update($paymentMethodId)
    {
        $result  = $this->paymentMethod->updateById($this->request->all(), $paymentMethodId);

        if (isset($result->id)) {
            Notification::success('The payment method was updated.');
            return redirect()->route('payment-method.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function destroy($paymentMethodId)
    {
        $result  = $this->paymentMethod->destroy($paymentMethodId);

        if ($result) {
            Notification::success('The payment method was deleted.');
            return redirect()->route('payment-method.index');
        }
    }
}
