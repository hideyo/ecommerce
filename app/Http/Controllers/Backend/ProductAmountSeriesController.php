<?php namespace App\Http\Controllers\Backend;

/**
 * ProductAmountSeriesController
 *
 * This is the controller of the product amount series of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductAmountSeriesRepository;
use Hideyo\Ecommerce\Framework\Services\Product\Entity\ProductRepository;
use Hideyo\Ecommerce\Framework\Services\ExtraField\Entity\ExtraFieldRepository;
use Hideyo\Ecommerce\Framework\Services\Attribute\Entity\AttributeGroupRepository;
use Hideyo\Ecommerce\Framework\Services\TaxRate\Entity\TaxRateRepository;

use Illuminate\Http\Request;
use Notification;
use Response;
use Datatables;
use Form;

class ProductAmountSeriesController extends Controller
{
    public function __construct(
        ProductAmountSeriesRepository $productAmountSeries,
        ProductRepository $product,
        AttributeGroupRepository $attributeGroup,
        TaxRateRepository $taxRate
    ) {
        $this->productAmountSeries = $productAmountSeries;
        $this->product = $product;
        $this->attributeGroup = $attributeGroup;
        $this->taxRate = $taxRate;
    }

    public function index(Request $request, $productId)
    {
        $product = $this->product->find($productId);
        if ($request->wantsJson()) {


            $query = $this->productAmountSeries->getModel()->select(
                ['id', 'series_start', 'series_value', 'active','series_max']
            )->where('product_id', '=', $productId);
            
            $datatables = Datatables::of($query)

            ->addColumn('active', function ($query) {
                if ($query->active) {
                    return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-ok icon-green"></span></a>';
                }
                
                return '<a href="#" class="change-active" data-url="/admin/html-block/change-active/'.$query->id.'"><span class="glyphicon glyphicon-remove icon-red"></span></a>';
            })
            ->addColumn('action', function ($query) use ($productId) {
                $deleteLink = Form::deleteajax(url()->route('product.product-amount-series.destroy', array('productId' => $productId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('product.product-amount-series.edit', array('productId' => $productId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.product-amount-series.index')->with(array('product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
    }

    public function create(Request $request, $productId)
    {
        $product = $this->product->find($productId);

        if ($request->wantsJson()) {
            $input = $request->all();
            $attributeGroup = $this->attributeGroup->find($input['attribute_group_id']);
            if ($attributeGroup->count()) {
                if ($attributeGroup->attributes()) {
                    return Response::json($attributeGroup->attributes);
                }
            }
        } else {
            return view('backend.product-amount-series.create')->with(array('taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'), 'product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
        }
    }

    public function store(Request $request, $productId)
    {
        $result  = $this->productAmountSeries->create($request->all(), $productId);
 
        if (isset($result->id)) {
            Notification::success('The product amount series is updated.');
            return redirect()->route('product.product-amount-series.index', $productId);
        }

        if ($result) {
            foreach ($result->errors()->all() as $error) {
                \Notification::error($error);
            }
        } else {
            \Notification::error('amount series already exist');
        }
        
        return \redirect()->back()->withInput();
    }

    public function edit(Request $request, $productId, $id)
    {
        $product = $this->product->find($productId);
        $productAmountSeries = $this->productAmountSeries->find($id);
        $selectedAttributes = array();
        $attributes = array();

        return view('backend.product-amount-series.edit')->with(array('taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'), 'selectedAttributes' => $selectedAttributes, 'attributes' => $attributes, 'productAmountSeries' => $productAmountSeries, 'product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
    }

    public function update(Request $request, $productId, $id)
    {
        $result  = $this->productAmountSeries->updateById($request->all(), $productId, $id);

        if (!$result->id) {
            return redirect()->back()->withInput()->withErrors($result->errors()->all());
        }
        
        Notification::success('The product amount series is updated.');
        return redirect()->route('product.product-amount-series.index', $productId);
    }

    public function destroy($productId, $id)
    {
        $result  = $this->productAmountSeries->destroy($id);

        if ($result) {
            Notification::success('The product amount series is deleted.');
            return redirect()->route('product.product-amount-series.index', $productId);
        }
    }
}
