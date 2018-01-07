<?php namespace App\Http\Controllers\Backend;

/**
 * CouponGroupController
 *
 * This is the controller of the coupons of the shop
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use App\Http\Controllers\Controller;
use Hideyo\Ecommerce\Framework\Repositories\CouponRepositoryInterface;
use Illuminate\Http\Request;
use Notification;
use Form;

class CouponGroupController extends Controller
{
    public function __construct(
        Request $request,
        CouponRepositoryInterface $coupon
    ) {
        $this->coupon = $coupon;
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->wantsJson()) {

            $query = $this->coupon->getGroupModel()->select(['id', 'title'])
            ->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);

            $datatables = \Datatables::of($query)
            ->addColumn('action', function ($query) {
                $deleteLink = Form::deleteajax(url()->route('coupon-group.destroy', $query->id), 'Delete', '', array('class'=>'btn btn-sm btn-danger'));
                $links = '<a href="'.url()->route('coupon-group.edit', $query->id).'" class="btn btn-sm btn-success"><i class="fi-pencil"></i>Edit</a>  '.$deleteLink;
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.coupon-group.index')->with('couponGroup', $this->coupon->selectAll());
    }

    public function create()
    {
        return view('backend.coupon-group.create')->with(array());
    }

    public function store()
    {
        $result  = $this->coupon->createGroup($this->request->all());

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
        return view('backend.coupon-group.edit')->with(array('couponGroup' => $this->coupon->findGroup($couponGroupId)));
    }

    public function update($couponGroupId)
    {

        $result  = $this->coupon->updateGroupById($this->request->all(), $couponGroupId);

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
        $result  = $this->coupon->destroyGroup($couponGroupId);

        if ($result) {
            Notification::success('The coupon was deleted.');
            return redirect()->route('coupon-group.index');
        }
    }
}
