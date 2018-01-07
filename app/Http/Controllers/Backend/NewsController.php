<?php namespace App\Http\Controllers\Backend;

/**
 * NewsController
 *
 * This is the controller of the news of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\NewsRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\TaxRateRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\PaymentMethodRepositoryInterface;
use Hideyo\Ecommerce\Framework\Repositories\NewsGroupRepositoryInterface;

use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;
use Auth;

class NewsController extends Controller
{
    public function __construct(Request $request, NewsRepositoryInterface $news)
    {
        $this->request = $request;
        $this->news = $news;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->news->getModel()->select(
                [
                $this->news->getModel()->getTable().'.id',
                $this->news->getModel()->getTable().'.title',
                $this->news->getGroupModel()->getTable().'.title as newsgroup']
            )->where($this->news->getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
            ->with(array('newsGroup'))        ->leftJoin($this->news->getGroupModel()->getTable(), $this->news->getGroupModel()->getTable().'.id', '=', 'news_group_id');
            
            $datatables = Datatables::of($query)
            ->filterColumn('title', function ($query, $keyword) {

                $query->where(
                    function ($query) use ($keyword) {
                        $query->whereRaw("news.title like ?", ["%{$keyword}%"]);
                        ;
                    }
                );
            })
            ->addColumn('newsgroup', function ($query) {
                return $query->newstitle;
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('news.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('news.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.news.index')->with('news', $this->news->selectAll());
    }

    public function create()
    {
        return view('backend.news.create')->with(array('groups' => $this->news->selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function store()
    {
        $result  = $this->news->create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The news was inserted.');
            return redirect()->route('news.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($newsId)
    {
        return view('backend.news.edit')->with(array('news' => $this->news->find($newsId), 'groups' => $this->news->selectAllGroups()->pluck('title', 'id')->toArray()));
    }

    public function reDirectoryAllImages()
    {
        $this->newsImage->reDirectoryAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);

        return redirect()->route('news.index');
    }

    public function refactorAllImages()
    {
        $this->newsImage->refactorAllImagesByShopId(auth('hideyobackend')->user()->selected_shop_id);

        return redirect()->route('news.index');
    }

    public function editSeo($newsId)
    {
        return view('backend.news.edit_seo')->with(array('news' => $this->news->find($newsId)));
    }
    
    public function update($newsId)
    {
        $result  = $this->news->updateById($this->request->all(), $newsId);

        if (isset($result->id)) {
            Notification::success('The news was updated.');
            return redirect()->route('news.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function destroy($newsId)
    {
        $result  = $this->news->destroy($newsId);

        if ($result) {
            Notification::success('The news was deleted.');
            return redirect()->route('news.index');
        }
    }
}
