<?php namespace App\Http\Controllers\Backend;

/**
 * ProductCategoryController
 *
 * This is the controller of the product categories of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;

use Dutchbridge\Datatable\ProductCategoryDatatable;
use Hideyo\Ecommerce\Framework\Repositories\ProductCategoryRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\ProductRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Auth;
use Datatables;
use Form;

class ProductCategoryController extends Controller
{
    public function __construct(ProductRepositoryInterface $product, ProductCategoryRepositoryInterface $productCategory)
    {
        $this->productCategory = $productCategory;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $productCategory = $this->productCategory->getModel()->select(
                ['id', 'active','shop_id','parent_id', 'redirect_product_category_id','title', 'meta_title', 'meta_description']
            )->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = Datatables::of($productCategory)

            ->addColumn('image', function ($productCategory) {
                if ($productCategory->productCategoryImages->count()) {
                    return '<img src="/files/product_category/100x100/'.$productCategory->id.'/'.$productCategory->productCategoryImages->first()->file.'"  />';
                }
            })

            ->addColumn('title', function ($productCategory) {

                $categoryTitle = $productCategory->title;
                if ($productCategory->refProductCategory) {
                    $categoryTitle = '<strong>Redirect:</strong> '.$productCategory->title.' &#8594; '.$productCategory->refProductCategory->title;
                } elseif ($productCategory->isRoot()) {
                    $categoryTitle = '<strong>Root:</strong> '.$productCategory->title;
                } elseif ($productCategory->isChild()) {
                    $categoryTitle = '<strong>Child:</strong> '.$productCategory->title;
                }
                
                return $categoryTitle;
            })

            ->addColumn('products', function ($productCategory) {
                return $productCategory->products->count();
            })
            ->addColumn('parent', function ($productCategory) {
             
                if ($productCategory->parent()->count()) {
                    return $productCategory->parent()->first()->title;
                }
            })

            ->addColumn('active', function ($product) {
                if ($product->active) {
                    return '<a href="#" class="change-active" data-url="/admin/product-category/change-active/'.$product->id.'"><span class="glyphicon glyphicon-ok icon-green"></span></a>';
                }
                
                return '<a href="#" class="change-active" data-url="/admin/product-category/change-active/'.$product->id.'"><span class="glyphicon glyphicon-remove icon-red"></span></a>';
            })


            ->addColumn('seo', function ($productCategory) {
                if ($productCategory->meta_title && $productCategory->meta_description) {
                    return '<i class="fa fa-check"></i>';
                }
            })
            ->addColumn('action', function ($productCategory) {
                $deleteLink = Form::deleteajax(url()->route('product-category.destroy', $productCategory->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'), $productCategory->title);
                $links = '<a href="'.url()->route('product-category.edit', $productCategory->id).'" class="btn btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.product_category.index')->with(array('productCategory' =>  $this->productCategory->selectAll(), 'tree' => $this->productCategory->entireTreeStructure(auth('hideyobackend')->user()->shop->id)->toArray()));
    }

    public function refactorAllImages()
    {
        $this->productCategoryImage->refactorAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);
        return redirect()->route('product-category.index');
    }

    public function tree()
    {
        return view('backend.product_category.tree')->with(array('productCategory' =>  $this->productCategory->selectAll(), 'tree' => $this->productCategory->entireTreeStructure(auth('hideyobackend')->user()->shop->id)->toArray()));
    }

    public function ajaxCategories(Request $request)
    {
        $query = $request->get('q');
        $selectedId = $request->get('selectedId');

        if ($request->wantsJson()) {
            return response()->json($this->productCategory->ajaxSearchByTitle($query, $selectedId));
        }
    }

    public function ajaxCategory(Request $request, $productCategoryId)
    {
        if ($request->wantsJson()) {
            return response()->json($this->productCategory->find($productCategoryId));
        }
    }

    public function generateInput($array)
    {      
        if (empty($array['redirect_product_category_id'])) {
            $array['redirect_product_category_id'] = null;
        }

        if (empty($array['parent_id'])) {
            $array['parent_id'] = null;
        }

        return $array;
    }

    public function create()
    {
        return view('backend.product_category.create')->with(array('categories' => $this->productCategory->selectAll()->pluck('title', 'id')));
    }

    public function store(Request $request)
    {
        $result  = $this->productCategory->create($this->generateInput($request->all()));

        if (isset($result->id)) {
            Notification::success('The product category was inserted.');
            return redirect()->route('product-category.index');
        }
            
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }

        return redirect()->back()->withInput();
    }

    public function edit($productCategoryId)
    {
        $category =$this->productCategory->find($productCategoryId);
        return view('backend.product_category.edit')->with(array('productCategory' => $this->productCategory->find($productCategoryId), 'categories' => $this->productCategory->selectAll()->pluck('title', 'id')));
    }

    public function editHighlight($productCategoryId)
    {
        $category =$this->productCategory->find($productCategoryId);
        $products = $this->product->selectAll()->pluck('title', 'id');
        return view('backend.product_category.edit-highlight')->with(array('products' => $products, 'productCategory' => $this->productCategory->find($productCategoryId), 'categories' => $this->productCategory->selectAll()->pluck('title', 'id')));
    }

    public function editSeo($productCategoryId)
    {
        return view('backend.product_category.edit_seo')->with(array('productCategory' => $this->productCategory->find($productCategoryId), 'categories' => $this->productCategory->selectAll()->pluck('title', 'id')));
    }

    public function update(Request $request, $productCategoryId)
    {
        $result  = $this->productCategory->updateById($this->generateInput($request->all()), $productCategoryId);

        if (isset($result->id)) {
            if ($request->get('seo')) {
                Notification::success('Category seo was updated.');
                return redirect()->route('product-category.edit_seo', $productCategoryId);
            } elseif ($request->get('highlight')) {
                Notification::success('Highlight was updated.');
                return redirect()->route('product-category.edit.hightlight', $productCategoryId);
            }
    
            Notification::success('Category was updated.');
            return redirect()->route('product-category.edit', $productCategoryId);        
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }

        return redirect()->back()->withInput();
    }

    public function destroy($productCategoryId)
    {
        $result  = $this->productCategory->destroy($productCategoryId);

        if ($result) {
            Notification::success('Category was deleted.');
            return redirect()->route('product-category.index');
        }
    }

    public function ajaxRootTree()
    {
        $tree = $this->productCategory->entireTreeStructure(auth('hideyobackend')->user()->shop->id);
        foreach ($tree as $key => $row) {
            $children = false;
            if ($row->children->count()) {
                $children = true;
            }

            $treeData[] = array(
                'id' => $row->id,
                'text' => $row->title,
                'children' => $children,
                'type' => 'root'

            );
        }

        return response()->json($treeData);
    }

    public function ajaxChildrenTree(Request $request)
    {
        $productCategoryId = $request->get('id');
        $category = $this->productCategory->find($productCategoryId);

        foreach ($category->children()->get() as $key => $row) {
            $children = false;
            if ($row->children->count()) {
                $children = true;
            }

            $treeData[] = array(
                'id' => $row->id,
                'text' => $row->title,
                'children' => $children
            );
        }

        return response()->json($treeData);
    }

    public function changeActive($productCategoryId)
    {
        $result = $this->productCategory->changeActive($productCategoryId);
        return response()->json($result);
    }

    public function ajaxMoveNode(Request $request)
    {
        $productCategoryId = $request->get('id');
        $position = $request->get('position');
        $node = $this->productCategory->find($productCategoryId);
        $parent = $request->get('parent');

        if ($parent != '#') {
            $parent = $this->productCategory->find($parent);
            if ($position == 0) {
                $node->makeFirstChildOf($parent);
            } elseif ($parent->children()->count()) {
                $node->makeLastChildOf($parent);
                foreach ($parent->children()->get() as $key => $row) {
                    $positionKey =  $position - 1;
                    if ($key == $positionKey) {
                        $node->moveToRightOf($row);
                    }
                }
            } else {
                $node->makeFirstChildOf($parent);
            }
        } else {
            $node->makeRoot();
        }

        $node = $this->productCategory->find($productCategoryId);
        $arrayPosition = $node->siblingsAndSelf()->get()->toArray();

        $positionToMove = $arrayPosition[$position];
        
        $otherNode = $this->productCategory->find($positionToMove['id']);
        $node->moveToLeftOf($otherNode);
    }
}
