<?php namespace App\Http\Controllers\Backend;

/**
 * ProductController
 *
 * This is the controller of the products of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\Product\ProductCombinationFacade as ProductCombinationService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;
use Hideyo\Ecommerce\Framework\Services\TaxRate\TaxRateFacade as TaxRateService;
use Hideyo\Ecommerce\Framework\Services\Brand\BrandFacade as BrandService;
use Illuminate\Http\Request;
use Notification;
use Excel;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $product = ProductService::getModel()->select(
                ['product.*', 
                'brand.title as brandtitle', 
                'product_category.title as categorytitle']
            )->with(array('productCategory', 'brand', 'subcategories', 'attributes',  'productImages','taxRate'))

            ->leftJoin('product_category as product_category', 'product_category.id', '=', 'product.product_category_id')

            ->leftJoin('brand as brand', 'brand.id', '=', 'product.brand_id')

            ->where('product.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = \DataTables::of($product)
            ->filterColumn('reference_code', function ($query, $keyword) {
                $query->whereRaw("product.reference_code like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('active', function ($query, $keyword) {
                $query->whereRaw("product.active like ?", ["%{$keyword}%"]);
                ;
            })

            ->addColumn('rank', function ($product) {
                return '<input type="text" class="change-rank" value="'.$product->rank.'" style="width:50px;" data-url="/admin/product/change-rank/'.$product->id.'">';
              
            })

            ->filterColumn('title', function ($query, $keyword) {

                $query->where(
                    function ($query) use ($keyword) {
                        $query->whereRaw("product.title like ?", ["%{$keyword}%"]);
                        $query->orWhereRaw("product.reference_code like ?", ["%{$keyword}%"]);
                             $query->orWhereRaw("brand.title like ?", ["%{$keyword}%"]);
                        ;
                    }
                );
            })

            ->filterColumn('categorytitle', function ($query, $keyword) {
                $query->whereRaw("product_category.title like ?", ["%{$keyword}%"]);
            })

            ->addColumn('active', function ($product) {
                if ($product->active) {
                    return '<a href="#" class="change-active" data-url="'.url()->route('product.change-active', array('productId' => $product->id)).'"><span class="glyphicon glyphicon-ok icon-green"></span></a>';
                }
                
                return '<a href="#" class="change-active" data-url="'.url()->route('product.change-active', array('productId' => $product->id)).'"><span class="glyphicon glyphicon-remove icon-red"></span></a>';
            })

            ->addColumn('title', function ($product) {
                if ($product->brand) {
                    return $product->brand->title.' | '.$product->title;
                }
                
                return $product->title;
            })


            ->addColumn('amount', function ($product) {
                if ($product->attributes->count()) {
                    return '<a href="/admin/product/'.$product->id.'/product-combination">combinations</a>';
                }
                
                return '<input type="text" class="change-amount" value="'.$product->amount.'" style="width:50px;" data-url="'.url()->route('product.change-amount', array('productId' => $product->id)).'">';
            })

            ->addColumn('image', function ($product) {
                if ($product->productImages->count()) {
                    return '<img src="/files/product/100x100/'.$product->id.'/'.$product->productImages->first()->file.'"  />';
                }
            })
            ->addColumn('price', function ($product) {

                $result = "";
                if ($product->price) {

                    $taxRate = 0;
                    $priceInc = 0;
                    $taxValue = 0;

                    if (isset($product->taxRate->rate)) {
                        $taxRate = $product->taxRate->rate;
                        $priceInc = (($product->taxRate->rate / 100) * $product->price) + $product->price;
                        $taxValue = $priceInc - $product->price;
                    }

                    $discountPriceInc = false;
                    $discountPriceEx = false;
                    $discountTaxRate = 0;
                    if ($product->discount_value) {
                        if ($product->discount_type == 'amount') {
                            $discountPriceInc = $priceInc - $product->discount_value;
                            $discountPriceEx = $discountPriceInc / 1.21;
                        } elseif ($product->discount_type == 'percent') {
                            $tax = ($product->discount_value / 100) * $priceInc;
                            $discountPriceInc = $priceInc - $tax;
                            $discountPriceEx = $discountPriceInc / 1.21;
                        }
                        $discountTaxRate = $discountPriceInc - $discountPriceEx;
                        $discountPriceInc = $discountPriceInc;
                        $discountPriceEx = $discountPriceEx;
                    }


                    $output = array(
                        'orginal_price_ex_tax'  => $product->price,
                        'orginal_price_ex_tax_number_format'  => number_format($product->price, 2, '.', ''),
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
                        'discount_value' => $product->discount_value,
                        'amount' => $product->amount
                        );

                    $result =  '&euro; '.$output['orginal_price_ex_tax_number_format'].' / &euro; '.$output['orginal_price_inc_tax_number_format'];


                    if ($product->discount_value) {
                        $result .= '<br/> discount: yes';
                    }
                }

                return $result;
            })


            ->addColumn('categorytitle', function ($product) {
                if ($product->subcategories()->count()) {
                    $subcategories = $product->subcategories()->pluck('title')->toArray();
                    return $product->categorytitle.', <small> '.implode(', ', $subcategories).'</small>';
                }
                
                return $product->categorytitle;
            })

            ->addColumn('action', function ($product) {
                $deleteLink = \Form::deleteajax(url()->route('product.destroy', $product->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'), $product->title);
                $copy = '<a href="'.url()->route('product.copy', $product->id).'" class="btn btn-default btn-sm btn-info"><i class="entypo-pencil"></i>Copy</a>';

                $links = '<a href="'.url()->route('product.edit', $product->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$copy.' '.$deleteLink;

                return $links;
            });

            return $datatables->rawColumns(['action', 'active', 'amount', 'categorytitle', 'image'])->make(true);
        }
        
        return view('backend.product.index')->with('product', ProductService::selectAll());
    }

    public function getRank(Request $request)
    {
        if ($request->wantsJson()) {

            $product = ProductService::getModel()->select(
                ['product.*', 
                'brand.title as brandtitle', 
                'product_category.title as categorytitle']
            )->with(array('productCategory', 'brand', 'subcategories', 'attributes',  'productImages','taxRate'))
            ->leftJoin('product_category as product_category', 'product_category.id', '=', 'product.product_category_id')
            ->leftJoin('brand as brand', 'brand.id', '=', 'product.brand_id')
            ->where('product.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = \DataTables::of($product)
            ->addColumn('rank', function ($product) {
                return '<input type="text" class="change-rank" value="'.$product->rank.'" style="width:50px;" data-url="'.url()->route('product.change-rank', array('productId' => $product->id)).'">';
            })
            ->filterColumn('categorytitle', function ($query, $keyword) {
                $query->whereRaw("product_category.title like ?", ["%{$keyword}%"]);
            })
            ->addColumn('title', function ($product) {
                if ($product->brand) {
                    return $product->brand->title.' | '.$product->title;
                }
                
                return $product->title;      
            })
            ->addColumn('categorytitle', function ($product) {
                if ($product->subcategories()->count()) {
                    $subcategories = $product->subcategories()->pluck('title')->toArray();
                    return $product->categorytitle.', <small> '.implode(', ', $subcategories).'</small>';
                }
                
                return $product->categorytitle;
            });

            return $datatables->make(true);

        }
        
        return view('backend.product.rank')->with('product', ProductService::selectAll());
    }

    public function refactorAllImages()
    {
        $this->productImage->refactorAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);
        return redirect()->route('product.index');
    }

    public function create()
    {
        return view('backend.product.create')->with(array('brands' => BrandService::selectAll()->pluck('title', 'id')->toArray(), 'taxRates' => TaxRateService::selectAll()->pluck('title', 'id'), 'productCategories' => ProductCategoryService::selectAllProductPullDown()->pluck('title', 'id')));
    }

    public function store(Request $request)
    {
        $result  = ProductService::create($request->all());
        return ProductService::notificationRedirect('product.index', $result, 'The product was inserted.');
    }

    public function changeActive($productId)
    {
        $result = ProductService::changeActive($productId);
        return response()->json($result);
    }

    public function changeAmount($productId, $amount)
    {
        $result = ProductService::changeAmount($productId, $amount);
        return response()->json($result);
    }


    public function changeRank($productId, $rank = 0)
    {
        $result = ProductService::changeRank($productId, $rank);
        return response()->json($result);
    }

    public function edit($productId)
    {
        $product = ProductService::find($productId);

        return view('backend.product.edit')->with(
            array(
            'product' => $product,
            'brands' => BrandService::selectAll()->pluck('title', 'id')->toArray(),
            'productCategories' => ProductCategoryService::selectAllProductPullDown()->pluck('title', 'id'),
            'taxRates' => TaxRateService::selectAll()->pluck('title', 'id')
            )
        );
    }

    public function getExport()
    {
        return view('backend.product.export')->with(array());
    }

    public function postExport()
    {

        $result  =  ProductService::selectAllExport();
        Excel::create('export', function ($excel) use ($result) {

            $excel->sheet('Products', function ($sheet) use ($result) {
                $newArray = array();
                foreach ($result as $row) {
                    $category = "";
                    if ($row->productCategory) {
                        $category = $row->productCategory->title;
                    }

                    $priceDetails = $row->getPriceDetails();


                    $newArray[$row->id] = array(
                    'title' => $row->title,
                    'category' => $category,
                    'amount' => $row->amount,
                    'reference_code' => $row->reference_code,
                    'orginal_price_ex_tax_number_format' => $priceDetails['orginal_price_ex_tax_number_format'],
                    'orginal_price_inc_tax_number_format' => $priceDetails['orginal_price_inc_tax_number_format'],
                    'tax_rate' => $priceDetails['tax_rate'],
                    'currency' => $priceDetails['currency']

                    );


                    $images = array();
                    if ($row->productImages->count()) {
                        $i = 0;
                        foreach ($row->productImages as $image) {
                            $i++;
                            $newArray[$row->id]['image_'.$i] =  url('/').'/files/product/800x800/'.$row->id.'/'.$image->file;
                        }
                    }
                }

                $sheet->fromArray($newArray);
            });
        })->download('xls');


        Notification::success('The product export is completed.');
        return redirect()->route('product.index');
    }

    public function copy($productId)
    {
        $product = ProductService::find($productId);

        return view('backend.product.copy')->with(
            array(
                'brands' => BrandService::selectAll()->pluck('title', 'id')->toArray(),
            'product' => $product,
            'productCategories' => ProductCategoryService::selectAll()->pluck('title', 'id'),
            'taxRates' => TaxRateService::selectAll()->pluck('title', 'id')
            )
        );
    }

    public function storeCopy(Request $request, $productId)
    {
        $product = ProductService::find($productId);
        $result  = ProductService::createCopy($request->all(), $productId);

        if (isset($result->id)) {
            if ($product->attributes) {
                foreach ($product->attributes as $attribute) {
                    $inputAttribute = $attribute->toArray();

                    foreach ($attribute->combinations as $row2) {
                        $inputAttribute['selected_attribute_ids'][] = $row2->attribute->id;
                    }

                    $this->productCombination->create($inputAttribute, $result->id);
                }
            }

            Notification::success('The product copy is inserted.');
            return redirect()->route('product.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function editSeo($id)
    {
        return view('backend.product.edit_seo')->with(array('product' => ProductService::find($id)));
    }

    public function editPrice($id)
    {
        return view('backend.product.edit_price')->with(array('product' => ProductService::find($id), 'taxRates' => TaxRateService::selectAll()->pluck('title', 'id')));
    }

    public function update(Request $request, $productId)
    {
        $input = $request->all();
        $result  = ProductService::updateById($input, $productId);

        $redirect = redirect()->route('product.index');

        if (isset($result->id)) {
            if ($request->get('seo')) {
                Notification::success('Product seo was updated.');
                $redirect = redirect()->route('product.edit_seo', $productId);
            } elseif ($request->get('price')) {
                Notification::success('Product price was updated.');
                $redirect = redirect()->route('product.edit_price', $productId);
            } elseif ($request->get('product-combination')) {
                Notification::success('Product combination leading attribute group was updated.');
                $redirect = redirect()->route('product-combination.index', $productId);
            } else {
                Notification::success('Product was updated.');
            }

            return $redirect;
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }

        return redirect()->back()->withInput()->withErrors($result->errors()->all());
    }

    public function destroy($id)
    {
        $result  = ProductService::destroy($id);

        if ($result) {
            Notification::success('The product was deleted.');
            return redirect()->route('product.index');
        }
    }
}
