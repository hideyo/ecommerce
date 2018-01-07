<?php namespace App\Http\Controllers\Backend;

/**
 * OrderStatusEmailTemplateController
 *
 * This is the controller of the content weight types of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\OrderStatusEmailTemplateRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\SendingPaymentMethodRelatedRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class OrderStatusEmailTemplateController extends Controller
{
    public function __construct(
        Request $request,
        OrderStatusEmailTemplateRepositoryInterface $orderHtmlTemplate,
        SendingPaymentMethodRelatedRepositoryInterface $sendingPaymentMethodRelatedInterface
    ) {
        $this->request = $request;
        $this->orderHtmlTemplate = $orderHtmlTemplate;
        $this->sendingPaymentMethodRelatedInterface = $sendingPaymentMethodRelatedInterface;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->orderHtmlTemplate->getModel()->select(
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
        
        return view('backend.order-status-email-template.index')->with(array('orderHtmlTemplate' =>  $this->orderHtmlTemplate->selectAll()));
    }

    public function create()
    {
        return view('backend.order-status-email-template.create')->with(array());
    }

    public function store()
    {
        $result  = $this->orderHtmlTemplate->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The template was inserted.');
            return redirect()->route('order-status-email-template.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }

        return redirect()->back()->withInput();
    }

    public function edit($templateId)
    {
        return view('backend.order-status-email-template.edit')->with(array('orderHtmlTemplate' => $this->orderHtmlTemplate->find($templateId)));
    }

    public function showAjaxTemplate($templateId)
    {
        return response()->json($this->orderHtmlTemplate->find($templateId));
    }

    public function update($templateId)
    {
        $result  = $this->orderHtmlTemplate->updateById($this->request->all(), $templateId);

        if (isset($result->id)) {
            Notification::success('template was updated.');
            return redirect()->route('order-status-email-template.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput()->withErrors($result->errors()->all());
    }

    public function destroy($templateId)
    {
        $result  = $this->orderHtmlTemplate->destroy($templateId);

        if ($result) {
            Notification::success('template was deleted.');
            return redirect()->route('order-status-email-template.index');
        }
    }
}
