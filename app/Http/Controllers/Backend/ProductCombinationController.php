<?php namespace App\Http\Controllers\Backend;

/**
 * ProductCombinationController
 *
 * This is the controller of the product combination of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;


use Hideyo\Ecommerce\Framework\Repositories\ProductCombinationRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\ProductRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\ExtraFieldRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\AttributeGroupRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\TaxRateRepositoryInterface;

use \Request;
use \Notification;
use \Redirect;
use \Response;

class ProductCombinationController extends Controller
{
    public function __construct(
        ProductCombinationRepositoryInterface $productCombination,
        ProductRepositoryInterface $product,
        AttributeGroupRepositoryInterface $attributeGroup,
        TaxRateRepositoryInterface $taxRate
    ) {
        $this->productCombination = $productCombination;
        $this->product = $product;
        $this->attributeGroup = $attributeGroup;
        $this->taxRate = $taxRate;
    }

    public function index($productId)
    {
        $product = $this->product->find($productId);

        if($product) {
            if (Request::wantsJson()) {

                $query = $this->productCombination->getModel()->select(
                    ['id', 'tax_rate_id', 'amount', 'price', 'product_id', 'reference_code',
                    'default_on']
                )->where('product_id', '=', $productId);

                $datatables = \Datatables::of($query)->addColumn('action', function ($query) use ($productId) {
                    $deleteLink = \Form::deleteajax(url()->route('product-combination.destroy', array('productId' => $productId, 'id' => $query->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                    $links = '<a href="'.url()->route('product-combination.edit', array('productId' => $productId, 'id' => $query->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                
                    return $links;
                })

                ->addColumn('amount', function ($query) {
                    return '<input type="text" class="change-amount-product-attribute" value="'.$query->amount.'" data-url="/admin/product/'.$query->product_id.'/product-combination/change-amount-attribute/'.$query->id.'">';
                })

                ->addColumn('price', function ($query) {
                    $result = 0;
                    if ($query->price) {

                        $taxRate = 0;
                        $priceInc = 0;
                        $taxValue = 0;

                        if (isset($query->taxRate->rate)) {
                            $taxRate = $query->taxRate->rate;
                            $priceInc = (($query->taxRate->rate / 100) * $query->price) + $query->price;
                            $taxValue = $priceInc - $query->price;
                        }

                        $discountPriceInc = false;
                        $discountPriceEx = false;
                        $discountTaxRate = 0;
                        if ($query->discount_value) {
                            if ($query->discount_type == 'amount') {
                                $discountPriceInc = $priceInc - $query->discount_value;
                                $discountPriceEx = $discountPriceInc / 1.21;
                            } elseif ($query->discount_type == 'percent') {
                                $tax = ($query->discount_value / 100) * $priceInc;
                                $discountPriceInc = $priceInc - $tax;
                                $discountPriceEx = $discountPriceInc / 1.21;
                            }
                            $discountTaxRate = $discountPriceInc - $discountPriceEx;
                            $discountPriceInc = $discountPriceInc;
                            $discountPriceEx = $discountPriceEx;
                        }


                        $output = array(
                            'orginal_price_ex_tax'  => $query->price,
                            'orginal_price_ex_tax_number_format'  => number_format($query->price, 2, '.', ''),
                            'orginal_price_inc_tax' => $priceInc,
                            'orginal_price_inc_tax_number_format' => number_format($priceInc, 2, '.', ''),
                            'tax_rate' => $taxRate,
                            'tax_value' => $taxValue,
                            'currency' => 'EU',
                            'discount_price_inc' => $discountPriceInc,
                            'discount_price_inc_number_format' => number_format($discountPriceInc, 2, '.', ''),
                            'discount_price_ex' => $discountPriceEx,
                            'discount_price_ex_number_format' => number_format($discountPriceEx, 2, '.', ''),
                            'discount_tax_value' => $discountTaxRate,
                            'discount_value' => $query->discount_value,
                            'amount' => $query->amount
                            );

                        $result =  '&euro; '.$output['orginal_price_ex_tax_number_format'].' / &euro; '.$output['orginal_price_inc_tax_number_format'];


                        if ($query->discount_value) {
                            $result .= '<br/> discount: yes';
                        }
                    }

                    return $result;
                })

                ->addColumn('combinations', function ($query) use ($productId) {
                    $items = array();
                    foreach ($query->combinations as $row) {
                        $items[] = $row->attribute->attributeGroup->title.': '.$row->attribute->value;
                    }
           
                    return implode(', ', $items);
                });

                return $datatables->make(true);

            }
            
            return view('backend.product-combination.index')->with(array('product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
        }
        
        return Redirect::route('product.index');            
    }

    public function create($productId)
    {
        $product = $this->product->find($productId);

        if (Request::wantsJson()) {
            $input = Request::all();
            $attributeGroup = $this->attributeGroup->find($input['attribute_group_id']);
            if ($attributeGroup->count()) {
                if ($attributeGroup->attributes()) {
                    return Response::json($attributeGroup->attributes);
                }
            }
        }
        
        return view('backend.product-combination.create')->with(array('taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'), 'product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
    }

    public function changeAmount($productId, $id, $amount)
    {
        $result = $this->productCombination->changeAmount($id, $amount);

        return Response::json($result);
    }

    public function store($productId)
    {
        $result  = $this->productCombination->create(Request::all(), $productId);
 
        if (isset($result->id)) {
            Notification::success('The product extra fields are updated.');
            return Redirect::route('product-combination.index', $productId);
        }

        if ($result) {
            foreach ($result->errors()->all() as $error) {
                \Notification::error($error);
            }
        } else {
            \Notification::error('combination already exist');
        }
        
        return \Redirect::back()->withInput();
    }

    public function edit($productId, $id)
    {
        $product = $this->product->find($productId);
        $productCombination = $this->productCombination->find($id);
        $selectedAttributes = array();
        $attributes = array();
        foreach ($productCombination->combinations as $row) {
            $selectedAttributes[] = $row->attribute->id;
            $attributes[$row->attribute->id]['group_id'] = $row->attribute->attributeGroup->id;
            $attributes[$row->attribute->id]['value'] = $row->attribute->value;
        }

        if (Request::wantsJson()) {
            $input = Request::all();
            $attributeGroup = $this->attributeGroup->find($input['attribute_group_id']);
            if ($attributeGroup->count()) {
                if ($attributeGroup->attributes()) {
                    return Response::json($attributeGroup->attributes);
                }
            }
        } else {
            return view('backend.product-combination.edit')->with(array('taxRates' => $this->taxRate->selectAll()->pluck('title', 'id'), 'selectedAttributes' => $selectedAttributes, 'attributes' => $attributes, 'productCombination' => $productCombination, 'product' => $product, 'attributeGroups' => $this->attributeGroup->selectAll()->pluck('title', 'id')));
        }
    }

    public function update($productId, $id)
    {

        $result  = $this->productCombination->updateById(Request::all(), $productId, $id);

        if (!$result->id) {
            return Redirect::back()->withInput()->withErrors($result->errors()->all());
        }
        
        Notification::success('The product combination is updated.');
        return Redirect::route('product-combination.index', $productId);
    }

    public function destroy($productId, $id)
    {
        $result  = $this->productCombination->destroy($id);

        if ($result) {
            Notification::success('The product combination is deleted.');
            return Redirect::route('product-combination.index', $productId);
        }
    }
}
