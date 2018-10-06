<?php namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;

/**
 * ContentController
 *
 * This is the controller of the contents of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Ecommerce\Framework\Services\Content\ContentFacade as ContentService;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class ContentController extends Controller
{
    public function __construct(
        Request $request
    ) {

        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $content = ContentService::getModel()->select(
                [
                
                ContentService::getModel()->getTable().'.id',
                ContentService::getModel()->getTable().'.title', ContentService::getModel()->getTable().'.content_group_id', ContentService::getGroupModel()->getTable().'.title as contenttitle']
            )->where(ContentService::getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)


            ->with(array('contentGroup'))        ->leftJoin(ContentService::getGroupModel()->getTable(), ContentService::getGroupModel()->getTable().'.id', '=', ContentService::getModel()->getTable().'.content_group_id');
            
            $datatables = Datatables::of($content)

            ->filterColumn('title', function ($query, $keyword) {
                $query->whereRaw("content.title like ?", ["%{$keyword}%"]);
            })
            ->addColumn('contentgroup', function ($content) {
                return $content->contenttitle;
            })
            ->addColumn('action', function ($content) {
                $deleteLink = Form::deleteajax(url()->route('content.destroy', $content->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('content.edit', $content->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.content.index')->with('content', ContentService::selectAll());
    }

    public function create()
    {
        return view('backend.content.create')->with(array('groups' => ContentService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function store()
    {
        $result  = ContentService::create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The content was inserted.');
            return redirect()->route('content.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($contentId)
    {
        return view('backend.content.edit')->with(array('content' => ContentService::find($contentId), 'groups' => ContentService::selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function editSeo($contentId)
    {
        return view('backend.content.edit_seo')->with(array('content' => ContentService::find($contentId)));
    }

    public function update($contentId)
    {

        $result  = ContentService::updateById($this->request->all(), $contentId);

        if (isset($result->id)) {
            if ($this->request->get('seo')) {
                Notification::success('Content seo was updated.');
                return redirect()->route('content.edit_seo', $contentId);
            } elseif ($this->request->get('content-combination')) {
                Notification::success('Content combination leading attribute group was updated.');
                return redirect()->route('content.{contentId}.content-combination.index', $contentId);
            } else {
                Notification::success('Content was updated.');
                return redirect()->route('content.edit', $contentId);
            }
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
       
        return redirect()->back()->withInput();
    }

    public function destroy($contentId)
    {
        $result  = ContentService::destroy($contentId);

        if ($result) {
            Notification::success('The content was deleted.');
            return redirect()->route('content.index');
        }
    }
}
