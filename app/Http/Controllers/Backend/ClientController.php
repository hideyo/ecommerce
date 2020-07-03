<?php namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;

/**
 * ClientController
 *
 * This is the controller for the shop clients
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */

use Hideyo\Ecommerce\Framework\Services\Client\ClientFacade as ClientService;

use Illuminate\Http\Request;
use Notification;
use Mail;
use Excel;
use Form;
use DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $clients = ClientService::getModel()->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id);
            
            $datatables = DataTables::of($clients)
            ->addColumn('last_login', function ($clients) {
                return date('d F H:i', strtotime($clients->last_login));
            })
            ->addColumn('action', function ($clients) {
                $deleteLink = Form::deleteajax(url()->route('client.destroy', $clients->id), 'Delete', '', array('class'=>'btn btn-default btn-sm btn-danger'));
                $links = '<a href="'.url()->route('client.edit', $clients->id).'" class="btn btn-default btn-sm btn-success"><i class="entypo-pencil"></i>Show</a>  '.$deleteLink;
            
                if (!$clients->active || !$clients->confirmed) {
                    $links .= ' <a href="'.url()->route('client.activate', $clients->id).'" class="btn btn-default btn-sm btn-info">activate</a>';
                } else {
                    $links .= ' <a href="'.url()->route('client.de-activate', $clients->id).'" class="btn btn-default btn-sm btn-info">block</a>';
                }
                
                return $links;
            });

            return $datatables->make(true);
        }
        
        return view('backend.client.index')->with('client', ClientService::selectAll());    
    }

    public function create()
    {
        return view('backend.client.create')->with(array());
    }

    public function getActivate($clientId)
    {
        return view('backend.client.activate')->with(array('client' => ClientService::find($clientId)));
    }

    public function getDeActivate($clientId)
    {
        return view('backend.client.de-activate')->with(array('client' => ClientService::find($clientId)));
    }

    public function postActivate(Request $request, $clientId)
    {
        $input = $request->all();
        $result  = ClientService::activate($clientId);

        if ($input['send_mail']) {
                Mail::send('frontend.email.activate-mail', array('user' => $result->toArray(), 'billAddress' => $result->clientBillAddress->toArray()), function ($message) use ($result) {
                    $message->to($result['email'])->from('info@hideyo.nl', 'Hideyo')->subject('Toegang tot account.');
                });

                Notification::container('foundation')->success('Uw account is geactiveerd.');
        }
        
        flash('The client was activate.');
        return redirect()->route('client.index');
    }

    public function postDeActivate($clientId)
    {
        ClientService::deactivate($clientId);
        flash('The client was deactivate.');
        return redirect()->route('client.index');
    }

    public function store(Request $request)
    {
        $result  = ClientService::create($request->all());
        return ClientService::notificationRedirect('client.index', $result, 'The client was inserted.');
    }

    public function edit($clientId)
    {
        $addresses = ClientService::selectAddressesByClientId($clientId);

        $addressesList = array();

        if ($addresses) {
            foreach ($addresses as $row) {
                $addressesList[$row->id] = $row->street.' '.$row->housenumber;
                if ($row->housenumber_suffix) {
                    $addressesList[$row->id] .= $row->housenumber_suffix;
                }

                $addressesList[$row->id] .= ', '.$row->city;
            }
        }

        return view('backend.client.edit')->with(array('client' => ClientService::find($clientId), 'addresses' => $addressesList));
    }

    public function getExport()
    {
        return view('backend.client.export')->with(array());
    }

    public function postExport()
    {
        $result  =  ClientService::selectAllExport();
        Excel::create('export', function ($excel) use ($result) {

            $excel->sheet('Clients', function ($sheet) use ($result) {
                $newArray = array();
                foreach ($result as $row) {
                    $firstname = null;
                    if($row->clientBillAddress) {
                        $firstname = $row->clientBillAddress->firstname;
                    }

                    $lastname = null;
                    if($row->clientBillAddress) {
                        $lastname = $row->clientBillAddress->lastname;
                    }

                    $gender = null;
                    if($row->clientBillAddress) {
                        $gender = $row->clientBillAddress->gender;
                    }

                    $newArray[$row->id] = array(
                        'email' => $row->email,
                        'company' => $row->company,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'gender' => $gender
                    );
                }

                $sheet->fromArray($newArray);
            });
        })->download('xls');


        flash('The product export is completed.');
        return redirect()->route('product.index');
    }

    public function update(Request $request, $clientId)
    {
        $result  = ClientService::updateById($request->all(), $clientId);
        $input = $request->all();
        if (isset($result->id)) {
            if ($result->active) {
                if ($input['send_mail']) {
                    Mail::send('frontend.email.activate-mail', array('user' => $result->toArray(), 'billAddress' => $result->clientBillAddress->toArray()), function ($message) use ($result) {
                        $message->to($result['email'])->from('info@hideyo.nl', 'Hideyo')->subject('Toegang tot account.');
                    });

                    Notification::container('foundation')->success('Uw account is geactiveerd.');
                }
                
            }

            flash('The client was updated.');
            return redirect()->route('client.index');
        }
        
        foreach ($result->errors()->all() as $error) {
            flash($error);
        }
        return redirect()->back()->withInput();
    }

    public function destroy($clientId)
    {
        $result  = ClientService::destroy($clientId);

        if ($result) {
            flash('The client was deleted.');
            return redirect()->route('client.index');
        }
    }
}
