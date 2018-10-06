<?php namespace App\Http\Controllers\Backend;

/**
 * CouponGroupController
 *
 * This is the controller of the coupons of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use Form;

use Hideyo\Ecommerce\Framework\Services\Coupon\CouponFacade as CouponService;

class CouponGroupController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = CouponService::getGroupModel()->select(['id', 'title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = \Datatables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('coupon-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'));
                $links = '<a href="'.url()->route('coupon-group.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.coupon-group.index')->with('couponGroup', CouponService::selectAll());
    }

    public function create()
    {
        return view('backend.coupon-group.create')->with(array());
    }

    public function store()
    {
        $result  = CouponService::createGroup($this->request->all());

        if (isset($result->id)) {
            Notification::success('The coupon was inserted.');
            return redirect()->route('coupon-group.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
        return redirect()->back()->withInput();
    }

    public function edit($couponGroupId)
    {
        return view('backend.coupon-group.edit')->with(array('couponGroup' => CouponService::findGroup($couponGroupId)));
    }

    public function update($couponGroupId)
    {

        $result  = CouponService::updateGroupById($this->request->all(), $couponGroupId);

        if (isset($result->id)) {
            if ($this->request->get('seo')) {
                Notification::success('CouponGroup seo was updated.');
                return redirect()->route('coupon-group.edit_seo', $couponGroupId);
            } elseif ($this->request->get('coupon-combination')) {
                Notification::success('CouponGroup combination leading attribute group was updated.');
                return redirect()->route('coupon-group.{couponId}.coupon-combination.index', $couponGroupId);
            } else {
                Notification::success('CouponGroup was updated.');
                return redirect()->route('coupon-group.edit', $couponGroupId);
            }
        }

        foreach ($result->errors()->all() as $error) {
            Notification::error($error);
        }
        
       
        return redirect()->back()->withInput();
    }

    public function destroy($couponGroupId)
    {
        $result  = CouponService::destroyGroup($couponGroupId);

        if ($result) {
            Notification::success('The coupon was deleted.');
            return redirect()->route('coupon-group.index');
        }
    }
}
