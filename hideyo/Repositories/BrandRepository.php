<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Brand;
use Hideyo\Models\BrandImage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Hideyo\Repositories\RedirectRepositoryInterface;
use Image;
use File;
use Hideyo\Repositories\ShopRepositoryInterface;
use Validator;
use Auth;
 
class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    protected $model;

    public function __construct(
        Brand $model, 
        BrandImage $modelImage, 
        RedirectRepositoryInterface $redirect, 
        ShopRepositoryInterface $shop)
    {
        $this->model        = $model;
        $this->modelImage   = $modelImage;
        $this->redirect     = $redirect;
        $this->shop         = $shop;
        $this->storageImagePath = storage_path() .config('hideyo.storage_path'). "/brand/";
        $this->publicImagePath = public_path() .config('hideyo.public_path'). "/brand/";
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $brandId id attribute model    
     * @return array
     */
    private function rules($brandId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
            );

            return $rules;
        } 

        $rules = array(
            'title' => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id',
            'rank'  => 'required|integer'
        );
        
        if ($brandId) {
            $rules['title'] =   $rules['title'].', '.$brandId.' = id';
        }
     
        return $rules;
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
   
        return $this->model;
    }

    public function createImage(array $attributes, $brandId)
    {
        $userId = auth('hideyobackend')->user()->id;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);
       
        $rules = array(
            'file'=>'required|image|max:1000',
            'rank' => 'required'
        );

        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = $userId;

        $destinationPath = $this->storageImagePath.$brandId;
        $attributes['user_id'] = $userId;
        $attributes['brand_id'] = $brandId;
        $attributes['extension'] = $attributes['file']->getClientOriginalExtension();
        $attributes['size'] = $attributes['file']->getSize();

        $filename =  str_replace(" ", "_", strtolower($attributes['file']->getClientOriginalName()));
        $uploadSuccess = $attributes['file']->move($destinationPath, $filename);

        if ($uploadSuccess) {
            $attributes['file'] = $filename;
            $attributes['path'] = $uploadSuccess->getRealPath();
     
            $this->modelImage->fill($attributes);
            $this->modelImage->save();

            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueSize) {
                        $image = Image::make($uploadSuccess->getRealPath());
                        $explode = explode('x', $valueSize);
                        $image->resize($explode[0], $explode[1]);
                        $image->interlace();

                        if (!File::exists($this->publicImagePath.$valueSize."/".$brandId."/")) {
                            File::makeDirectory($this->publicImagePath.$valueSize."/".$brandId."/", 0777, true);
                        }
                        $image->save($this->publicImagePath.$valueSize."/".$brandId."/".$filename);
                    }
                }
            }
            
            return $this->modelImage;
        }  
    }

    public function updateById(array $attributes, $brandId)
    {
        $validator = Validator::make($attributes, $this->rules($brandId, $attributes));
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->find($brandId);

        $result = $this->updateEntity($attributes);

        return $result;
    }

    public function updateImageById(array $attributes, $brandId, $imageId)
    {
        $this->model = $this->find($brandId);

        if($this->model) {
            $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            $this->modelImage = $this->findImage($imageId);
            return $this->updateImageEntity($attributes);            
        }

        return false;
    }

    private function updateImageEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelImage->fill($attributes);
            $this->modelImage->save();
        }

        return $this->modelImage;
    }

    public function destroy($brandId)
    {
        $this->model = $this->find($brandId);

        // $url = $this->model->shop->url.route('brand.item', ['slug' => $this->model->slug], null);
        // $newUrl = $this->model->shop->url.route('brand.overview', array(), null);
        // $redirectResult = $this->redirect->create(array('active' => 1, 'url' => $url, 'redirect_url' => $newUrl, 'shop_id' => $this->model->shop_id));

        $this->model->save();
        return $this->model->delete();
    }

    public function destroyImage($imageId)
    {
        $this->modelImage = $this->findImage($imageId);
        $filename = $this->storageImagePath.$this->modelImage->brand_id."/".$this->modelImage->file;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);

        if (File::exists($filename)) {
            File::delete($filename);
            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueSize) {
                        File::delete($this->publicImagePath.$valueSize."/".$this->modelImage->brand_id."/".$this->modelImage->file);
                    }
                }
            }
        }

        return $this->modelImage->delete();
    }

    public function refactorAllImagesByShopId($shopId)
    {
        $result = $this->modelImage->get();
        $shop = $this->shop->find($shopId);
        if($result AND $shop->thumbnail_square_sizes) {
            foreach ($result as $productImage) {
                if ($shop->thumbnail_square_sizes) {
                    $sizes = explode(',', $shop->thumbnail_square_sizes);
                    if ($sizes) {
                        foreach ($sizes as $valueSize) {
                            if (!File::exists($this->publicImagePath.$valueSize."/".$productImage->brand_id."/")) {
                                File::makeDirectory($this->publicImagePath.$valueSize."/".$productImage->brand_id."/", 0777, true);
                            }

                            if (!File::exists($this->publicImagePath.$valueSize."/".$productImage->brand_id."/".$productImage->file)) {
                                if (File::exists($this->storageImagePath.$productImage->brand_id."/".$productImage->file)) {
                                    $image = Image::make($this->storageImagePath.$productImage->brand_id."/".$productImage->file);
                                    $explode = explode('x', $valueSize);
                                    $image->fit($explode[0], $explode[1]);
                                    $image->interlace();
                                    $image->save($this->publicImagePath.$valueSize."/".$productImage->brand_id."/".$productImage->file);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->orderBy('title', 'asc')->get();
    } 
    
    public function findImage($imageId)
    {
        return $this->modelImage->find($imageId);
    }

    public function getModelImage()
    {
        return $this->modelImage;
    }    
}