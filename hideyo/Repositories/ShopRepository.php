<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\Shop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use File;
use Image;
use Validator;
 
class ShopRepository extends BaseRepository implements ShopRepositoryInterface
{
    protected $model;

    public function __construct(Shop $model)
    {
        $this->model = $model;
        $this->storageImagePath = storage_path() .config('hideyo.storage_path'). "/shop/";
        $this->publicImagePath = public_path() .config('hideyo.public_path'). "/shop/";
    }
  
    /**
     * The validation rules for the model.
     *
     * @param  integer  $shopId id attribute model    
     * @return array
     */
    private function rules($shopId = false)
    {
        $rules = array(
            'title' => 'required|between:4,65|unique:'.$this->model->getTable(),
            'active' => 'required'
        );

        if ($shopId) {
            $rules['title'] =   $rules['title'].',title,'.$shopId;
        }

        return $rules;
    }

    public function create(array $attributes)
    {
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $this->model->slug = null;
        $this->model->fill($attributes);
        $this->model->save();
        
        if (isset($attributes['logo'])) {
            $destinationPath = $this->storageImagePath.$this->model->id;
            $filename =  str_replace(" ", "_", strtolower($attributes['logo']->getClientOriginalName()));
            $upload_success = $attributes['logo']->move($destinationPath, $filename);

            $attributes['logo_file_name'] = $filename;
            $attributes['logo_file_path'] = $upload_success->getRealPath();
            $this->model->fill($attributes);
            $this->model->save();

            if (File::exists($this->model->logo_file_path)) {
                if (!File::exists($this->publicImagePath.$this->model->id)) {
                    File::makeDirectory($this->publicImagePath.$this->model->id, 0777, true);
                }

                if (!File::exists($this->publicImagePath.$this->model->id."/".$this->model->logo_file_name)) {
                    $image = Image::make($this->model->logo_file_path);
                    $image->interlace();
                    $image->save($this->publicImagePath.$this->model->id."/".$this->model->logo_file_name);
                }
            }
        }
        
        return $this->model;
    }

    public function updateById(array $attributes, $shopId)
    {
        $validator = Validator::make($attributes, $this->rules($shopId));

        if ($validator->fails()) {
            return $validator;
        }

        $this->model = $this->find($shopId);
        return $this->updateEntity($attributes);
    }

    public function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            if (isset($attributes['logo'])) {
                File::delete($this->model->logo_file_path);
                $destinationPath = $this->storageImagePath.$this->model->id;

                $filename =  str_replace(" ", "_", strtolower($attributes['logo']->getClientOriginalName()));
                $upload_success = $attributes['logo']->move($destinationPath, $filename);

                $attributes['logo_file_name'] = $filename;
                $attributes['logo_file_path'] = $upload_success->getRealPath();
            }


            $this->model->slug = null;
            $this->model->fill($attributes);
            $this->model->save();

            if (File::exists($this->model->logo_file_path)) {
                if (!File::exists($this->publicImagePath.$this->model->id)) {
                    File::makeDirectory($this->publicImagePath.$this->model->id, 0777, true);
                }

                if (!File::exists($this->publicImagePath.$this->model->id."/".$this->model->logo_file_name)) {
                    $image = Image::make($this->model->logo_file_path);
                    $image->interlace();
                    $image->save($this->publicImagePath.$this->model->id."/".$this->model->logo_file_name);
                }
            }
        }

        return $this->model;
    }

    public function destroy($shopId)
    {
        $this->model = $this->find($shopId);
        File::deleteDirectory($this->publicImagePath.$this->model->id);

        $destinationPath = $this->storageImagePath.$this->model->id;

        File::deleteDirectory($destinationPath);
        $this->model->save();

        return $this->model->delete();
    }

    public function selectAll()
    {
        return $this->model->get();
    }    

    public function checkByUrl($shopUrl)
    {
        $result = $this->model->where('url', '=', $shopUrl)->get()->first();

        if (isset($result->id)) {
            return $result;
        }
        
        return false;        
    }    
}