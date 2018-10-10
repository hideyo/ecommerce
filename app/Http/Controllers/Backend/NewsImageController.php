<?php namespace App\Http\Controllers\Backend;
/**
 * NewsImageController
 *
 * This is the controller for the images of a news item
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\News\NewsFacade as NewsService;

class NewsImageController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index($newsId)
    {
        $news = NewsService::find($newsId);
        if ($this->request->wantsJson()) {

            $image = NewsService::getImageModel()->select(
                ['id',
                'file', 'news_id']
            )->where('news_id', '=', $newsId);
            
            $datatables = Datatables::of($image)

            ->addColumn('thumb', function ($image) use ($newsId) {


                return '<img src="/files/news/100x100/'.$image->news_id.'/'.$image->file.'"  />';
            })


            ->addColumn('action', function ($image) use ($newsId) {
                $deleteLink = Form::deleteajax(url()->route('news-images.destroy', array('newsId' => $newsId, 'id' => $image->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('news-images.edit', array('newsId' => $newsId, 'id' => $image->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;

                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.news_image.index')->with(array('news' => $news));
    }

    public function create($newsId)
    {
        $news = NewsService::find($newsId);
        return view('backend.news_image.create')->with(array('news' => $news));
    }

    public function store($newsId)
    {
        $result  = NewsService::createImage($this->request->all(), $newsId);
        return NewsService::notificationRedirect(array('news-images.index', $newsId), $result, 'The news image was inserted.');
    }

    public function edit($newsId, $newsImageId)
    {
        $news = NewsService::find($newsId);
        return view('backend.news_image.edit')->with(array('newsImage' => NewsService::findImage($newsImageId), 'news' => $news));
    }

    public function update($newsId, $newsImageId)
    {
        $result  = NewsService::updateImageById($this->request->all(), $newsId, $newsImageId);
        return NewsService::notificationRedirect(array('news-images.index', $newsId), $result, 'The news image was updated.');
    }

    public function destroy($newsId, $newsImageId)
    {
        $result  = NewsService::destroyImage($newsImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('news-images.index', $newsId);
        }
    }
}
