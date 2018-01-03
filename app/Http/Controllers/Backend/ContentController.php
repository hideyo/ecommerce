<?php namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;

/**
 * ContentController
 *
 * This is the controller of the contents of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Repositories\ContentRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class ContentController extends Controller
{
    public function __construct(
        Request $request,
        ContentRepositoryInterface $content
    ) {
        $this->content = $content;
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $content = $this->content->getModel()->select(
                [
                
                $this->content->getModel()->getTable().'.id',
                $this->content->getModel()->getTable().'.title', $this->content->getModel()->getTable().'.content_group_id', $this->content->getGroupModel()->getTable().'.title as contenttitle']
            )->where($this->content->getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)


            ->with(array('contentGroup'))        ->leftJoin($this->content->getGroupModel()->getTable(), $this->content->getGroupModel()->getTable().'.id', '=', $this->content->getModel()->getTable().'.content_group_id');
            
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
        
        return view('backend.content.index')->with('content', $this->content->selectAll());
    }

    public function create()
    {
        return view('backend.content.create')->with(array('groups' => $this->content->selectGroupAll()->pluck('title', 'id')->toArray()));
    }

    public function store()
    {
        $result  = $this->content->create($this->request->all());

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
        return view('backend.content.edit')->with(array('content' => $this->content->find($contentId), 'groups' => $this->content->selectGroupAll()->pluck('title', 'id')->toArray()));
    }

    public function editSeo($contentId)
    {
        return view('backend.content.edit_seo')->with(array('content' => $this->content->find($contentId)));
    }

    public function update($contentId)
    {

        $result  = $this->content->updateById($this->request->all(), $contentId);

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
        $result  = $this->content->destroy($contentId);

        if ($result) {
            Notification::success('The content was deleted.');
            return redirect()->route('content.index');
        }
    }
}
