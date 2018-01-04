<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Content;
use Hideyo\Models\ContentImage;
use Hideyo\Models\ContentGroup;
use Image;
use File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Hideyo\Repositories\ShopRepositoryInterface;
use Validator;
use Auth;
 
class ContentRepository extends BaseRepository implements ContentRepositoryInterface
{

    protected $model;

    public function __construct(Content $model, ContentImage $modelImage, ContentGroup $modelGroup, ShopRepositoryInterface $shop)
    {
        $this->model = $model;
        $this->modelImage = $modelImage;
        $this->modelGroup = $modelGroup;
        $this->shop = $shop;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $id id attribute model    
     * @return array
     */
    public function rules($contentId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
            );
        } else {
            $rules = array(
                'title'                 => 'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id'
            );
            
            if ($contentId) {
                $rules['title'] =   'required|between:4,65|unique_with:'.$this->model->getTable().', shop_id, '.$contentId.' = id';
            }
        }

        return $rules;
    }

    /**
     * The validation rules for the modelGroup.
     *
     * @return array
     */
    public function rulesGroup($contentGroupId = false, $attributes = false)
    {
        if (isset($attributes['seo'])) {
            $rules = array(
                'meta_title'                 => 'required|between:4,65|unique_with:'.$this->modelGroup->getTable().', shop_id'
            );
        } else {
            $rules = array(
                'title'                 => 'required|between:4,65|unique:'.$this->modelGroup->getTable().''
            );
            
            if ($contentGroupId) {
                $rules['title'] =   'required|between:4,65|unique:'.$this->modelGroup->getTable().',title,'.$contentGroupId;
            }
        }

        return $rules;
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

    public function createImage(array $attributes, $contentId)
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

        $destinationPath = storage_path() . "/app/files/content/".$contentId;
        $attributes['user_id'] = $userId;
        $attributes['content_id'] = $contentId;
        $attributes['extension'] = $attributes['file']->getClientOriginalExtension();
        $attributes['size'] = $attributes['file']->getSize();
        
        $filename =  str_replace(" ", "_", strtolower($attributes['file']->getClientOriginalName()));
        $uploadSuccess = $attributes['file']->move($destinationPath, $filename);

        if ($uploadSuccess) {
            $attributes['file'] = $filename;
            $attributes['path'] = $uploadSuccess->getRealPath();
     
            $this->modelImage->fill($attributes);
            $this->modelImage->save();

            if ($shop->square_thumbnail_sizes) {
                $sizes = explode(',', $shop->square_thumbnail_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueSize) {
                        $image = Image::make($uploadSuccess->getRealPath());
                        $explode = explode('x', $valueSize);
                        $image->resize($explode[0], $explode[1]);
                        $image->interlace();

                        if (!File::exists(public_path() . "/files/content/".$valueSize."/".$contentId."/")) {
                            File::makeDirectory(public_path() . "/files/content/".$valueSize."/".$contentId."/", 0777, true);
                        }
                        $image->save(public_path() . "/files/content/".$valueSize."/".$contentId."/".$filename);
                    }
                }
            }
            
            return $this->modelImage;
        }
    
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

    private function updateGroupEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelGroup->fill($attributes);
            $this->modelGroup->save();
        }

        return $this->modelGroup;
    }

    public function updateImageById(array $attributes, $contentId, $newsImageId)
    {
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->modelImage = $this->find($newsImageId);
        return $this->updateImageEntity($attributes);
    }

    private function updateImageEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->modelImage->fill($attributes);
            $this->modelImage->save();
        }

        return $this->modelImage;
    }

    public function destroy($newsId)
    {
        $this->model = $this->find($newsId);
        $this->model->save();

        if ($this->model->contentImages()->count()) {
            foreach ($this->model->contentImages()->get() as $image) {
                $this->contentImage->destroy($image->id);
            }
        }

        $directory = app_path() . "/storage/files/".$this->model->shop_id."/content/".$this->model->id;
        File::deleteDirectory($directory);


        return $this->model->delete();
    }

    public function destroyImage($newsImageId)
    {
        $this->modelImage = $this->findImage($newsImageId);
        $filename = storage_path() ."/app/files/content/".$this->modelImage->content_id."/".$this->modelImage->file;
        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);

        if (File::exists($filename)) {
            File::delete($filename);
            if ($shop->square_thumbnail_sizes) {
                $sizes = explode(',', $shop->square_thumbnail_sizes);
                if ($sizes) {
                    foreach ($sizes as $valueSize) {
                        File::delete(public_path() . "/files/content/".$valueSize."/".$this->modelImage->content_id."/".$this->modelImage->file);
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

    public function selectGroupAll()
    {
        return $this->modelGroup->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }
 
    public function findGroup($newsGroupId)
    {
        return $this->modelGroup->find($newsGroupId);
    }

    public function getGroupModel()
    {
        return $this->modelGroup;
    }

    public function findImage($newsImageId)
    {
        return $this->modelImage->find($newsImageId);
    }

    public function getImageModel()
    {
        return $this->modelImage;
    }

}
