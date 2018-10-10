<?php namespace App\Http\Controllers\Backend;

/**
 * FaqItemController
 *
 * This is the controller of the faqs of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Datatables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Faq\FaqFacade as FaqService;

class FaqItemController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = FaqService::getModel()->select(
                [
                
                'faq_item.id', 
                'faq_item.faq_item_group_id',
                'faq_item.question', 
                'faq_item.answer', 
                'faq_item_group.title as grouptitle']
            )
            ->with(array('faqItemGroup'))
            ->leftJoin('faq_item_group', 'faq_item_group.id', '=', 'faq_item.faq_item_group_id')
            ->where('faq_item.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = Datatables::of($query)
            ->addColumn('faqitemgroup', function ($query) {
                return $query->grouptitle;
            })
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('faq.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('faq.edit', $query->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);

        }
        
        return view('backend.faq-item.index')->with('faq', FaqService::selectAll());
    }

    public function create()
    {
        $groups = FaqService::selectAllGroups()->pluck('title', 'id')->toArray();
        return view('backend.faq-item.create')->with(array('groups' => $groups));
    }

    public function store()
    {
        $result  = FaqService::create($this->request->all());

        if (isset($result->id)) {
            Notification::success('The faq was inserted.');
            return redirect()->route('faq.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($faqItemId)
    {

        $groups = FaqService::selectAllGroups()->pluck('title', 'id')->toArray();
        return view('backend.faq-item.edit')->with(array('faq' => FaqService::find($faqItemId), 'groups' => $groups));
    }

    public function editSeo($faqItemId)
    {
        return view('backend.faq-item.edit_seo')->with(array('faq' => FaqService::find($faqItemId)));
    }

    public function update($faqId)
    {
        $result  = FaqService::updateById($this->request->all(), $faqId);

        if (isset($result->id)) {
            Notification::success('FaqItem was updated.');
            return redirect()->route('faq.index');
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
       
        return redirect()->back()->withInput();
    }

    public function destroy($faqItemId)
    {
        $result  = FaqService::destroy($faqItemId);

        if ($result) {
            Notification::success('The faq was deleted.');
            return redirect()->route('faq.index');
        }
    }
}
