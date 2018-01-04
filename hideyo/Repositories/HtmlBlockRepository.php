<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\HtmlBlock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use File;
use Hideyo\Repositories\ShopRepositoryInterface;
use Image;
use Auth;
use Validator;

class HtmlBlockRepository extends BaseRepository implements HtmlBlockRepositoryInterface
{

    protected $model;

    public function __construct(HtmlBlock $model, ShopRepositoryInterface $shop)
    {
        $this->model = $model;
        $this->shop = $shop;
        $this->storageImagePath = storage_path() .config('hideyo.storage_path'). "/html_block/";
        $this->publicImagePath = public_path() .config('hideyo.public_path'). "/html_block/";

    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $htmlBlockId id attribute model    
     * @return array
     */
    public function rules($htmlBlockId = false, $attributes = false)
    {

        $rules = array(
            'title'                 => 'required|between:4,65'
        );
        
        if ($htmlBlockId) {
            $rules['title'] =   'required|between:4,65';
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

        if (isset($attributes['image'])) {
            $attributes['image_file_extension'] = $attributes['image']->getClientOriginalExtension();
            $attributes['image_file_size'] = $attributes['image']->getSize();

            $rules = array(
                'file'=>'image|max:1000'
            );

            $validator = Validator::make($attributes, $rules);

            if ($validator->fails()) {
                return $validator;
            } else {
                $destinationPath = $this->storageImagePath.$this->model->id;
                $filename =  str_replace(" ", "_", strtolower($attributes['image']->getClientOriginalName()));
                $uploadSuccess = $attributes['image']->move($destinationPath, $filename);

                if ($uploadSuccess) {
                    $attributes['image_file_name'] = $filename;
                    $attributes['image_file_path'] = $uploadSuccess->getRealPath();

                    $this->model->fill($attributes);
                    $this->model->save();

                    if ($this->model->thumbnail_height and $this->model->thumbnail_width) {
                        $image = Image::make($uploadSuccess->getRealPath());
               
                        $image->resize($this->model->thumbnail_width, $this->model->thumbnail_height);
                        $image->interlace();

                        if (!File::exists($this->publicImagePath.$this->model->id."/")) {
                            File::makeDirectory($this->publicImagePath.$this->model->id."/", 0777, true);
                        }
                        $image->save($this->publicImagePath.$this->model->id."/".$filename);
                    }
                }
            }
        }


        if ($this->model->thumbnail_height and $this->model->thumbnail_width and $this->model->image_file_name) {
            File::deleteDirectory($this->publicImagePath.$this->model->id."/");
            $image = Image::make($this->model->image_file_path);
   
            $image->resize($this->model->thumbnail_width, $this->model->thumbnail_height);
            $image->interlace();

            if (!File::exists($this->publicImagePath.$this->model->id."/")) {
                File::makeDirectory($this->publicImagePath.$this->model->id."/", 0777, true);
            }
            $image->save($this->publicImagePath.$this->model->id."/".$this->model->image_file_name);
        }

   
        return $this->model;
    }


    public function createCopy(array $attributes, $htmlBlockId)
    {

        $product =  $this->find($htmlBlockId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }
   
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;

        $this->model->sluggify();
        $this->model->fill($attributes);

        $this->model->save();
    
        return $this->model;
    }

    public function changeActive($htmlBlockId)
    {

        $this->model = $this->find($htmlBlockId);

        if ($this->model) {
            $active = 1;
            
            if ($this->model->active) {
                $active = 0;
            }

            $attributes = array(
                'active' => $active
            );

            $this->model->fill($attributes);

            $this->model->sluggify();

            return $this->model->save();
        }

        return false;
    }



    public function updateById(array $attributes, $htmlBlockId)
    {
        $validator = Validator::make($attributes, $this->rules($htmlBlockId, $attributes));

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model = $this->find($htmlBlockId);
        return $this->updateEntity($attributes);
    }

    public function updateEntity(array $attributes = array())
    {

        $shopId = auth('hideyobackend')->user()->selected_shop_id;
        $shop = $this->shop->find($shopId);

        if (count($attributes) > 0) {
            $this->model->fill($attributes);
            $this->model->save();
        }


        if (isset($attributes['image'])) {
            $attributes['image_file_extension'] = $attributes['image']->getClientOriginalExtension();
            $attributes['image_file_size'] = $attributes['image']->getSize();


            $rules = array(
                'file'=>'image|max:1000'
            );

            $validator = Validator::make($attributes, $rules);

            if ($validator->fails()) {
                return $validator;
            } else {
                $destinationPath = $this->storageImagePath.$this->model->id;
                $filename =  str_replace(" ", "_", strtolower($attributes['image']->getClientOriginalName()));
                File::deleteDirectory($destinationPath);

                $uploadSuccess = $attributes['image']->move($destinationPath, $filename);

                if ($uploadSuccess) {
                    $attributes['image_file_name'] = $filename;
                    $attributes['image_file_path'] = $uploadSuccess->getRealPath();

                    $this->model->fill($attributes);
                    $this->model->save();
                }
            }
        }
 
        if (File::exists($this->model->image_file_path) AND $this->model->thumbnail_height and $this->model->thumbnail_width and $this->model->image_file_name) {
            $image = Image::make($this->model->image_file_path);
   

            File::deleteDirectory($this->publicImagePath.$this->model->id);


            $image->resize($this->model->thumbnail_width, $this->model->thumbnail_height);
            $image->interlace();

            if (!File::exists($this->publicImagePath.$this->model->id."/")) {
                File::makeDirectory($this->publicImagePath.$this->model->id."/", 0777, true);
            }
            $image->save($this->publicImagePath.$this->model->id."/".$this->model->image_file_name);
        }


        return $this->model;
    }

    public function destroy($htmlBlockId)
    {
        $this->model = $this->find($htmlBlockId);
        File::deleteDirectory($this->publicImagePath.$this->model->id);

        $destinationPath = $this->storageImagePath.$this->model->id;

        File::deleteDirectory($destinationPath);
        $this->model->save();

        return $this->model->delete();
    }

    function selectAllActiveByShopId($shopId)
    {
         return $this->model->where('shop_id', '=', $shopId)->get();
    }

    function selectOneByShopIdAndSlug($shopId, $slug)
    {
        $result = $this->model->where('shop_id', '=', $shopId)->where('slug', '=', $slug)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    }

    function selectOneByShopIdAndPosition($shopId, $position)
    {
        $result = $this->model->where('shop_id', '=', $shopId)->where('position', '=', $position)->get();
        
        if ($result->isEmpty()) {
            return false;
        }
        return $result->first();
    } 
}