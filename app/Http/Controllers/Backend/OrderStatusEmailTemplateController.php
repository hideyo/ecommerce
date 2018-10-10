<?php namespace App\Http\Controllers\Backend;

/**
 * OrderStatusEmailTemplateController
 *
 * This is the controller of the content weight types of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Order\OrderStatusEmailTemplateFacade as OrderStatusEmailTemplateService;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class OrderStatusEmailTemplateController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;

    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = OrderStatusEmailTemplateService::getModel()->select(
                ['id', 'title', 'subject']
            )->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($query)
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

    public function store()
    {
        $result  = OrderStatusEmailTemplateService::create($this->request->all());
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

    public function update($templateId)
    {
        $result  = OrderStatusEmailTemplateService::updateById($this->request->all(), $templateId);
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
