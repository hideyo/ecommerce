<?php namespace App\Http\Controllers\Backend;

/**
 * OrderStatusEmailTemplateController
 *
 * This is the controller of the content weight types of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use DataTables;
use Form;
use Hideyo\Ecommerce\Framework\Services\Order\OrderStatusEmailTemplateFacade as OrderStatusEmailTemplateService;

class OrderStatusEmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = OrderStatusEmailTemplateService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = DataTables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax('/admin/order-status-email-template/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/order-status-email-template/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.order-status-email-template.index')->with(array('orderHtmlTemplate' =>  OrderStatusEmailTemplateService::selectAll()));
    }

    public function create()
    {
        return view('backend.order-status-email-template.create')->with(array());
    }

    public function store(Request $request)
    {
        $result  = OrderStatusEmailTemplateService::create($request->all());
        return OrderStatusEmailTemplateService::notificationRedirect('order-status-email-template.index', $result, 'The template was inserted.');
    }

    public function edit($templateId)
    {
        return view('backend.order-status-email-template.edit')->with(array('orderHtmlTemplate' => OrderStatusEmailTemplateService::find($templateId)));
    }

    public function showAjaxTemplate($templateId)
    {
        return response()->json(OrderStatusEmailTemplateService::find($templateId));
    }

    public function update(Request $request, $templateId)
    {
        $result  = OrderStatusEmailTemplateService::updateById($request->all(), $templateId);
        return OrderStatusEmailTemplateService::notificationRedirect('order-status-email-template.index', $result, 'The template was updated.');
    }

    public function destroy($templateId)
    {
        $result  = OrderStatusEmailTemplateService::destroy($templateId);

        if ($result) {
            Notification::success('template was deleted.');
            return redirect()->route('order-status-email-template.index');
        }
    }
}
