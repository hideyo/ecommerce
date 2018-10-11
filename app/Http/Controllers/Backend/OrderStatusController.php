<?php namespace App\Http\Controllers\Backend;

/**
 * OrderStatusController
 *
 * This is the controller of the order statuses of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Form;
use Hideyo\Ecommerce\Framework\Services\Order\OrderStatusEmailTemplateFacade as OrderStatusEmailTemplateService;
use Hideyo\Ecommerce\Framework\Services\Order\OrderStatusFacade as OrderStatusService;

class OrderStatusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = OrderStatusService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = \DataTables::of($query)

            ->addColumn('title', function ($query) {
     
                if ($query->color) {
                    return '<span style="background-color:'.$query->color.'; padding: 10px; line-height:30px; text-align:center; color:white;">'.$query->title.'</span>';
                }
                
                return $query->title;
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax('/admin/order-status/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/order-status/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->rawColumns(['title', 'action'])->make(true);
        }
        
        return view('backend.order-status.index')->with('content', OrderStatusService::selectAll());
    }

    public function create()
    {
        return view('backend.order-status.create')->with(array('templates' => OrderStatusEmailTemplateService::selectAllByShopId(auth('hideyobackend')->user()->selected_shop_id)->pluck('title', 'id')));
    }

    public function store(Request $request)
    {
        $result  = OrderStatusService::create($request->all());
        return OrderStatusService::notificationRedirect('order-status.index', $result, 'The order status was inserted.');
    }

    public function edit($orderStatusId)
    {
        $orderStatus = OrderStatusService::find($orderStatusId);

        $populatedData = array();
           
        return view('backend.order-status.edit')->with(
            array(
            'orderStatus' => $orderStatus,
            'populatedData' => $populatedData,
            'templates' => OrderStatusEmailTemplateService::selectAllByShopId(auth('hideyobackend')->user()->selected_shop_id)->pluck('title', 'id')
            )
        );
    }

    public function update(Request $request, $orderStatusId)
    {
        $result  = OrderStatusService::updateById($request->all(), $orderStatusId);
        return OrderStatusService::notificationRedirect('order-status.index', $result, 'The order status was updated.');
    }

    public function destroy($orderStatusId)
    {
        $result  = OrderStatusService::destroy($orderStatusId);

        if ($result) {
            Notification::success('The order status was deleted.');
            return redirect()->route('order-status.index');
        }
    }
}
