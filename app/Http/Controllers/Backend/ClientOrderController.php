<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * ClientOrderController
 *
 * This is the controller for the shop clients orders
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Ecommerce\Framework\Services\Client\Entity\ClientRepository;
use Hideyo\Ecommerce\Framework\Services\Client\Entity\ClientAddressRepository;
use Hideyo\Ecommerce\Framework\Services\Order\Entity\OrderRepository;
use Illuminate\Http\Request;
use Form;
use DataTables;

class ClientOrderController extends Controller
{
    public function __construct(
        Request $request, 
        ClientAddressRepository $clientAddress, 
        ClientRepository $client, 
        OrderRepository $order)
    {
        $this->clientAddress = $clientAddress;
        $this->client = $client;
        $this->order = $order;
        $this->request = $request;
    }

    public function index($clientId)
    {
        $client = $this->client->find($clientId);
        if ($this->request->wantsJson()) {

            $order = $this->order->getModel()->select(
                ['id', 'created_at', 'generated_custom_order_id', 'order_status_id', 'client_id', 'delivery_order_address_id', 'bill_order_address_id',
                'price_with_tax']
            )->with(array('orderStatus', 'orderPaymentMethod', 'orderSendingMethod', 'products', 'client', 'orderBillAddress', 'orderDeliveryAddress'))->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('client_id', '=', $clientId);
            
            
            $datatables = DataTables::of($order)

            ->addColumn('status', function ($order) {
                if ($order->orderStatus) {
                     return $order->orderStatus->title;
                }
            })

            ->addColumn('created_at', function ($order) {
                return date('d F H:i', strtotime($order->created_at));
            })
            ->addColumn('status', function ($order) {
                if ($order->orderStatus) {
                    if ($order->orderStatus->color) {
                        return '<a href="/admin/order/'.$order->id.'" style="text-decoration:none;"><span style="background-color:'.$order->orderStatus->color.'; padding: 10px; line-height:30px; text-align:center; color:white;">'.$order->orderStatus->title.'</span></a>';
                    }
                    return $order->orderStatus->title;
                }
            })
            ->addColumn('client', function ($order) {
                if ($order->client) {
                    if ($order->orderBillAddress) {
                        return $order->orderBillAddress->firstname.' '.$order->orderBillAddress->lastname;
                    }
                }
            })
            ->addColumn('products', function ($order) {
                if ($order->products) {
                    return $order->products->count();
                }
            })
            ->addColumn('price_with_tax', function ($order) {
                $money = '&euro; '.$order->getPriceWithTaxNumberFormat();
                return $money;
            })
            ->addColumn('paymentMethod', function ($order) {
                if ($order->orderPaymentMethod) {
                    return $order->orderPaymentMethod->title;
                }
            })
            ->addColumn('sendingMethod', function ($order) {
                if ($order->orderSendingMethod) {
                    return $order->orderSendingMethod->title;
                }
            })
            ->addColumn('action', function ($order) {
                $download = '<a href="/admin/order/'.$order->id.'/download" class="btn btn-default btn-sm btn-info"><i class="entypo-pencil"></i>Download</a>  ';
                $links = '<a href="/admin/order/'.$order->id.'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Show</a>  '.$download;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.client_order.index')->with(array('client' => $client));
    }   
}
