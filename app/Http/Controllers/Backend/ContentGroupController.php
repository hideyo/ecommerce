<?php namespace App\Http\Controllers\Backend;

/**
 * ContentGroupController
 *
 * This is the controller of the content groups of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Repositories\ContentRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class ContentGroupController extends Controller
{
    public function __construct(
        Request $request,
        ContentRepositoryInterface $content
    ) {
        $this->request = $request;
        $this->content = $content;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->content->getGroupModel()
            ->select(['id', 'title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = Datatables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('content-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('content-group.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.content_group.index')->with('contentGroup', $this->content->selectAll());
    }

    public function create()
    {
        return view('backend.content_group.create')->with(array());
    }

    public function store()
    {
        $result  = $this->content->createGroup($this->request->all());

        if (isset($result->id)) {
            Notification::success('The content was inserted.');
            return redirect()->route('content-group.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($contentGroupId)
    {
        return view('backend.content_group.edit')->with(array('contentGroup' => $this->content->findGroup($contentGroupId)));
    }

    public function editSeo($contentGroupId)
    {
        return view('backend.content_group.edit_seo')->with(array('contentGroup' => $this->content->find($contentGroupId)));
    }

    public function update($contentGroupId)
    {
        $result  = $this->content->updateGroupById($this->request->all(), $contentGroupId);

        if (isset($result->id)) {
            if ($this->request->get('seo')) {
                Notification::success('ContentGroup seo was updated.');
                return redirect()->route('content-group.edit_seo', $contentGroupId);
            } elseif ($this->request->get('content-combination')) {
                Notification::success('ContentGroup combination leading attribute group was updated.');
                return redirect()->route('content-group.{contentId}.content-combination.index', $contentGroupId);
            } else {
                Notification::success('ContentGroup was updated.');
                return redirect()->route('content-group.index');
            }
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }        
       
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage
     * @param  int  $contentGroupId
     * @return Redirect
     */
    public function destroy($contentGroupId)
    {
        $result  = $this->content->destroyGroup($contentGroupId);

        if ($result) {
            Notification::success('The content was deleted.');
            return redirect()->route('content-group.index');
        }
    }
}
