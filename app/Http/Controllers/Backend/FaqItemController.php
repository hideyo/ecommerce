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
use DataTables;
use Form;

use Hideyo\Ecommerce\Framework\Services\Faq\FaqFacade as FaqService;

class FaqItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = FaqService::getModel()->select(
                ['faq_item.id', 
                'faq_item.faq_item_group_id',
                'faq_item.question', 
                'faq_item.answer', 
                'faq_item_group.title as grouptitle']
            )
            ->with(array('faqItemGroup'))
            ->leftJoin('faq_item_group', 'faq_item_group.id', '=', 'faq_item.faq_item_group_id')
            ->where('faq_item.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = DataTables::of($query)
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

    public function store(Request $request)
    {
        $result  = FaqService::create($request->all());
        return FaqService::notificationRedirect('faq.index', $result, 'FaqItem was inserted.');
    }

    public function edit($faqItemId)
    {
        $groups = FaqService::selectAllGroups()->pluck('title', 'id')->toArray();
        return view('backend.faq-item.edit')->with(array('faq' => FaqService::find($faqItemId), 'groups' => $groups));
    }

    public function update(Request $request, $faqId)
    {
        $result  = FaqService::updateById($request->all(), $faqId);
        return FaqService::notificationRedirect('faq.index', $result, 'FaqItem was updated.');
    }

    public function destroy($faqItemId)
    {
        $result  = FaqService::destroy($faqItemId);

        if ($result) {
            flash('The faq was deleted.');
            return redirect()->route('faq.index');
        }
    }
}
