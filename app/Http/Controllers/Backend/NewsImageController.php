<?php namespace App\Http\Controllers\Backend;
/**
 * NewsImageController
 *
 * This is the controller for the images of a news item
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

class NewsImageController extends Controller
{
    public function __construct(Request $request, NewsRepositoryInterface $news)
    {
        $this->news = $news;
        $this->request = $request;
    }

    public function index($newsId)
    {
        $news = $this->news->find($newsId);
        if ($this->request->wantsJson()) {

            $image = $this->news->getImageModel()->select(
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
        $news = $this->news->find($newsId);
        return view('backend.news_image.create')->with(array('news' => $news));
    }

    public function store($newsId)
    {
        $result  = $this->news->createImage($this->request->all(), $newsId);
 
        if (isset($result->id)) {
            Notification::success('The news image was inserted.');
            return redirect()->route('news-images.index', $newsId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);  
    }

    public function edit($newsId, $newsImageId)
    {
        $news = $this->news->find($newsId);
        return view('backend.news_image.edit')->with(array('newsImage' => $this->news->findImage($newsImageId), 'news' => $news));
    }

    public function update($newsId, $newsImageId)
    {
        $result  = $this->news->updateImageById($this->request->all(), $newsId, $newsImageId);

        if (isset($result->id)) {
            Notification::success('The news image was updated.');
            return redirect()->route('news-images.index', $newsId);
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        return redirect()->back()->withInput()->withErrors($result);
    }

    public function destroy($newsId, $newsImageId)
    {
        $result  = $this->news->destroyImage($newsImageId);

        if ($result) {
            Notification::success('The file was deleted.');
            return redirect()->route('news-images.index', $newsId);
        }
    }
}
