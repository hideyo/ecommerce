<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * CouponController
 *
 * This is the controller for the shop clients
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */


use Hideyo\Ecommerce\Framework\Services\Coupon\CouponFacade as CouponService;

use Hideyo\Ecommerce\Framework\Services\Product\ProductFacade as ProductService;
use Hideyo\Ecommerce\Framework\Services\ProductCategory\ProductCategoryFacade as ProductCategoryService;
use Hideyo\Ecommerce\Framework\Services\SendingMethod\SendingMethodFacade as SendingMethodService;
use Hideyo\Ecommerce\Framework\Services\PaymentMethod\PaymentMethodFacade as PaymentMethodService;

use Illuminate\Http\Request;
use Notification;
use DataTables;
use Form;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = CouponService::getModel()
            ->where(CouponService::getModel()->getTable().'.shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
            ->with(array('couponGroup'));
            
            $datatables = DataTables::of($query)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereRaw("coupon.title like ?", ["%{$keyword}%"]);
            })

            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax('/admin/coupon/'. $query->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="/admin/coupon/'.$query->id.'/edit" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.coupon.index')->with('coupon', CouponService::selectAll());
    }

    public function create()
    {
        return view('backend.coupon.create')->with(array(
            'products'          => ProductService::selectAll()->pluck('title', 'id'),
            'productCategories' => ProductCategoryService::selectAll()->pluck('title', 'id'),
            'groups'            => CouponService::selectAllGroups()->pluck('title', 'id')->toArray(),
            'sendingMethods'    => SendingMethodService::selectAll()->pluck('title', 'id'),
            'paymentMethods'    => PaymentMethodService::selectAll()->pluck('title', 'id')
        ));
    }

    public function store(Request $request)
    {
        $result  = CouponService::create($request->all());
        return CouponService::notificationRedirect('coupon.index', $result, 'The coupon was inserted.');
    }

    public function edit($couponId)
    {
        return view('backend.coupon.edit')->with(
            array(
            'coupon' => CouponService::find($couponId),
            'products' => ProductService::selectAll()->pluck('title', 'id'),
            'groups' => CouponService::selectAllGroups()->pluck('title', 'id')->toArray(),
            'productCategories' => ProductCategoryService::selectAll()->pluck('title', 'id'),
            'sendingMethods' => SendingMethodService::selectAll()->pluck('title', 'id'),
            'paymentMethods' => PaymentMethodService::selectAll()->pluck('title', 'id'),
            )
        );
    }

    public function update(Request $request, $couponId)
    {
        $result  = CouponService::updateById($request->all(), $couponId);
        return CouponService::notificationRedirect('coupon.index', $result, 'The coupon was updated.');
    }

    public function destroy($couponId)
    {
        $result  = CouponService::destroy($couponId);

        if ($result) {
            Notification::success('The coupon was deleted.');
            return redirect()->route('coupon.index');
        }
    }
}
