<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\News;
use Hideyo\Models\NewsImage;
use Hideyo\Models\NewsGroup;
use Carbon\Carbon;
use Image;
use File;
use Hideyo\Repositories\ShopRepositoryInterface;
use Validator;
use Auth;

class NewsRepository  extends BaseRepository implements NewsRepositoryInterface
{

    /**
     * Note: please keep logic in this repository. Put logic not in models,
     * Information about models in Laravel: http://laravel.com/docs/5.1/eloquent
     * @author     Matthijs Neijenhuijs <matthijs@hideyo.io>
     * @copyright  DutchBridge - dont share/steel!
     */

    protected $model;

    public function __construct(News $model, NewsImage $modelImage, NewsGroup $modelGroup, ShopRepositoryInterface $shop)
    {
        $this->model = $model;
        $this->modelImage = $modelImage;
        $this->shop = $shop;
        $this->modelGroup = $modelGroup;
        $this->storageImagePath = storage_path() .config('hideyo.storage_path'). "/news/";
        $this->publicImagePath = public_path() .config('hideyo.public_path'). "/news/";
    }
  
    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */
    public function rules($newsId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
            );
        } else {
            $rules = array(
                'title'                 => 'required|between:4,65|unique:'.$this->model->getTable().''
            );
            
            if ($newsId) {
                $rules['title'] =   'required|between:4,65|unique:'.$this->model->getTable().',title,'.$newsId;
            }
        }

        return $rules;
    }

    public function rulesGroup($newsGroupId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65|unique_with:'.$this->modelGroup->getTable().', shop_id'
            );
        } else {
            $rules = array(
                'title'                 => 'required|between:4,65|unique:'.$this->modelGroup->getTable()
            );
            
            if ($newsGroupId) {
                $rules['title'] =   'required|between:4,65|unique:'.$this->modelGroup->getTable().',title,'.$newsGroupId;
            }
        }

        return $rules;
    }

    public function createImage(array $attributes, $newsId)
    {
        $userId = auth('hideyobackend')->user()->id;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);
        $attributes['modified_by_user_id'] = $userId;
        $destinationPath = $this->storageImagePath.$newsId;
        $attributes['user_id'] = $userId;
        $attributes['news_id'] = $newsId;
        $attributes['extension'] = $attributes['file']->getClientOriginalExtension();
        $attributes['size'] = $attributes['file']->getSize();
       
        $rules = array(
            'file'=>'required|image|max:1000',
            'rank' => 'required'
        );

        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            return $validator;
        } 

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
                    foreach ($sizes as $valueImage) {
                        $image = Image::make($uploadSuccess->getRealPath());
                        $explode = explode('x', $valueImage);
                        $image->resize($explode[0], $explode[1]);
                        $image->interlace();

                        if (!File::exists($this->publicImagePath.$valueImage."/".$newsId."/")) {
                            File::makeDirectory($this->publicImagePath.$valueImage."/".$newsId."/", 0777, true);
                        }
                        $image->save($this->publicImagePath.$valueImage."/".$newsId."/".$filename);
                    }
                }
            }
            
            return $this->modelImage;
        }
        
    }


    public function createGroup(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rulesGroup());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
            
        $this->modelGroup->fill($attributes);
        $this->modelGroup->save();
   
        return $this->modelGroup;
    }
    
    public function refactorAllImagesByShopId($shopId)
    {
        $result = $this->modelImage->get();
        $shop = $this->shop->find($shopId);
        foreach ($result as $productImage) {
            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueImage) {
                        if (!File::exists($this->publicImagePath.$valueImage."/".$productImage->news_id."/")) {
                            File::makeDirectory($this->publicImagePath.$valueImage."/".$productImage->news_id."/", 0777, true);
                        }

                        if (!File::exists($this->publicImagePath.$valueImage."/".$productImage->news_id."/".$productImage->file)) {
                            if (File::exists($this->storageImagePath.$productImage->news_id."/".$productImage->file)) {
                                $image = Image::make(storage_path() .config('hideyo.storage_path'). "//news/".$productImage->news_id."/".$productImage->file);
                                $explode = explode('x', $valueImage);
                                $image->fit($explode[0], $explode[1]);
                            
                                $image->interlace();

                                $image->save(public_path() .config('hideyo.storage_path'). "/news/".$valueImage."/".$productImage->news_id."/".$productImage->file);
                            }
                        }
                    }
                }
            }
        }
    }

    public function updateImageById(array $attributes, $newsId, $newsImageId)
    {
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->modelImage = $this->findImage($newsImageId);
        return $this->updateImageEntity($attributes);
    }

    public function updateImageEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelImage->fill($attributes);
            $this->modelImage->save();
        }

        return $this->modelImage;
    }

    public function updateGroupById(array $attributes, $newsGroupId)
    {
        $validator = Validator::make($attributes, $this->rulesGroup($newsGroupId, $attributes));

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->modelGroup = $this->findGroup($newsGroupId);
        return $this->updateGroupEntity($attributes);
    }

    public function updateGroupEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelGroup->fill($attributes);
            $this->modelGroup->save();
        }

        return $this->modelGroup;
    }

    public function destroy($newsId)
    {
        $this->model = $this->find($newsId);

        if ($this->model->newsImages->count()) {
            foreach ($this->model->newsImages as $image) {
                $this->newsImage->destroy($image->id);
            }
        }

        $directory = app_path() . "/storage/files/news/".$this->model->id;
        File::deleteDirectory($directory);

        return $this->model->delete();
    }

    public function destroyImage($newsImageId)
    {
        $this->modelImage = $this->findImage($newsImageId);
        $filename = $this->storageImagePath.$this->modelImage->news_id."/".$this->modelImage->file;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);

        if (File::exists($filename)) {
            File::delete($filename);
            if ($shop->thumbnail_square_sizes) {
                $sizes = explode(',', $shop->thumbnail_square_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueImage) {
                        File::delete($this->publicImagePath.$valueImage."/".$this->modelImage->news_id."/".$this->modelImage->file);
                    }
                }
            }
        }

        return $this->modelImage->delete();
    }

    public function destroyGroup($newsGroupId)
    {
        $this->modelGroup = $this->findGroup($newsGroupId);
        $this->modelGroup->save();
        return $this->modelGroup->delete();
    }

    public function selectAllGroups()
    {
       return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    public function findGroup($groupId)
    {
        return $this->modelGroup->find($groupId);
    }

    public function getGroupModel()
    {
        return $this->modelGroup;
    }

    public function findImage($imageId)
    {
        return $this->modelImage->find($imageId);
    }

    public function getImageModel()
    {
        return $this->modelImage;
    }

    function selectOneBySlug($shopId, $slug)
    {
        $dt = Carbon::now('Europe/Amsterdam');
        return $this->model->where('slug', '=', $slug)->where('published_at', '<=', $dt->toDateString('Y-m-d'))->get()->first();
    }

    function selectAllByBlogCategoryId($newsCategoryId)
    {
           return $this->model->with(array('extraFields' => function ($query) {
           }, 'taxRate', 'newsCategory',  'relatedBlogs' => function ($query) {
            $query->with('newsImages')->orderBy('rank', 'asc');
           }, 'newsImages' => function ($query) {
            $query->orderBy('rank', 'asc');
           }))->where('active', '=', 1)->where('news_category_id', '=', $newsCategoryId)->get();
    }

    function selectOneById($shopId, $slug)
    {
        $dt = Carbon::now('Europe/Amsterdam');
        $result = $this->model->with(array('newsCategory', 'relatedBlogs', 'newsImages' => function ($query) {
            $query->orderBy('rank', 'asc');
        }))->where('published_at', '<=', $dt->toDateString('Y-m-d'))->where('active', '=', 1)->where('id', '=', $id)->get()->first();
        return $result;
    }

    function selectAllActiveGroupsByShopId($shopId)
    {
         return $this->modelGroup->where('shop_id', '=', $shopId)->where('active', '=', 1)->get();
    }

    function selectOneGroupByShopIdAndSlug($shopId, $slug)
    {
        $result = $this->modelGroup->where('shop_id', '=', $shopId)->where('slug', '=', $slug)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    public function selectByLimitAndOrderBy($shopId, $limit, $orderBy)
    {
        $dt = Carbon::now('Europe/Amsterdam');

        return $this->model->with(
            array('newsImages' => function ($query) {
                $query->orderBy('rank', 'asc');
            })
        )
            ->limit($limit)
           ->where('shop_id', '=', $shopId)
           ->where('published_at', '<=', $dt->toDateString('Y-m-d'))
            ->orderBy('created_at', $orderBy)->get();
    }

    function selectAllByShopIdAndPaginate($shopId, $totalPage, $filters = false)
    {
        $dt = Carbon::now('Europe/Amsterdam');

           $result = $this->model
           ->where('shop_id', '=', $shopId)
           ->where('published_at', '<=', $dt->toDateString('Y-m-d'));

            return array(
                'totals' => $result->get()->count(),
                'totalPages' => ceil($result->get()->count() / $totalPage),
                'result' => $result->paginate($totalPage),
                'totalOnPage' => $totalPage
            );
    }

    function selectByGroupAndByShopIdAndPaginate($shopId, $newsGroupSlug, $totalPage, $filters = false)
    {
        $dt = Carbon::now('Europe/Amsterdam');

           $result = $this->model
           ->where('shop_id', '=', $shopId)
           ->where('published_at', '<=', $dt->toDateString('Y-m-d'))
           ->whereHas('newsGroup', function ($query) use ($newsGroupSlug) {
            $query->where('slug', '=', $newsGroupSlug);
           });

            return array(
                'totals' => $result->get()->count(),
                'totalPages' => ceil($result->get()->count() / $totalPage),
                'result' => $result->paginate($totalPage),
                'totalOnPage' => $totalPage
            );
    }
}