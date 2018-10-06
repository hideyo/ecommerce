<?php namespace App\Http\Controllers\Backend;

/**
 * ContentImageController
 *
 * This is the controller of the content images of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Content\ContentFacade as ContentService;

class ContentImageController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index($contentId)
    {
        $content = ContentService::find($contentId);
        if ($this->request->wantsJson()) {

            $image = ContentService::getImageModel()->select(
                [
                
                'id',
                'file', 'content_id']
            )->where('content_id', '=', $contentId);
            
            $datatables = Datatables::of($image)

            ->addColumn('thumb', function ($image) use ($contentId) {
                return '<img src="/files/content/100x100/'.$image->content_id.'/'.$image->file.'"  />';
            })
            ->addColumn('action', function ($image) use ($contentId) {
                $deleteLink = Form::deleteajax('/admin/content/'.$contentId.'/images/'. $image->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/content/'.$contentId.'/images/'.$image->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;

                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.content_image.index')->with(array('content' => $content));
    }

    public function create($contentId)
    {
        $content = ContentService::find($contentId);
        return view('backend.content_image.create')->with(array('content' => $content));
    }

    public function store($contentId)
    {
        $result  = ContentService::createImage($this->request->all(), $contentId);
 
        if (isset($result->id)) {
            Notification::success('The content image was inserted.');
            return redirect()->route('content.{contentId}.images.index', $contentId);
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);
    }

    public function edit($contentId, $contentImageId)
    {
        $content = ContentService::find($contentId);
        return view('backend.content_image.edit')->with(array('contentImage' => ContentService::findImage($contentImageId), 'content' => $content));
    }

    public function update($contentId, $contentImageId)
    {
        $result  = ContentService::updateImageById($this->request->all(), $contentId, $contentImageId);

        if (isset($result->id)) {
            Notification::success('The content image was updated.');
            return redirect()->route('content.{contentId}.images.index', $contentId);
        } else {
            foreach ($result->errors()->all() as $error) {
                Notification::error($error);
            }
            return redirect()->back()->withInput()->withErrors($result);
        }
    }

    public function destroy($contentId, $contentImageId)
    {
        $result  = ContentService::destroyImage($contentImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('content.{contentId}.images.index', $contentId);
        }
    }
}
