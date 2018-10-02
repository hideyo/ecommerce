<?php namespace App\Http\Controllers\Backend;

/**
 * ContentImageController
 *
 * This is the controller of the content images of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\ContentRepository;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class ContentImageController extends Controller
{
    public function __construct(Request $request, ContentRepository $content)
    {
        $this->request = $request;
        $this->content = $content;
    }

    public function index($contentId)
    {
        $content = $this->content->find($contentId);
        if ($this->request->wantsJson()) {

            $image = $this->content->getImageModel()->select(
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
        $content = $this->content->find($contentId);
        return view('backend.content_image.create')->with(array('content' => $content));
    }

    public function store($contentId)
    {
        $result  = $this->content->createImage($this->request->all(), $contentId);
 
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
        $content = $this->content->find($contentId);
        return view('backend.content_image.edit')->with(array('contentImage' => $this->content->findImage($contentImageId), 'content' => $content));
    }

    public function update($contentId, $contentImageId)
    {
        $result  = $this->content->updateImageById($this->request->all(), $contentId, $contentImageId);

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
        $result  = $this->content->destroyImage($contentImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('content.{contentId}.images.index', $contentId);
        }
    }
}
