<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * ClientAddressController
 *
 * This is the controller for the client addresses
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;

use Illuminate\Http\Request;
use Notification;
use Form;
use Datatables;

class ClientAddressController extends Controller
{
    public function __construct(
        Request $request)
    {
        $this->request          = $request;
    }

    public function index($clientId)
    {
        $client = ClientService::find($clientId);
        if ($this->request->wantsJson()) {

            $addresses = ClientService::getAddressModel()->select(
                [
                'id',
                'firstname',
                'street',
                'housenumber',
                'housenumber_suffix',
                'city',
                'lastname']
            )->with(array('clientDeliveryAddress', 'clientBillAddress'))->where('client_id', '=', $clientId);
            
            $datatables = Datatables::of($addresses)
            ->addColumn('housenumber', function ($addresses) use ($clientId) {
                return $addresses->housenumber.$addresses->housenumber_suffix;
            })
            ->addColumn('delivery', function ($addresses) {
                if ($addresses->clientDeliveryAddress()->count()) {
                    return '<span class="glyphicon glyphicon-ok icon-green"></span>';
                }
                
                return '<span class="glyphicon glyphicon-remove icon-red"></span>';
                
            })

            ->addColumn('bill', function ($addresses) {
                if ($addresses->clientBillAddress()->count()) {
                          return '<span class="glyphicon glyphicon-ok icon-green"></span>';
                }

                return '<span class="glyphicon glyphicon-remove icon-red"></span>';
            })
            ->addColumn('action', function ($addresses) use ($clientId) {
                $deleteLink = Form::deleteajax(url()->route('client.addresses.destroy', array('clientId' => $clientId, 'clientAddressId' => $addresses->id)), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('client.addresses.edit', array('clientId' => $clientId, 'clientAddressId' => $addresses->id)).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
            
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.client_address.index')->with(array('client' => $client));
    }

    public function create($clientId)
    {
        $client = ClientService::find($clientId);
        return view('backend.client_address.create')->with(array('client' => $client));
    }

    public function store($clientId)
    {
        $result  = ClientService::createAddress($this->request->all(), $clientId);
 
        if ($result->id) {
            Notification::success('The client address is inserted.');
            return redirect()->route('client.addresses.index', $clientId);
        }
        
        Notification::error('field are required');
        return redirect()->back()->withInput()->withErrors($result->errors()->all());
    }

    public function edit($clientId, $id)
    {
        $client = ClientService::find($clientId);
        return view('backend.client_address.edit')->with(array('clientAddress' => ClientService::findAddress($id), 'client' => $client));
    }

    public function update($clientId, $id)
    {
        $result  = ClientService::editAddress($clientId, $id, $this->request->all());

        if (!$result->id) {
            return redirect()->back()->withInput()->withErrors($result->errors()->all());
        }
    
        Notification::success('The client address is updated.');    
        return redirect()->route('client.addresses.index', $clientId); 
    }

    public function destroy($clientId, $id)
    {
        $result  = ClientService::destroyAddress($id);

        if ($result) {
            Notification::success('The client address is deleted.');
            return redirect()->route('client.addresses.index', $clientId);
        }
    }
}
