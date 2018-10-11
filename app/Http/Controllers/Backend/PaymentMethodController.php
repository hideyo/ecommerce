<?php namespace App\Http\Controllers\Backend;

/**
 * PaymentMethodController
 *
 * This is the controller for the shop payment methods
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */
use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;
use Hideyo\Ecommerce\Framework\Services\Order\Entity\OrderStatusRepository;


use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class PaymentMethodController extends Controller
{
    public function __construct(
        OrderStatusRepository $orderStatus
    ) {
        $this->orderStatus = $orderStatus;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = PaymentMethodService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
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
        
        return view('backend.payment_method.index')->with('paymentMethod', PaymentMethodService::selectAll());
    }

    public function create()
    {
        return view('backend.payment_method.create')->with(
            array(
                'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'),
                'orderStatuses' => $this->orderStatus->selectAll()->pluck('title', 'id')                
            )
        );
    }

    public function store(Request $request)
    {
        $result  = PaymentMethodService::create($request->all());
        return PaymentMethodService::notificationRedirect('payment-method.index', $result, 'The payment method was inserted.');
    }

    public function edit($paymentMethodId)
    {
        return view('backend.payment_method.edit')->with(
            array(
                'paymentMethod' => PaymentMethodService::find($paymentMethodId),
                'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'),
                'orderStatuses' => $this->orderStatus->selectAll()->pluck('title', 'id')
            )
        );
    }

    public function update(Request $request, $paymentMethodId)
    {
        $result  = PaymentMethodService::updateById($request->all(), $paymentMethodId);
        return PaymentMethodService::notificationRedirect('payment-method.index', $result, 'The payment method was updated.');
    }

    public function destroy($paymentMethodId)
    {
        $result  = PaymentMethodService::destroy($paymentMethodId);

        if ($result) {
            Notification::success('The payment method was deleted.');
            return redirect()->route('payment-method.index');
        }
    }
}
