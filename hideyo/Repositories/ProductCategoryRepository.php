<?php
namespace Hideyo\Repositories;


use Hideyo\Repositories\ProductCategoryImageRepositoryInterface;
use Hideyo\Repositories\RedirectRepositoryInterface;
use Hideyo\Repositories\ShopRepositoryInterface;

use Hideyo\Models\ProductCategory;
use Hideyo\Models\ProductCategoryImage;
use Image;
use File;
use Auth;
use Validator;
 
class ProductCategoryRepository extends BaseRepository implements ProductCategoryRepositoryInterface
{
    protected $model;

    /**
     * The validation rules for the model.
     *
     * @param  integer  $productCategoryId id attribute model    
     * @return array
     */
    private function rules($productCategoryId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65',
                'meta_description'           => 'required|between:4,160',
            );
        } elseif (isset($attributes['highlight'])) {
            $rules = array();
        } else {
            $rules = array(
                'title'                 => 'required|unique_with:'.'product_category, shop_id'
            );
            
            if ($productCategoryId) {
                $rules['title'] =   'required|between:4,65|unique_with:'.'product_category, shop_id, '.$productCategoryId.' = id';
            }
        }
        return $rules;
    }

    public function __construct(ProductCategory $model, ProductCategoryImage $imageModel, ShopRepositoryInterface $shop, RedirectRepositoryInterface $redirect)
    {
        $this->model = $model;
        $this->imageModel = $imageModel;
        $this->redirect = $redirect;
        $this->shop = $shop;
        $this->storageImagePath = storage_path() .config('hideyo.storage_path'). "/product_category/";
        $this->publicImagePath = public_path() .config('hideyo.public_path'). "/product_category/";
    }
  
    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model->fill($attributes);
        $this->model->save();
        $this->model->rebuild();
        return $this->model;
    }

    public function createImage(array $attributes, $productCategoryId)
    {
        $userId = auth('hideyobackend')->user()->id;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);
        $attributes['modified_by_user_id'] = $userId;

        $destinationPath = $this->storageImagePath.$productCategoryId;
        $attributes['user_id'] = $userId;
        $attributes['product_category_id'] = $productCategoryId;
        $attributes['extension'] = $attributes['file']->getClientOriginalExtension();
        $attributes['size'] = $attributes['file']->getSize();
       
        $rules = array(
            'file'=>'required|image|max:1000',
            'rank' => 'required'
        );

        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            return $validator;
        } else {
            $filename =  str_replace(" ", "_", strtolower($attributes['file']->getClientOriginalName()));
            $uploadSuccess = $attributes['file']->move($destinationPath, $filename);

            if ($uploadSuccess) {
                $attributes['file'] = $filename;
                $attributes['path'] = $uploadSuccess->getRealPath();
         
                $this->imageModel->fill($attributes);
                $this->imageModel->save();

                if ($shop->thumbnail_square_sizes) {
                    $sizes = explode(',', $shop->thumbnail_square_sizes);
                    if ($sizes) {
                        foreach ($sizes as $key => $value) {
                            $image = Image::make($uploadSuccess->getRealPath());
                            $explode = explode('x', $value);
                            $image->resize($explode[0], $explode[1]);
                            $image->interlace();

                            if (!File::exists($this->publicImagePath.$value."/".$productCategoryId."/")) {
                                File::makeDirectory($this->publicImagePath.$value."/".$productCategoryId."/", 0777, true);
                            }
                            $image->save($this->publicImagePath.$value."/".$productCategoryId."/".$filename);
                        }
                    }
                }
                
                return $this->imageModel;
            }
        }
    }

    public function refactorAllImagesByShopId($shopId)
    {
        $result = $this->model->get();
        $shop = $this->shop->find($shopId);
        foreach ($result as $productImage) {
            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $key => $value) {
                        if (!File::exists($this->publicImagePath.$value."/".$productImage->product_category_id."/")) {
                            File::makeDirectory($this->publicImagePath.$value."/".$productImage->product_category_id."/", 0777, true);
                        }

                        if (!File::exists($this->publicImagePath.$value."/".$productImage->product_category_id."/".$productImage->file)) {
                            if (File::exists($this->storageImagePath.$productImage->product_category_id."/".$productImage->file)) {
                                $image = Image::make($this->storageImagePath.$productImage->product_category_id."/".$productImage->file);
                                $explode = explode('x', $value);
                                $image->fit($explode[0], $explode[1]);
                            
                                $image->interlace();

                                $image->save($this->publicImagePath.$value."/".$productImage->product_category_id."/".$productImage->file);
                            }
                        }
                    }
                }
            }
        }
    }

    public function updateById(array $attributes, $id)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules($id, $attributes));

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->find($id);

        $oldTitle = $this->model->title;
        $oldSlug = $this->model->slug;

        $this->model->rebuild();
        $result = $this->updateEntity($attributes);


        // if (isset($attributes['title']) AND $oldTitle != $attributes['title']) {
        //     $url = $result->shop->url.route('product-category', ['slug' => $oldSlug], null);
           
        //     if ($result->active) {
        //         $this->redirect->destroyByUrl($url);
        //     }

        //     $newUrl = $result->shop->url.route('product-category', ['slug' => $result->slug], null);
        //     $redirectResult = $this->redirect->create(array('active' => 1, 'url' => $url, 'redirect_url' => $newUrl, 'shop_id' => $result->shop_id));
        // }

        // if (!$result->active) {
        //     $url = $result->shop->url.route('product-category', ['slug' => $result->slug], null);
        //     $productCategoryUrl = $result->shop->url;
        //     $redirectResult = $this->redirect->create(array('active' => 1, 'url' => $url, 'redirect_url' => $productCategoryUrl, 'shop_id' => $result->shop_id));
        // }

        return $result;
    }

    public function updateEntity(array $attributes = array())
    {
        if (!isset($attributes['parent_id'])) {
            $attributes['parent_id'] = null;
        }

        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            
            if (isset($attributes['highlightProducts'])) {
                $this->model->productCategoryHighlightProduct()->sync($attributes['highlightProducts']);
            }
            
            $this->model->save();
        }

        return $this->model;
    }

    public function updateImageById(array $attributes, $productCategoryId, $imageId)
    {
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->findImage($imageId);
        return $this->updateImageEntity($attributes);
    }

    public function updateImageEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }

        return $this->model;
    }

    public function destroy($productCategoryId)
    {
        $this->model = $this->find($productCategoryId);

        // $url = $this->model->shop->url.route('hideyo.product-category', ['slug' => $this->model->slug], null);
        // $newUrl = $this->model->shop->url;
        // $redirectResult = $this->redirect->create(array('active' => 1, 'url' => $url, 'redirect_url' => $newUrl, 'shop_id' => $this->model->shop_id));

        if ($this->model->productCategoryImages()->count()) {
            foreach ($this->model->productCategoryImages()->get() as $image) {
                $this->productCategoryImage->destroy($image->id);
            }
        }

        $directory = storage_path() . "/app/files/product_category/".$this->model->id;
        File::deleteDirectory($directory);
        File::deleteDirectory(public_path() . "/files/product_category/".$this->model->id);

        if ($this->model->children()->count()) {
            foreach ($this->model->children()->get() as $child) {
                $child->makeRoot();
                $child->parent_id = null;
                $child->save();
            }
        }

        return $this->model->delete();
    }

    public function destroyImage($imageId)
    {
        $this->imageModel = $this->findImage($imageId);
        $filename = storage_path() ."/app/files/product_category/".$this->imageModel->product_category_id."/".$this->imageModel->file;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);

        if (File::exists($filename)) {
            File::delete($filename);
            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $key => $value) {
                        File::delete(public_path() . "/files/product_category/".$value."/".$this->imageModel->product_category_id."/".$this->imageModel->file);
                    }
                }
            }
        }

        return $this->imageModel->delete();
    }

    public function rebuild()
    {
        return $this->model->rebuild(true);
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->orderBy('title', 'asc')->get();
    }

    public function selectAllProductPullDown()
    {
        return $this->model->whereNull('redirect_product_category_id')->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->orderBy('title', 'asc')->get();
    }

    public function ajaxSearchByTitle($query)
    {
        return $this->model->where('title', 'LIKE', '%'.$query.'%')->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->orderBy('title', 'asc')->get();
    }

    function selectAllByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->orderBy('title', 'asc')->get();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->orderBy('title', 'asc')->get();
    }

    function selectAllByShopIdAndRoot($shopId)
    {
         return $this->model->roots()->where('shop_id', '=', $shopId)->where('active', '=', 1)->orderBy('title', 'asc')->get();
    }  

    function selectCategoriesByParentId($shopId, $parentId, $imageTag = false)
    {
        $result = $this->model->where('id', '=', $parentId)->first()->children()
        ->with(
            array('productCategoryImages' => function ($query) use ($imageTag) {
                if ($imageTag) {
                    $query->where('tag', '=', $imageTag);
                }
                    $query->orderBy('rank', 'asc');
            }
        )
        )
        ->where('product_category.shop_id', '=', $shopId)
        ->where('product_category.parent_id', '=', $parentId)
        ->where('active', '=', '1')->get();

        if ($result->count()) {
            return $result;
        }
        
        return false;
    }

    function selectRootCategories($shopId, $imageTag)
    {
        $result = $this->model
        ->with(
            array('productCategoryImages' => function ($query) use ($imageTag) {
                if ($imageTag) {
                    $query->where('tag', '=', $imageTag);
                }
                    $query->orderBy('rank', 'asc');
            }
            )
        )
            ->where('product_category.shop_id', '=', $shopId)
            ->whereNull('product_category.parent_id')
            ->get();

        if ($result) {
            return $result;
        }
        
        return false;
    }

    public function entireTreeStructure($shopId)
    {
        return $this->model->where('shop_id', '=', $shopId)->get()->toHierarchy();
    }

    public function changeActive($productCategoryId)
    {
        $this->model = $this->find($productCategoryId);

        if ($this->model) {

            $active = 1;
            if ($this->model->active) {
                $active = 0;
            }

            $attributes = array(
                'active' => $active
            );

            $this->model->fill($attributes);

            // if (!$this->model->active) {
            //     $url = $this->model->shop->url.route('product-category', ['slug' => $this->model->slug], null);
            //     $productCategoryUrl = $this->model->shop->url;
            //     $redirectResult = $this->redirect->create(array('active' => 1, 'url' => $url, 'redirect_url' => $productCategoryUrl, 'shop_id' => $this->model->shop_id));
            // } else {
            //     $url = $this->model->shop->url.route('product-category', ['slug' => $this->model->slug], null);
            //     $this->redirect->destroyByUrl($url);
            // }


            return $this->model->save();
        }

        return false;
    }

    public function findImage($imageId)
    {
        return $this->imageModel->find($imageId);
    }

    public function getImageModel()
    {
        return $this->imageModel;
    }

    function selectOneByShopIdAndSlug($shopId, $slug, $imageTag = false)
    { 
        $result = $this->model->
        with(
            array(
                'products' => function ($query) {
                    $query->where('active', '=', 1)->with(
                        array('productImages' => function ($query) {
                            $query->orderBy('rank', 'asc');
                        })
                    );
                },
                'productCategoryImages' => function ($query) use ($imageTag) {
                    if ($imageTag) {
                        $query->where('tag', '=', $imageTag);
                    } $query->orderBy('rank', 'asc');
                }, 
                'refProductCategory'
            )
        )
        ->where('product_category.shop_id', '=', $shopId)
        ->where('product_category.slug', '=', $slug)
        ->where('product_category.active', '=', 1)
        ->get()
        ->first();

        if ($result) {

            if ($result->isRoot()) {
                $result['is_root'] = true;
            }

            if ($result->isLeaf()) {
                $result['is_leaf'] = true;
            } else {
                $result['is_leaf'] = false;
                $result['children_product_categories'] = $result->children()->get();
            }

            return $result;
        }
        
        return false;        
    }
}