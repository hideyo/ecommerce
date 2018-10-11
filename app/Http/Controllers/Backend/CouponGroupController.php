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
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = CouponService::getGroupModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

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

    public function store(Request $request)
    {
        $result  = CouponService::createGroup($request->all());
        return CouponService::notificationRedirect('coupon-group.index', $result, 'The coupon group was inserted.');
    }

    public function edit($couponGroupId)
    {
        return view('backend.coupon-group.edit')->with(array('couponGroup' => CouponService::findGroup($couponGroupId)));
    }

    public function update(Request $request, $couponGroupId)
    {
        $result  = CouponService::updateGroupById($request->all(), $couponGroupId);
        return CouponService::notificationRedirect('coupon-group.index', $result, 'The coupon group was updated.');
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
