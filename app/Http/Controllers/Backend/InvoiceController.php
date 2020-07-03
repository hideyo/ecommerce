<?php namespace App\Http\Controllers\Backend;

/**
 * InvoiceController
 *
 * This is the controller of the invoices of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Invoice\InvoiceFacade as InvoiceService;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $invoice = InvoiceService::getModel()->select(
                [
                'id', 'generated_custom_invoice_id', 'order_id',
                'price_with_tax']
            )->with(array('Order'))->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            
            $datatables = \DataTables::of($invoice)
            ->addColumn('price_with_tax', function ($order) {
                $money = '&euro; '.$order->price_with_tax;
                return $money;
            })
            ->addColumn('action', function ($invoice) {
                $deleteLink = \Form::deleteajax('/invoice/'. $invoice->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $download = '<a href="/invoice/'.$invoice->id.'/download" class="btn btn-default btn-sm btn-info"><i class="entypo-pencil"></i>Download</a>  ';
                $links = '<a href="/invoice/'.$invoice->id.'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Show</a>  '.$download;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.invoice.index')->with('invoice', InvoiceService::selectAll());
    }

    public function show($invoiceId)
    {
        return view('backend.invoice.show')->with('invoice', InvoiceService::find($invoiceId));
    }

    public function download($invoiceId)
    {
        $invoice = InvoiceService::find($invoiceId);
        $pdf = \PDF::loadView('invoice.pdf', array('invoice' => $invoice));
        return $pdf->download('invoice-'.$invoice->generated_custom_invoice_id.'.pdf');
    }

    public function create()
    {
        return view('backend.invoice.create')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'),
            'paymentMethods' => PaymentMethodService::selectAll()->pluck('title', 'id')
        ));
    }

    public function store(Request $request)
    {
        $result  = InvoiceService::create($request->all());

        if (isset($result->id)) {
            flash('The invoice was inserted.');
            return redirect()->route('sending-method.index');
        }
        
        flash($result->errors()->all());
        return redirect()->back()->withInput();
    }

    public function edit($invoiceId)
    {
        return view('backend.invoice.edit')->with(array(
            'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'),
            'invoice' => InvoiceService::find($invoiceId),
            'paymentMethods' => PaymentMethodService::selectAll()->pluck('title', 'id'),
        ));
    }

    public function update(Request $request, $invoiceId)
    {
        $result  = InvoiceService::updateById($request->all(), $invoiceId);

        if (isset($result->id)) {
            flash('The invoice was updated.');
            return redirect()->route('sending-method.index');
        }
        
        flash($result->errors()->all());
        return redirect()->back()->withInput();
    }

    public function destroy($invoiceId)
    {
        $result  = InvoiceService::destroy($invoiceId);

        if ($result) {
            flash('The invoice was deleted.');
            return Redirect::route('sending-method.index');
        }
    }
}
