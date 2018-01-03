<?php namespace App\Http\Controllers\Backend;

/**
 * SendingPaymentMethodRelatedController
 *
 * This is the controller of the sending payment method related of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */
use App\Http\Controllers\Controller;
use Hideyo\Repositories\SendingPaymentMethodRelatedRepositoryInterface;
use Hideyo\Repositories\TaxRateRepositoryInterface;
use Hideyo\Repositories\PaymentMethodRepositoryInterface;
use DB;
use Request;
use Datatables;
use Notification;

class SendingPaymentMethodRelatedController extends Controller
{
    public function __construct(SendingPaymentMethodRelatedRepositoryInterface $sendingPaymentMethodRelated)
    {
        $this->sendingPaymentMethodRelated = $sendingPaymentMethodRelated;
    }

    public function index()
    {
        if (Request::wantsJson()) {

            $query = DB::table('sending_payment_method_related')->join('sending_method', 'sending_payment_method_related.sending_method_id', '=', 'sending_method.id')->join('payment_method', 'sending_payment_method_related.payment_method_id', '=', 'payment_method.id')
                ->select(['payment_method.title as payment_method_title', 
                    'sending_method.title as sending_method_title', 
                    'sending_payment_method_related.*'])
               ->where('sending_method.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            $datatables = Datatables::of($query)

            ->addColumn('payment_method', function ($query) {
                  return $query->payment_method_title;
            })
            ->addColumn('sending_method', function ($query) {
                  return $query->sending_method_title;
            })

            ->addColumn('pdf_text', function ($query) {
                
                $result = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

                if ($query->pdf_text) {
                    $result = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                }

                return $result;
            })
            ->addColumn('payment_text', function ($query) {
                
                $result = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

                if ($query->payment_text) {
                    $result = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                }

                return $result;
            })
            ->addColumn('payment_confirmed_text', function ($query) {
                
                $result = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

                if ($query->payment_confirmed_text) {
                    $result = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                }

                return $result;
            })
            

            ->addColumn('action', function ($query) {
                $links = '<a href="'.url()->route('sending-payment-method-related.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>';
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.sending_payment_method_related.index');
    }

    public function edit($sendingPaymentRelatedId)
    {
        return view('backend.sending_payment_method_related.edit')->with(
            array(
                'sendingPaymentMethodRelated' => $this->sendingPaymentMethodRelated->find($sendingPaymentRelatedId)
            )
        );
    }

    public function update($sendingPaymentRelatedId)
    {
        $result  = $this->sendingPaymentMethodRelated->updateById(Request::all(), $sendingPaymentRelatedId);

        if (isset($result->id)) {
            Notification::success('The order template was updated.');
            return redirect()->route('sending-payment-method-related.index');
        }
        
        Notification::error($result->errors()->all());
        return redirect()->back()->withInput();
    }
}
