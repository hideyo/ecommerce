@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li><a href="#">Overzicht</a></li>
        </ul>
    </div>
</div>      

<div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-5">
            <div class="account-block">
                <h3>Account</h3>
                @notification('foundation')
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Email:</td>
                            <td>{!! $user->email !!}</td>
                        </tr>
                        <tr>
                            <td>Wachtwoord:</td>
                            <td>*****</td>
                        </tr>
                    </tbody>
                </table>   

                <a href="/account/edit-account" class="btn float-right btn-info">Wijzig gegevens</a>        
            </div>

        </div>

        <div class="col-sm-12 col-md-12 col-lg-offset-2 col-lg-5 login">

            <div class="row">
                <div class="small-15 medium-10 large-7 columns">
                    <div class="address-block">
                        <h3>Factuuradres</h3>

                        @notification()
                        {!! Form::model($user->clientBillAddress, array('method' => 'post', 'url' => array('/account/edit-address/bill'), 'files' => true, 'class' => 'box login')) !!}

                        @include('frontend.account._default_account_fields')       

                        <a href="/account" class="btn btn-danger">Annuleer</a>
                        <button type="submit" class="btn btn-success">Wijzig</button>

                        {!! Form::close() !!}      
         
                    </div>
                </div>

                <div class="small-15 medium-10 large-7 columns">
                    <div class="address-block">
                        <h3>Afleveradres</h3>
          
                        <ul>
                            <li>{!! $user->clientDeliveryAddress->firstname !!} {!! $user->clientDeliveryAddress['lastname']  !!}</li>


                            <li>{!! $user->clientDeliveryAddress['street']  !!} {!! $user->clientDeliveryAddress['housenumber']  !!} {!! $user->clientDeliveryAddress['housenumber_suffix']  !!}</li>
                            <li>{!! $user->clientDeliveryAddress['zipcode']  !!} {!! $user->clientDeliveryAddress['city']  !!}</li>
                            <li>

                                @if($user->clientDeliveryAddress->countryObject)
                                @if($user->clientDeliveryAddress->countryObject->iso_3166_2 == 'NL')
                                Nederland
                                @elseif($user->clientDeliveryAddress->countryObject->iso_3166_2 == 'BE')
                                Belgie
                                @else
                                {!! $user->clientDeliveryAddress->countryObject->name !!}
                                @endif
                                @endif


                            </li>
                            <li>{!! $user->clientDeliveryAddress['phone']  !!}</li>
                        </ul> 
                        <a href="/account/edit-address/delivery" class="btn btn-success">Wijzig afleveradres</a>        
            
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@stop